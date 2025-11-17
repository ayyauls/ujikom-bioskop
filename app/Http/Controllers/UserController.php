<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Booking;
use App\Models\Transaction;
use App\Models\Schedule;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Midtrans\Config;
use Midtrans\Snap;

class UserController extends Controller
{
    // ============================
    // 🏠 HALAMAN UTAMA
    // ============================
    public function index()
    {
        $nowPlaying = Film::where('status', 'now_playing')->get();
        $upComing = Film::where('status', 'coming_soon')->get();

        return view('landing.index', compact('nowPlaying', 'upComing'));
    }

    // ============================
    // 🎬 DETAIL FILM
    // ============================
    public function detail($id)
    {
        $film = Film::findOrFail($id);
        $todaySchedules = Schedule::where('film_id', $id)
            ->whereDate('show_date', today())
            ->where('is_active', true)
            ->with('studio')
            ->orderBy('show_time')
            ->get();
        return view('film_detail', compact('film', 'todaySchedules'));
    }

    // ============================
    // 🔐 REGISTER & LOGIN
    // ============================
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:users,phone',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', '✅ Akun berhasil dibuat! Silakan login.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->role === 'owner') {
                return redirect()->route('owner.dashboard')->with('success', '🎉 Selamat datang kembali di BioskopKu!');
            } elseif ($user->role === 'kasir') {
                return redirect()->route('kasir.dashboard')->with('success', '🎉 Selamat datang kembali di BioskopKu!');
            }

            return redirect()->route('home')->with('success', '🎉 Selamat datang kembali di BioskopKu!');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', '👋 Anda telah logout.');
    }

    // ============================
    // 🪑 PILIH KURSI
    // ============================
    public function selectSeat(Request $request, $id)
    {
        $film = Film::findOrFail($id);
        $scheduleId = $request->query('schedule_id');
        
        if (!$scheduleId) {
            return redirect()->route('film.detail', $id)->withErrors('Jadwal belum dipilih.');
        }
        
        $schedule = Schedule::findOrFail($scheduleId);

        // Auto-expire pending transactions yang sudah melewati expired_at
        $this->autoExpireTransactions();

        // Ambil kursi yang sudah dipesan (paid atau pending yang belum expired)
        $bookedSeats = Booking::where('film_id', $id)
            ->where(function($query) use ($scheduleId, $schedule) {
                $query->where('schedule_id', $scheduleId)
                      ->orWhere(function($q) use ($schedule) {
                          $q->where('showtime', $schedule->show_time)
                            ->whereDate('booking_date', $schedule->show_date);
                      });
            })
            ->where(function($query) {
                $query->where('status', 'paid')
                      ->orWhere(function($q) {
                          $q->where('status', 'pending')
                            ->whereHas('transaction', function($qt) {
                                $qt->where('status', 'pending')
                                   ->where(function($subq) {
                                       $subq->whereNull('expired_at')
                                            ->orWhere('expired_at', '>', now());
                                   });
                            });
                      });
            })
            ->pluck('seat_number')
            ->toArray();

        // Ambil kursi yang disabled oleh admin
        $disabledSeats = Seat::where('studio_id', $schedule->studio_id)
            ->where('is_available', false)
            ->pluck('seat_number')
            ->toArray();

        return view('booking.select_seat', compact('film', 'bookedSeats', 'schedule', 'disabledSeats'));
    }

    // ============================
    // 🎟️ BOOKING KURSI
    // ============================
    public function bookSeat(Request $request, $id)
    {
        $request->validate([
            'seats' => 'required|array|min:1',
            'schedule_id' => 'required',
        ]);

        $film = Film::findOrFail($id);
        $user = Auth::user();
        
        if ($user->role !== 'user') {
            return back()->withErrors(['error' => 'Hanya user biasa yang bisa melakukan booking. Silakan login dengan akun user.']);
        }

        $schedule = Schedule::findOrFail($request->schedule_id);
        
        // Auto-expire sebelum cek ketersediaan kursi
        $this->autoExpireTransactions();
        
        // Cek kursi yang sudah dibooking (paid atau pending yang belum expired)
        $existing = Booking::where('film_id', $id)
            ->where(function($query) use ($request, $schedule) {
                $query->where('schedule_id', $request->schedule_id)
                      ->orWhere(function($q) use ($schedule) {
                          $q->where('showtime', $schedule->show_time)
                            ->whereDate('booking_date', $schedule->show_date);
                      });
            })
            ->whereIn('seat_number', $request->seats)
            ->where(function($query) {
                $query->where('status', 'paid')
                      ->orWhere(function($q) {
                          $q->where('status', 'pending')
                            ->whereHas('transaction', function($qt) {
                                $qt->where('status', 'pending')
                                   ->where(function($subq) {
                                       $subq->whereNull('expired_at')
                                            ->orWhere('expired_at', '>', now());
                                   });
                            });
                      });
            })
            ->pluck('seat_number')->toArray();

        if ($existing) {
            return back()->withErrors(['seats' => 'Kursi ' . implode(', ', $existing) . ' sudah dibooking!']);
        }

        // Cek kursi yang disabled oleh admin
        $disabledSeats = Seat::where('studio_id', $schedule->studio_id)
            ->where('is_available', false)
            ->whereIn('seat_number', $request->seats)
            ->pluck('seat_number')->toArray();

        if ($disabledSeats) {
            return back()->withErrors(['seats' => 'Kursi ' . implode(', ', $disabledSeats) . ' tidak tersedia!']);
        }

        DB::beginTransaction();
        try {
            $bookingCode = 'BK' . date('Ymd') . rand(10000, 99999);
            $total = count($request->seats) * 50000;
            
            // Set waktu expired (15 menit dari sekarang)
            $expiryTime = now()->addMinutes(15);

            foreach ($request->seats as $seat) {
                Booking::create([
                    'booking_code' => $bookingCode,
                    'user_id' => $user->id,
                    'film_id' => $id,
                    'schedule_id' => $request->schedule_id,
                    'seat_number' => $seat,
                    'showtime' => $schedule->show_time,
                    'booking_date' => $schedule->show_date,
                    'customer_name' => $user->name,
                    'customer_email' => $user->email,
                    'customer_phone' => $user->phone,
                    'price' => 50000,
                    'status' => 'pending',
                ]);
            }

            $transaction = Transaction::create([
                'transaction_code' => Transaction::generateTransactionCode(),
                'booking_code' => $bookingCode,
                'user_id' => $user->id,
                'film_id' => $id,
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'seats' => $request->seats,
                'showtime' => $schedule->show_time,
                'ticket_count' => count($request->seats),
                'total_price' => $total,
                'total_amount' => $total,
                'payment_method' => 'qris',
                'status' => 'pending',
                'expired_at' => $expiryTime,
            ]);

            DB::commit();

            return redirect()->route('transaction.payment', $transaction->booking_code)
                ->with('success', 'Booking berhasil! Silakan selesaikan pembayaran dalam 15 menit.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal booking: ' . $e->getMessage()]);
        }
    }

    // ============================
    // 💳 PEMBAYARAN (MIDTRANS)
    // ============================
    public function transactionPayment($bookingCode)
    {
        $transaction = Transaction::where('booking_code', $bookingCode)->firstOrFail();
        $user = Auth::user();

        if ($transaction->status === 'paid') {
            return redirect()->route('transaction.index')->with('info', 'Transaksi sudah dibayar.');
        }

        // Cek apakah sudah expired dari Transaction
        if ($transaction->status === 'pending' && $transaction->expired_at && $transaction->expired_at <= now()) {
            Booking::where('booking_code', $bookingCode)->update(['status' => 'expired']);
            $transaction->update(['status' => 'expired']);
            
            return redirect()->route('transaction.index')
                ->withErrors('Waktu pembayaran telah habis. Booking sudah kadaluarsa. Silakan booking ulang.');
        }

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $transaction->transaction_code,
                'gross_amount' => $transaction->total_amount,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);
        $transaction->update(['snap_token' => $snapToken]);

        return view('payment.process', compact('transaction', 'snapToken'));
    }

    // ============================
    // 🔄 CALLBACK MIDTRANS
    // ============================
    public function updateTransactionStatus(Request $request)
    {
        $transactionCode = $request->input('order_id');
        $status = $request->input('transaction_status');

        $transaction = Transaction::where('transaction_code', $transactionCode)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        switch ($status) {
            case 'settlement':
            case 'capture':
                $transaction->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);
                Booking::where('booking_code', $transaction->booking_code)
                    ->update(['status' => 'paid']);
                break;
            case 'expire':
                $transaction->update(['status' => 'expired']);
                Booking::where('booking_code', $transaction->booking_code)
                    ->update(['status' => 'expired']);
                break;
            case 'cancel':
                $transaction->update(['status' => 'cancelled']);
                Booking::where('booking_code', $transaction->booking_code)
                    ->update(['status' => 'cancelled']);
                break;
            case 'deny':
            case 'failure':
                $transaction->update(['status' => 'failed']);
                Booking::where('booking_code', $transaction->booking_code)
                    ->update(['status' => 'failed']);
                break;
        }

        return response()->json(['message' => 'OK']);
    }

    // ============================
    // 🕒 AUTO EXPIRE TRANSACTIONS (Helper Method)
    // ============================
    private function autoExpireTransactions()
    {
        $expiredTransactions = Transaction::where('status', 'pending')
            ->where('expired_at', '<=', now())
            ->get();
        
        foreach ($expiredTransactions as $transaction) {
            $transaction->update(['status' => 'expired']);
            Booking::where('booking_code', $transaction->booking_code)
                ->update(['status' => 'expired']);
        }
    }

    // ============================
    // 📜 RIWAYAT TRANSAKSI
    // ============================
    public function transactionIndex()
    {
        // Auto-expire sebelum tampilkan list
        $this->autoExpireTransactions();
        
        $transactions = Transaction::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.transaction_show', compact('transactions'));
    }

    // ============================
    // 📄 DETAIL TRANSAKSI
    // ============================
    public function transactionShow($transactionCode)
    {
        $transaction = Transaction::where('transaction_code', $transactionCode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $bookings = Booking::where('booking_code', $transaction->booking_code)->get();
        $film = Film::find($bookings->first()->film_id ?? null);

        return view('user.detailtransaksi', compact('transaction', 'bookings', 'film'));
    }

    // ============================
    // 🎫 DETAIL TIKET USER
    // ============================
    public function ticketDetail($bookingCode)
    {
        $bookings = Booking::where('booking_code', $bookingCode)
            ->where('user_id', Auth::id())
            ->get();

        if ($bookings->isEmpty()) {
            abort(404, 'Tiket tidak ditemukan.');
        }

        $film = Film::find($bookings->first()->film_id);
        $transaction = Transaction::where('booking_code', $bookingCode)->first();

        return view('user.ticket_detail', compact('bookings', 'film', 'transaction'));
    }

    // ============================
    // 🎫 HALAMAN TIKET SAYA (MY TICKETS)
    // ============================
    public function myTickets()
    {
        $user = Auth::user();
        
        $bookingsRaw = Booking::where('user_id', $user->id)
            ->whereIn('status', ['paid', 'pending'])
            ->with('film')
            ->orderBy('booking_date', 'desc')
            ->orderBy('showtime', 'desc')
            ->get();

        $groupedBookings = $bookingsRaw->groupBy('booking_code');

        $bookings = [];
        foreach ($groupedBookings as $bookingCode => $bookingGroup) {
            $first = $bookingGroup->first();
            $bookings[] = [
                'booking_code' => $bookingCode,
                'film_title' => $first->film->title ?? 'Unknown',
                'poster' => $first->film->poster ?? null,
                'booking_date' => $first->booking_date,
                'showtime' => $first->showtime,
                'seats' => $bookingGroup->pluck('seat_number')->toArray(),
                'total_price' => $bookingGroup->sum('price'),
                'status' => $first->status,
            ];
        }

        return view('user.my_tickets', compact('bookings'));
    }

    // ============================
    // ⚙️ HALAMAN EDIT PROFILE
    // ============================
    public function editProfile()
    {
        return view('user.edit_profile');
    }

    // ============================
    // 💾 UPDATE PROFILE
    // ============================
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20|unique:users,phone,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return back()->with('success', '✅ Profile berhasil diperbarui!');
    }

    // ============================
    // 🔒 UPDATE PASSWORD
    // ============================
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', '✅ Password berhasil diubah!');
    }
}

