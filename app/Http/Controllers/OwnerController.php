<?php 

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Film;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OwnerController extends Controller
{
    public function index()
    {
        try {
            $totalRevenue = Transaction::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_amount');

            $totalTransactions = Transaction::count();
            $averageTransaction = Transaction::avg('total_amount') ?? 0;

            $last7DaysLabels = [];
            $last7DaysData = [];

            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $day = $date->format('d M');
                $last7DaysLabels[] = $day;

                $revenue = Transaction::whereDate('created_at', $date->format('Y-m-d'))
                    ->sum('total_amount');
                $last7DaysData[] = $revenue;
            }

            $monthsLabels = [];
            $monthsData = [];

            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $month = $date->format('M Y');
                $monthsLabels[] = $month;

                $revenue = Transaction::whereYear('created_at', $date->format('Y'))
                    ->whereMonth('created_at', $date->format('m'))
                    ->sum('total_amount');
                $monthsData[] = $revenue;
            }

            $popularFilms = Film::select(
                'films.id',
                'films.title',
                DB::raw('COUNT(bookings.id) as tickets_sold'),
                DB::raw('COALESCE(SUM(transactions.total_amount), 0) as total_revenue')
            )
            ->leftJoin('bookings', 'films.id', '=', 'bookings.film_id')
            ->leftJoin('transactions', 'bookings.booking_code', '=', 'transactions.booking_code')
            ->groupBy('films.id', 'films.title')
            ->orderByDesc('tickets_sold')
            ->limit(5)
            ->get();

            $recentTransactions = Transaction::with(['user' => function($query) {
                $query->withDefault(['name' => 'Guest User']);
            }])
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();

            return view('owner.dashboard', compact(
                'totalRevenue',
                'totalTransactions',
                'averageTransaction',
                'last7DaysLabels',
                'last7DaysData',
                'monthsLabels',
                'monthsData',
                'popularFilms',
                'recentTransactions'
            ));

        } catch (\Exception $e) {
            return view('owner.dashboard', [
                'totalRevenue' => 0,
                'totalTransactions' => 0,
                'averageTransaction' => 0,
                'last7DaysLabels' => [],
                'last7DaysData' => [],
                'monthsLabels' => [],
                'monthsData' => [],
                'popularFilms' => collect(),
                'recentTransactions' => collect()
            ]);
        }
    }

    public function filterReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        try {
            $transactions = Transaction::with(['user' => function($query) {
                $query->withDefault(['name' => 'Guest User']);
            }])
            ->whereBetween(DB::raw('DATE(created_at)'), [$request->start_date, $request->end_date])
            ->orderBy('created_at', 'DESC')
            ->get();

            $filteredRevenue = $transactions->sum('total_amount');
            $filteredTransactions = $transactions->count();
            $averageFiltered = $filteredTransactions > 0 ? $filteredRevenue / $filteredTransactions : 0;

            return view('owner.report', compact(
                'transactions',
                'request',
                'filteredRevenue',
                'filteredTransactions',
                'averageFiltered'
            ));

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memfilter data: ' . $e->getMessage());
        }
    }

    public function history()
    {
        try {
            $transactions = Transaction::with(['user' => function($query) {
                $query->withDefault(['name' => 'Guest User']);
            }])
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

            return view('owner.history', compact('transactions'));

        } catch (\Exception $e) {
            $transactions = collect();
            return view('owner.history', compact('transactions'))
                ->with('error', 'Terjadi kesalahan saat memuat data transaksi.');
        }
    }

    public function showTransaction($id)
    {
        try {
            $transaction = Transaction::with(['user' => function($query) {
                $query->withDefault([
                    'name' => 'Guest User',
                    'email' => 'guest@example.com'
                ]);
            }, 'bookings.film'])
            ->findOrFail($id);

            return view('owner.transaction-detail', compact('transaction'));

        } catch (\Exception $e) {
            return redirect()->route('owner.history')
                ->with('error', 'Transaksi tidak ditemukan.');
        }
    }

    public function statistics()
    {
        try {
            $todayRevenue = Transaction::whereDate('created_at', today())->sum('total_amount');
            $todayTransactions = Transaction::whereDate('created_at', today())->count();

            $weekStart = now()->startOfWeek();
            $weekEnd = now()->endOfWeek();
            $weekRevenue = Transaction::whereBetween('created_at', [$weekStart, $weekEnd])->sum('total_amount');
            $weekTransactions = Transaction::whereBetween('created_at', [$weekStart, $weekEnd])->count();

            $monthlyPopularFilms = Film::select(
                'films.id',
                'films.title',
                DB::raw('COUNT(bookings.id) as tickets_sold'),
                DB::raw('COALESCE(SUM(transactions.total_amount), 0) as total_revenue')
            )
            ->leftJoin('bookings', 'films.id', '=', 'bookings.film_id')
            ->leftJoin('transactions', function($join) {
                $join->on('bookings.booking_code', '=', 'transactions.booking_code')
                     ->whereMonth('transactions.created_at', now()->month)
                     ->whereYear('transactions.created_at', now()->year);
            })
            ->groupBy('films.id', 'films.title')
            ->orderByDesc('tickets_sold')
            ->limit(10)
            ->get();

            return view('owner.statistics', compact(
                'todayRevenue',
                'todayTransactions',
                'weekRevenue',
                'weekTransactions',
                'monthlyPopularFilms'
            ));

        } catch (\Exception $e) {
            return redirect()->route('owner.dashboard')
                ->with('error', 'Terjadi kesalahan saat memuat statistik.');
        }
    }

    public function exportReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        try {
            $transactions = Transaction::with(['user' => function($query) {
                $query->withDefault(['name' => 'Guest User']);
            }])
            ->whereBetween(DB::raw('DATE(created_at)'), [$request->start_date, $request->end_date])
            ->orderBy('created_at', 'DESC')
            ->get();

            return redirect()->back()
                ->with('success', 'Fitur export akan segera tersedia.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengekspor data: ' . $e->getMessage());
        }
    }

    // ========== LOGOUT OWNER ==========
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('success', 'Logout berhasil!');
    }
}