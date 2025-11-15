<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Film;
use App\Models\Booking;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class KasirController extends Controller
{
     // Di KasirController
// Tambahkan method ini di KasirController
public function showLogin()
{
    // Jika sudah login, redirect ke dashboard
    if (Auth::check()) {
        return redirect()->route('kasir.dashboard');
    }
    
    return view('kasir.login');
}

public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        
        // Cek role user, jika bukan kasir, logout
        if (Auth::user()->role !== 'kasir') {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Anda tidak memiliki akses kasir.',
            ]);
        }
        
        return redirect()->route('kasir.dashboard')
            ->with('success', 'Login berhasil! Selamat datang di dashboard kasir.');
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ])->onlyInput('email');
}
    public function dashboard()
    {
        $today = Carbon::today();

        $todayTransactions = Transaction::whereDate('created_at', $today)->count();
        $todayTickets = Transaction::whereDate('created_at', $today)
            ->where('status', 'paid')
            ->sum('total_amount') / 50000; // Assuming each ticket is 50000
        $todayRevenue = Transaction::whereDate('created_at', $today)
            ->where('status', 'paid')
            ->sum('total_amount');
        $pendingPayments = Transaction::whereDate('created_at', $today)
            ->where('status', 'pending')
            ->count();

        $recentTransactions = Transaction::with(['film', 'user'])
            ->whereDate('created_at', $today)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('kasir.dashboard', compact(
            'todayTransactions',
            'todayTickets',
            'todayRevenue',
            'pendingPayments',
            'recentTransactions'
        ));
    }
    
    // Pesan Tiket - Daftar Film
    public function pesanTiket()
    {
        $films = Film::where('status', 'now_playing')->get();
        return view('kasir.pesan-tiket', compact('films'));
    }
    
    // Detail Film & Pilih Jadwal
    public function filmDetail($id)
    {
        $film = Film::findOrFail($id);
        $showtimes = ['10:00', '13:00', '16:00', '19:00', '21:30'];
        
        return view('kasir.film-detail', compact('film', 'showtimes'));
    }
    
    // Pilih Kursi
    public function pilihKursi(Request $request, $id)
    {
        $film = Film::findOrFail($id);
        $showtime = $request->showtime;
        
        if (!$showtime) {
            return redirect()->route('kasir.film-detail', $id)
                ->with('error', 'Silakan pilih jadwal tayang terlebih dahulu');
        }
        
        // Ambil kursi yang sudah dipesan untuk jadwal ini
        $bookedSeats = Booking::where('film_id', $id)
            ->where('showtime', $showtime)
            ->whereDate('created_at', Carbon::today())
            ->where('status', '!=', 'failed')
            ->pluck('seat_number')
            ->toArray();
        
        return view('kasir.pilih-kursi', compact('film', 'showtime', 'bookedSeats'));
    }
    
    // Process Booking
    public function processBooking(Request $request, $id)
    {
        $request->validate([
            'seats' => 'required|array|min:1',
            'showtime' => 'required',
            'payment_method' => 'required|in:cash,qris',
            'customer_name' => 'required|string|max:255'
        ]);
        
        // Cek login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Session expired, silakan login kembali');
        }
        
        // Validasi film exists
        $film = Film::find($id);
        
        if (!$film) {
            return redirect()->route('kasir.pesan-tiket')
                ->with('error', 'Film tidak ditemukan!');
        }
        
        $pricePerSeat = 50000;
        $totalPrice = count($request->seats) * $pricePerSeat;
        $bookingCode = 'TIX-' . strtoupper(Str::random(8));
        
        // Cek apakah kursi masih tersedia
        $existingBookings = Booking::where('film_id', $id)
            ->where('showtime', $request->showtime)
            ->whereDate('created_at', Carbon::today())
            ->where('status', '!=', 'failed')
            ->pluck('seat_number')
            ->toArray();
        
        $conflictSeats = array_intersect($request->seats, $existingBookings);
        
        if (!empty($conflictSeats)) {
            return redirect()->back()
                ->with('error', 'Kursi ' . implode(', ', $conflictSeats) . ' sudah dipesan!');
        }
        
        // Buat booking dan transaction
        try {
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'film_id' => $film->id,
                'booking_code' => $bookingCode,
                'seats' => $request->seats,
                'showtime' => $request->showtime,
                'booking_date' => Carbon::today(),
                'total_price' => $totalPrice,
                'payment_method' => $request->payment_method,
                'status' => 'success'
            ]);

            $transaction = Transaction::create([
                'booking_code' => $bookingCode,
                'user_id' => Auth::id(),
                'film_id' => $film->id,
                'transaction_code' => $bookingCode,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email ?? 'walkin@bioskop.com',
                'seats' => $request->seats,
                'showtime' => $request->showtime,
                'total_amount' => $totalPrice,
                'payment_method' => $request->payment_method,
                'status' => $request->payment_method === 'qris' ? 'pending' : 'paid'
            ]);

            // Jika QRIS, redirect ke halaman pembayaran Midtrans
            if ($request->payment_method === 'qris') {
                return redirect()->route('kasir.payment', $transaction->id)
                    ->with('success', 'Booking berhasil! Silakan selesaikan pembayaran QRIS.');
            }

            return redirect()->route('kasir.tiket')
                ->with('success', 'Booking berhasil! Silakan cetak tiket.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    // Riwayat Transaksi
    public function riwayat(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::today()->subDays(7)->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::today()->format('Y-m-d');
        
        $transactions = Transaction::with(['film', 'user'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('kasir.riwayat', compact('transactions', 'startDate', 'endDate'));
    }
    
    // Detail Transaksi
    public function detailTransaksi($id)
    {
        $transaction = Transaction::with(['film', 'user'])->findOrFail($id);
        return view('kasir.detail-transaksi', compact('transaction'));
    }
    
    // Laporan Keuangan
    public function laporan(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::today()->subDays(7)->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::today()->format('Y-m-d');
        
        $totalTransactions = Transaction::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('status', 'paid')
            ->count();

        $totalRevenue = Transaction::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('status', 'paid')
            ->sum('total_amount');

        $dailyRevenue = Transaction::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('status', 'paid')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as transaction_count, SUM(total_amount) as revenue')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();
        
        return view('kasir.laporan', compact(
            'startDate',
            'endDate',
            'totalTransactions',
            'totalRevenue',
            'dailyRevenue'
        ));
    }
    
    // Tiket Terjual Hari Ini
    public function tiket()
    {
        $tickets = Transaction::with(['film', 'user'])
            ->whereDate('created_at', Carbon::today())
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('kasir.tiket', compact('tickets'));
    }
    
    // Cetak Tiket
    public function cetakTiket($id)
    {
        $transaction = Transaction::with(['film', 'user'])->findOrFail($id);
        return view('kasir.cetak-tiket', compact('transaction'));
    }

    // Pembayaran QRIS Kasir
    public function payment($transactionId)
    {
        $transaction = Transaction::with(['film', 'user'])->findOrFail($transactionId);

        // Pastikan transaction milik kasir yang login dan status pending
        if ($transaction->user_id !== Auth::id() || $transaction->status !== 'pending') {
            return redirect()->route('kasir.dashboard')->with('error', 'Transaksi tidak ditemukan atau sudah diproses.');
        }

        // Setup Midtrans
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
                'first_name' => $transaction->customer_name,
                'email' => $transaction->customer_email,
                'phone' => '081234567890', // Default phone untuk walk-in customer
            ],
        ];

        $snapToken = Snap::getSnapToken($params);
        $transaction->update(['snap_token' => $snapToken]);

        return view('kasir.payment', compact('transaction', 'snapToken'));
    }

    // Callback Update Status Pembayaran Kasir
    public function updatePaymentStatus(Request $request)
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
                Booking::where('booking_code', $transaction->transaction_code)
                    ->update(['status' => 'paid']);
                break;
            case 'expire':
                $transaction->update(['status' => 'expired']);
                Booking::where('booking_code', $transaction->transaction_code)
                    ->update(['status' => 'expired']);
                break;
            case 'cancel':
                $transaction->update(['status' => 'cancelled']);
                Booking::where('booking_code', $transaction->transaction_code)
                    ->update(['status' => 'cancelled']);
                break;
            case 'deny':
            case 'failure':
                $transaction->update(['status' => 'failed']);
                Booking::where('booking_code', $transaction->transaction_code)
                    ->update(['status' => 'failed']);
                break;
        }

        return response()->json(['message' => 'OK']);
    }

    // Logout Kasir
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Logout berhasil!');
    }
}
