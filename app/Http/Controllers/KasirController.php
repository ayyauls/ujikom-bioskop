<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Film;
use App\Models\Booking;
use App\Models\Transaction;
use App\Models\Seat;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    // Dashboard Kasir
    public function dashboard()
    {
        $today = Carbon::today();
        
        $todayTransactions = Transaction::whereDate('created_at', $today)->count();
        $todayTickets = Transaction::whereDate('created_at', $today)
            ->where('status', 'paid')
            ->sum('ticket_count');
        $todayRevenue = Transaction::whereDate('created_at', $today)
            ->where('status', 'paid')
            ->sum(DB::raw('COALESCE(total_price, total_amount, 0)'));
        $pendingPayments = Transaction::whereDate('created_at', $today)
            ->where('status', 'pending')
            ->count();
        
        $recentTransactions = Transaction::with(['film', 'user'])
            ->where('status', 'paid')
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
        
        // Ambil schedule untuk hari ini
        $schedules = \App\Models\Schedule::where('film_id', $id)
            ->where('show_date', \Carbon\Carbon::today()->format('Y-m-d'))
            ->orderBy('show_time')
            ->get();
        
        return view('kasir.film-detail', compact('film', 'schedules'));
    }
    
    // Pilih Kursi
    public function pilihKursi(Request $request, $id)
    {
        $film = Film::findOrFail($id);
        $scheduleId = $request->schedule_id;
        
        if (!$scheduleId) {
            return redirect()->route('kasir.film-detail', $id)
                ->with('error', 'Silakan pilih jadwal tayang terlebih dahulu');
        }
        
        $schedule = \App\Models\Schedule::findOrFail($scheduleId);
        
        // Ambil kursi yang sudah dipesan untuk jadwal ini (hanya yang paid dan pending yang belum expired)
        $bookedSeats = Booking::where('film_id', $id)
            ->where(function($query) use ($scheduleId, $schedule) {
                $query->where('schedule_id', $scheduleId)
                      ->orWhere(function($q) use ($schedule) {
                          $q->where('showtime', $schedule->show_time)
                            ->whereDate('booking_date', $schedule->show_date);
                      });
            })
            ->where(function($query) {
                // Hanya ambil yang paid ATAU pending yang belum expired
                $query->where('status', 'paid')
                      ->orWhere(function($q) {
                          $q->where('status', 'pending')
                            ->where('expires_at', '>', now());
                      });
            })
            ->pluck('seat_number')
            ->toArray();
        
        // Ambil kursi yang disabled oleh admin
        $disabledSeats = Seat::where('studio_id', $schedule->studio_id)
            ->where('is_available', false)
            ->pluck('seat_number')
            ->toArray();
        
        return view('kasir.pilih-kursi', compact('film', 'schedule', 'bookedSeats', 'disabledSeats'));
    }
    
    // Process Booking
    public function processBooking(Request $request, $id)
    {
        $request->validate([
            'seats' => 'required|array|min:1',
            'schedule_id' => 'required',
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
        
        $schedule = \App\Models\Schedule::findOrFail($request->schedule_id);
        
        // Cek apakah kursi masih tersedia
        $existingBookings = Booking::where('film_id', $id)
            ->where(function($query) use ($request, $schedule) {
                $query->where('schedule_id', $request->schedule_id)
                      ->orWhere(function($q) use ($schedule) {
                          $q->where('showtime', $schedule->show_time)
                            ->whereDate('booking_date', $schedule->show_date);
                      });
            })
            ->where(function($query) {
                // Hanya cek yang paid ATAU pending yang belum expired
                $query->where('status', 'paid')
                      ->orWhere(function($q) {
                          $q->where('status', 'pending')
                            ->where('expires_at', '>', now());
                      });
            })
            ->pluck('seat_number')
            ->toArray();
        
        $conflictSeats = array_intersect($request->seats, $existingBookings);
        
        if (!empty($conflictSeats)) {
            return redirect()->back()
                ->with('error', 'Kursi ' . implode(', ', $conflictSeats) . ' sudah dipesan!');
        }
        
        // Cek kursi yang disabled oleh admin
        $disabledSeats = Seat::where('studio_id', $schedule->studio_id)
            ->where('is_available', false)
            ->whereIn('seat_number', $request->seats)
            ->pluck('seat_number')
            ->toArray();
        
        if (!empty($disabledSeats)) {
            return redirect()->back()
                ->with('error', 'Kursi ' . implode(', ', $disabledSeats) . ' tidak tersedia!');
        }
        
        // Buat booking dan transaction
        try {
            if ($request->payment_method === 'cash') {
                // Cash payment - langsung paid
                foreach ($request->seats as $seat) {
                    Booking::create([
                        'user_id' => null, // Kasir booking, bukan user
                        'film_id' => $film->id,
                        'schedule_id' => $request->schedule_id,
                        'booking_code' => $bookingCode,
                        'seat_number' => $seat,
                        'booking_date' => $schedule->show_date,
                        'showtime' => $schedule->show_time->format('H:i:s'),
                        'customer_name' => $request->customer_name,
                        'customer_email' => $request->customer_email ?? 'walkin@bioskop.com',
                        'customer_phone' => $request->customer_phone ?? '000000000',
                        'price' => $pricePerSeat,
                        'status' => 'paid'
                    ]);
                }
                
                Transaction::create([
                    'booking_code' => $bookingCode,
                    'user_id' => null, // Kasir transaction
                    'film_id' => $film->id,
                    'transaction_code' => $bookingCode,
                    'customer_name' => $request->customer_name,
                    'customer_email' => $request->customer_email ?? 'walkin@bioskop.com',
                    'seats' => $request->seats,
                    'showtime' => $schedule->show_time->format('H:i:s'),
                    'ticket_count' => count($request->seats),
                    'total_price' => $totalPrice,
                    'total_amount' => $totalPrice,
                    'payment_method' => $request->payment_method,
                    'status' => 'paid'
                ]);
                
                return redirect()->route('kasir.tiket')
                    ->with('success', 'Pembayaran cash berhasil! Silakan cetak tiket.');
            } else {
                // QRIS payment - buat booking pending dulu
                $expiresAt = now()->addMinutes(2); // Set expires_at untuk QRIS payment juga
                foreach ($request->seats as $seat) {
                    Booking::create([
                        'user_id' => null, // Kasir booking, bukan user
                        'film_id' => $film->id,
                        'schedule_id' => $request->schedule_id,
                        'booking_code' => $bookingCode,
                        'seat_number' => $seat,
                        'booking_date' => $schedule->show_date,
                        'showtime' => $schedule->show_time->format('H:i:s'),
                        'customer_name' => $request->customer_name,
                        'customer_email' => $request->customer_email ?? 'walkin@bioskop.com',
                        'customer_phone' => $request->customer_phone ?? '000000000',
                        'price' => $pricePerSeat,
                        'status' => 'pending',
                        'expires_at' => $expiresAt // ← TAMBAH INI
                    ]);
                }
                
                $transaction = Transaction::create([
                    'booking_code' => $bookingCode,
                    'user_id' => null, // Kasir transaction
                    'film_id' => $film->id,
                    'transaction_code' => $bookingCode,
                    'customer_name' => $request->customer_name,
                    'customer_email' => $request->customer_email ?? 'walkin@bioskop.com',
                    'seats' => $request->seats,
                    'showtime' => $schedule->show_time->format('H:i:s'),
                    'ticket_count' => count($request->seats),
                    'total_price' => $totalPrice,
                    'total_amount' => $totalPrice,
                    'payment_method' => $request->payment_method,
                    'status' => 'pending'
                ]);
                
                return redirect()->route('kasir.payment', ['transactionId' => $transaction->id]);
            }
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    // Riwayat Transaksi (semua transaksi)
    public function riwayat(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::today()->subDays(7)->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::today()->format('Y-m-d');
        
        $transactions = Transaction::with(['film', 'user'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Lengkapi data dari bookings untuk transaksi user
        foreach ($transactions as $transaction) {
            if ($transaction->user_id && !$transaction->customer_name) {
                $bookings = Booking::where('booking_code', $transaction->booking_code)->get();
                if ($bookings->count() > 0) {
                    $transaction->customer_name = $bookings->first()->customer_name;
                    $transaction->customer_email = $bookings->first()->customer_email;
                    $transaction->total_price = $bookings->sum('price');
                    $transaction->showtime = $bookings->first()->showtime;
                }
            }
        }
        
        return view('kasir.riwayat', compact('transactions', 'startDate', 'endDate'));
    }
    
    // Detail Transaksi
    public function detailTransaksi($id)
    {
        $transaction = Transaction::with(['film'])->findOrFail($id);
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
            ->sum(DB::raw('COALESCE(total_price, total_amount, 0)'));
        
        $dailyRevenue = Transaction::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('status', 'paid')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as transaction_count, SUM(COALESCE(total_price, total_amount, 0)) as revenue')
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
    
    // Tiket Terjual Hari Ini (semua transaksi)
    public function tiket()
    {
        $tickets = Transaction::with(['film', 'user'])
            ->where('status', 'paid')
            ->whereDate('created_at', Carbon::today())
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Lengkapi data dari bookings untuk transaksi user
        foreach ($tickets as $ticket) {
            if ($ticket->user_id && !$ticket->seats) {
                // Ambil data dari bookings
                $bookings = Booking::where('booking_code', $ticket->booking_code)->get();
                if ($bookings->count() > 0) {
                    $ticket->seats = $bookings->pluck('seat_number')->toArray();
                    $ticket->showtime = $bookings->first()->showtime;
                    $ticket->customer_name = $bookings->first()->customer_name;
                    $ticket->customer_email = $bookings->first()->customer_email;
                    $ticket->total_price = $bookings->sum('price');
                    $ticket->ticket_count = $bookings->count();
                }
            }
            
            // Pastikan seats adalah array
            if (!is_array($ticket->seats)) {
                $ticket->seats = json_decode($ticket->seats, true) ?? [];
            }
        }
        
        return view('kasir.tiket', compact('tickets'));
    }
    
    // Payment Gateway untuk QRIS
    public function payment($transactionId)
    {
        try {
            $transaction = Transaction::with('film')->findOrFail($transactionId);
            
            // Pastikan seats adalah array
            if (!is_array($transaction->seats)) {
                $transaction->seats = json_decode($transaction->seats, true) ?? [];
            }
            
            // Setup Midtrans
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production', false);
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;
            
            $params = [
                'transaction_details' => [
                    'order_id' => $transaction->transaction_code,
                    'gross_amount' => (int) $transaction->total_amount,
                ],
                'customer_details' => [
                    'first_name' => $transaction->customer_name,
                    'email' => $transaction->customer_email,
                ],
                'item_details' => [[
                    'id' => 'ticket-' . $transaction->film_id,
                    'price' => (int) $transaction->total_amount,
                    'quantity' => 1,
                    'name' => 'Tiket ' . ($transaction->film->title ?? 'Film')
                ]],
                'enabled_payments' => ['qris', 'gopay', 'shopeepay']
            ];
            
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $transaction->update(['snap_token' => $snapToken]);
            
            return view('kasir.payment', compact('transaction', 'snapToken'));
        } catch (\Exception $e) {
            return redirect()->route('kasir.pesan-tiket')->with('error', 'Gagal membuat payment: ' . $e->getMessage());
        }
    }
    
    // Payment Success
    public function paymentSuccess($transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);
        
        $transaction->update(['status' => 'paid']);
        Booking::where('booking_code', $transaction->booking_code)->update(['status' => 'paid']);
        
        return redirect()->route('kasir.tiket')->with('success', 'Pembayaran berhasil!');
    }
    
    // Update Payment Status (Midtrans Callback)
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
                $transaction->update(['status' => 'paid']);
                Booking::where('booking_code', $transaction->booking_code)->update(['status' => 'paid']);
                break;
            case 'expire':
                $transaction->update(['status' => 'expired']);
                Booking::where('booking_code', $transaction->booking_code)->update(['status' => 'expired']);
                break;
            case 'cancel':
                $transaction->update(['status' => 'cancelled']);
                Booking::where('booking_code', $transaction->booking_code)->update(['status' => 'cancelled']);
                break;
            case 'deny':
            case 'failure':
                $transaction->update(['status' => 'failed']);
                Booking::where('booking_code', $transaction->booking_code)->update(['status' => 'failed']);
                break;
        }
        
        return response()->json(['message' => 'OK']);
    }
    
    // Mark as Paid (Manual)
    public function markAsPaid($transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);
        
        $transaction->update(['status' => 'paid']);
        Booking::where('booking_code', $transaction->booking_code)->update(['status' => 'paid']);
        
        return redirect()->back()->with('success', 'Transaksi berhasil ditandai sebagai sudah dibayar!');
    }
    
    // Mark as Failed (untuk pembersihan)
    public function markAsFailed($transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);
        
        $transaction->update(['status' => 'failed']);
        Booking::where('booking_code', $transaction->booking_code)->update(['status' => 'failed']);
        
        return redirect()->back()->with('success', 'Transaksi ditandai sebagai gagal!');
    }

    
    // Cetak Tiket
    public function cetakTiket($id)
    {
        $transaction = Transaction::with(['film', 'user'])->findOrFail($id);
        return view('kasir.cetak-tiket', compact('transaction'));
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