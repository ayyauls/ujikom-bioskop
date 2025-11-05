<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// ======================
// 🏠 PUBLIC ROUTES
// ======================
Route::get('/', [UserController::class, 'index'])->name('home');
Route::get('/film/{id}', [UserController::class, 'detail'])->name('film.detail');

// ======================
// 👤 AUTH
// ======================
Route::get('/register', [UserController::class, 'showRegister'])->name('register');
Route::post('/register', [UserController::class, 'register']);
Route::get('/login', [UserController::class, 'showLogin'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// ======================
// 🎟️ BOOKING & TRANSAKSI (Hanya untuk user login)
// ======================
Route::middleware('auth')->group(function () {

    // Booking kursi
    Route::get('/booking/select-seat/{id}', [UserController::class, 'selectSeat'])->name('booking.select_seat');
    Route::post('/film/{id}/book', [UserController::class, 'bookSeat'])->name('booking.book');

    // Riwayat transaksi
    Route::get('/transactions', [UserController::class, 'transactionIndex'])->name('transaction.index');

    // Detail transaksi (per kode transaksi)
    Route::get('/transaction/{transactionCode}', [UserController::class, 'transactionShow'])->name('transaction.show');

    // Halaman pembayaran Midtrans
    Route::get('/transaction/payment/{bookingCode}', [UserController::class, 'transactionPayment'])->name('transaction.payment');

    // Update status pembayaran (callback)
    Route::post('/transaction/update-status', [UserController::class, 'updateTransactionStatus'])->name('transaction.update-status');

    // ✅ DETAIL TIKET USER
    Route::get('/ticket/{bookingCode}', [UserController::class, 'ticketDetail'])->name('ticket.detail');
});
