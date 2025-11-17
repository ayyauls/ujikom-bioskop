<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\AdminController;

// ======================
// 🏠 PUBLIC ROUTES
// ======================
Route::get('/', [UserController::class, 'index'])->name('home');
Route::get('/film/{id}', [UserController::class, 'detail'])->name('film.detail');

// ======================
// 👤 AUTH ROUTES (Untuk Customer)
// ======================
Route::get('/login', [UserController::class, 'showLogin'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::get('/register', [UserController::class, 'showRegister'])->name('register');
Route::post('/register', [UserController::class, 'register']);

// ======================
// 🎟️ CUSTOMER AREA (User yang login)
// ======================
Route::middleware('auth')->group(function () {

    // Booking
    Route::get('/booking/select-seat/{id}', [UserController::class, 'selectSeat'])->name('booking.select_seat');
    Route::post('/film/{id}/book', [UserController::class, 'bookSeat'])->name('booking.book');

    // Transaksi
    Route::get('/transactions', [UserController::class, 'transactionIndex'])->name('transaction.index');
    Route::get('/transaction/{transactionCode}', [UserController::class, 'transactionShow'])->name('transaction.show');
    Route::get('/transaction/payment/{bookingCode}', [UserController::class, 'transactionPayment'])->name('transaction.payment');
    Route::post('/transaction/update-status', [UserController::class, 'updateTransactionStatus'])->name('transaction.update-status');

    // Tiket
    Route::get('/ticket/{bookingCode}', [UserController::class, 'ticketDetail'])->name('ticket.detail');
    Route::get('/my-tickets', [UserController::class, 'myTickets'])->name('my.tickets');

    // Profile
    Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/update-password', [UserController::class, 'updatePassword'])->name('profile.update.password');
});

// ======================
// 💰 KASIR AREA (Menggunakan auth biasa + pengecekan role di controller)
// ======================
Route::prefix('kasir')->name('kasir.')->group(function () {
    // Login Kasir (Public)
    Route::get('/login', [KasirController::class, 'showLogin'])->name('login');
    Route::post('/login', [KasirController::class, 'login']);

    // Protected Kasir Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [KasirController::class, 'dashboard'])->name('dashboard');
        Route::get('/pesan-tiket', [KasirController::class, 'pesanTiket'])->name('pesan-tiket');
        Route::get('/film/{id}', [KasirController::class, 'filmDetail'])->name('film-detail');
        Route::get('/pilih-kursi/{id}', [KasirController::class, 'pilihKursi'])->name('pilih-kursi');
        Route::post('/process-booking/{id}', [KasirController::class, 'processBooking'])->name('process-booking');
        Route::get('/payment/{transactionId}', [KasirController::class, 'payment'])->name('payment');
        Route::get('/payment-success/{transactionId}', [KasirController::class, 'paymentSuccess'])->name('payment.success');
        Route::post('/mark-paid/{transactionId}', [KasirController::class, 'markAsPaid'])->name('mark-paid');
        Route::post('/mark-failed/{transactionId}', [KasirController::class, 'markAsFailed'])->name('mark-failed');
        Route::post('/update-payment-status', [KasirController::class, 'updatePaymentStatus'])->name('update-payment-status');
        Route::get('/riwayat', [KasirController::class, 'riwayat'])->name('riwayat');
        Route::get('/detail-transaksi/{id}', [KasirController::class, 'detailTransaksi'])->name('detail-transaksi');
        Route::get('/laporan', [KasirController::class, 'laporan'])->name('laporan');
        Route::get('/tiket', [KasirController::class, 'tiket'])->name('tiket');
        Route::get('/cetak-tiket/{id}', [KasirController::class, 'cetakTiket'])->name('cetak-tiket');
        Route::post('/logout', [KasirController::class, 'logout'])->name('logout');
    });
});

// ======================
// 👨‍💼 OWNER AREA (Menggunakan auth biasa + pengecekan role di controller)
// ======================
Route::prefix('owner')->name('owner.')->group(function () {
    // Login Owner (Public) - Opsional, bisa pakai login biasa
    Route::get('/login', [OwnerController::class, 'showLogin'])->name('login');
    Route::post('/login', [OwnerController::class, 'login']);

    // Protected Owner Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [OwnerController::class, 'index'])->name('dashboard');
        Route::get('/history', [OwnerController::class, 'history'])->name('history');
        Route::post('/report/filter', [OwnerController::class, 'filterReport'])->name('report.filter');
        Route::post('/logout', [OwnerController::class, 'logout'])->name('logout');
    });
});

// ======================
// 👑 ADMIN AREA
// ======================
Route::prefix('admin')->name('admin.')->group(function () {
    // Login Admin (Public)
    Route::get('/login', [AdminController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminController::class, 'login']);

    // Protected Admin Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // User Management
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');

        // Film Management
        Route::get('/films', [AdminController::class, 'films'])->name('films');
        Route::get('/films/create', [AdminController::class, 'createFilm'])->name('films.create');
        Route::post('/films', [AdminController::class, 'storeFilm'])->name('films.store');
        Route::get('/films/{id}/edit', [AdminController::class, 'editFilm'])->name('films.edit');
        Route::put('/films/{id}', [AdminController::class, 'updateFilm'])->name('films.update');
        Route::delete('/films/{id}', [AdminController::class, 'deleteFilm'])->name('films.delete');

        // Price Management
        Route::get('/prices', [AdminController::class, 'prices'])->name('prices');
        Route::post('/prices/update', [AdminController::class, 'updatePrices'])->name('prices.update');

        // Seat Management
        Route::get('/seats', [AdminController::class, 'seats'])->name('seats');
        Route::post('/seats/update-availability', [AdminController::class, 'updateSeatAvailability'])->name('seats.update-availability');

        // Studio Management
        Route::get('/studios', [AdminController::class, 'studios'])->name('studios');
        Route::put('/studios/{id}', [AdminController::class, 'updateStudio'])->name('studios.update');
        
        // Schedule Management
        Route::get('/schedules', [AdminController::class, 'schedules'])->name('schedules');
        Route::get('/schedules/create', [AdminController::class, 'createSchedule'])->name('schedules.create');
        Route::post('/schedules', [AdminController::class, 'storeSchedule'])->name('schedules.store');
        Route::delete('/schedules/{id}', [AdminController::class, 'deleteSchedule'])->name('schedules.delete');

        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    });
});
