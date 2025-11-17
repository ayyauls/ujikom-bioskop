<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Film;
use App\Models\Studio;
use App\Models\Seat;
use App\Models\Price;
use App\Models\Schedule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['showLogin', 'login']);
        $this->middleware(function ($request, $next) {
            // Cek apakah user sudah login dan role-nya admin
            if (Auth::check() && auth::user()->role !== 'admin') {
                return redirect('/')->with('error', 'Akses ditolak');
            }
            return $next($request);
        })->except(['showLogin', 'login']);
    }

    // ===================
    // AUTH METHODS
    // ===================

    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');
        
        // Cek apakah user ada
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'Email tidak ditemukan'])->withInput();
        }
        
        // Cek password
        if (!Hash::check($request->password, $user->password)) {
            return redirect()->back()->withErrors(['email' => 'Password salah'])->withInput();
        }
        
        // Cek role
        if ($user->role !== 'admin') {
            return redirect()->back()->withErrors(['email' => 'Bukan akun admin'])->withInput();
        }

        // Login manual
        Auth::login($user);
        $request->session()->regenerate();
        
        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    public function dashboard()
    {
        $data = [
            'total_users' => User::where('role', 'user')->count(),
            'total_films' => Film::count(),
            'total_studios' => Studio::count(),
            'total_seats' => Seat::count(),
            'recent_users' => User::where('role', 'user')->latest()->take(5)->get(),
            'films' => Film::latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('data'));
    }

    // ===================
    // USER MANAGEMENT
    // ===================

    public function users()
    {
        $users = User::where('role', 'user')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        return redirect()->route('admin.users')->with('success', 'User berhasil ditambahkan');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'required|string|max:20|unique:users,phone,' . $id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('admin.users')->with('success', 'User berhasil diupdate');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User berhasil dihapus');
    }

    // ===================
    // FILM MANAGEMENT
    // ===================

    public function films()
    {
        $films = Film::paginate(10);
        return view('admin.films.index', compact('films'));
    }

    public function createFilm()
    {
        return view('admin.films.create');
    }

    public function storeFilm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|integer|min:1',
            'rating' => 'nullable|string|max:10',
            'status' => 'required|in:now_playing,coming_soon',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->only(['title', 'genre', 'description', 'duration', 'rating', 'status']);

        // Handle poster upload
        if ($request->hasFile('poster')) {
            $posterName = time() . '.' . $request->poster->extension();
            $request->poster->move(public_path('images'), $posterName);
            $data['poster'] = 'images/' . $posterName;
        }

        Film::create($data);

        return redirect()->route('admin.films')->with('success', 'Film berhasil ditambahkan');
    }

    public function editFilm($id)
    {
        $film = Film::findOrFail($id);
        return view('admin.films.edit', compact('film'));
    }

    public function updateFilm(Request $request, $id)
    {
        $film = Film::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|integer|min:1',
            'rating' => 'nullable|string|max:10',
            'status' => 'required|in:now_playing,coming_soon',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->only(['title', 'genre', 'description', 'duration', 'rating', 'status']);

        // Handle poster upload
        if ($request->hasFile('poster')) {
            // Delete old poster if exists
            if ($film->poster && file_exists(public_path($film->poster))) {
                unlink(public_path($film->poster));
            }

            $posterName = time() . '.' . $request->poster->extension();
            $request->poster->move(public_path('images'), $posterName);
            $data['poster'] = 'images/' . $posterName;
        }

        $film->update($data);

        return redirect()->route('admin.films')->with('success', 'Film berhasil diupdate');
    }

    public function deleteFilm($id)
    {
        $film = Film::findOrFail($id);

        // Delete poster file if exists
        if ($film->poster && file_exists(public_path($film->poster))) {
            unlink(public_path($film->poster));
        }

        $film->delete();

        return redirect()->route('admin.films')->with('success', 'Film berhasil dihapus');
    }

    // ===================
    // PRICE MANAGEMENT
    // ===================

    public function prices()
    {
        $prices = Price::all();
        return view('admin.prices.index', compact('prices'));
    }

    public function updatePrices(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'weekday_price' => 'required|integer|min:0',
            'weekend_price' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        Price::where('day_type', 'weekday')->update(['price' => $request->weekday_price]);
        Price::where('day_type', 'weekend')->update(['price' => $request->weekend_price]);

        return redirect()->route('admin.prices')->with('success', 'Harga berhasil diupdate');
    }

    // ===================
    // SEAT MANAGEMENT
    // ===================

    public function seats()
    {
        $studios = Studio::with('seats')->get();
        return view('admin.seats.index', compact('studios'));
    }

    public function updateSeatAvailability(Request $request)
    {
        $seat = Seat::findOrFail($request->seat_id);
        $seat->update(['is_available' => $request->is_available]);

        return response()->json(['success' => true]);
    }

    // ===================
    // STUDIO MANAGEMENT
    // ===================

    public function studios()
    {
        $studios = Studio::paginate(10);
        return view('admin.studios.index', compact('studios'));
    }

    public function updateStudio(Request $request, $id)
    {
        $studio = Studio::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $studio->update($request->only(['name', 'capacity', 'is_active']));

        return redirect()->route('admin.studios')->with('success', 'Studio berhasil diupdate');
    }
    
    // ===================
    // SCHEDULE MANAGEMENT
    // ===================
    
    public function schedules()
    {
        $schedules = Schedule::with(['film', 'studio'])
            ->whereDate('show_date', '>=', today())
            ->orderBy('show_date')
            ->orderBy('show_time')
            ->paginate(20);
        return view('admin.schedules.index', compact('schedules'));
    }
    
    public function createSchedule()
    {
        $films = Film::where('status', 'now_playing')->get();
        $studios = Studio::all();
        return view('admin.schedules.create', compact('films', 'studios'));
    }
    
    public function storeSchedule(Request $request)
    {
        $request->validate([
            'film_id' => 'required|exists:films,id',
            'studio_id' => 'required|exists:studios,id',
            'show_date' => 'required|date|after_or_equal:today',
            'show_times' => 'required|array|min:1',
            'show_times.*' => 'required|date_format:H:i'
        ]);
        
        foreach ($request->show_times as $time) {
            Schedule::create([
                'film_id' => $request->film_id,
                'studio_id' => $request->studio_id,
                'show_date' => $request->show_date,
                'show_time' => $time,
                'is_active' => true
            ]);
        }
        
        return redirect()->route('admin.schedules')->with('success', 'Jadwal berhasil ditambahkan');
    }
    
    public function deleteSchedule($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();
        
        return redirect()->route('admin.schedules')->with('success', 'Jadwal berhasil dihapus');
    }
}