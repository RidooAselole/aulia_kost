<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    /**
     * Menampilkan form login admin
     */
    public function showLoginForm()
    {
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    /**
     * Handle login admin
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Hardcoded: username=admin, password=admin
        if ($request->username === 'admin' && $request->password === 'admin') {
            Session::put('admin_logged_in', true);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['error' => 'Username atau password salah'])->onlyInput('username');
    }

    /**
     * Menampilkan dashboard admin dengan data dari database
     */
    public function dashboard(Request $request)
    {
        // Check apakah sudah login
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $section = $request->get('section', 'dashboard');
        
        // Get data dari database
        $rooms = Kos::all();
        $bookings = Booking::with('user', 'kos')->get();

        // Calculate stats
        $totalRooms = $rooms->count();
        $availableRooms = $rooms->where('status', 'tersedia')->count();
        $occupiedRooms = $totalRooms - $availableRooms;

        return view('admin.dashboard', [
            'section' => $section,
            'totalRooms' => $totalRooms,
            'availableRooms' => $availableRooms,
            'occupiedRooms' => $occupiedRooms,
            'rooms' => $rooms,
            'bookings' => $bookings,
        ]);
    }

    /**
     * Logout admin
     */
    public function logout(Request $request)
    {
        Session::forget('admin_logged_in');
        return redirect()->route('admin.login');
    }

    /**
     * Update settings admin
     */
    public function updateSettings(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'old_password' => 'required',
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        // Verify old password (hardcoded 'admin' untuk sekarang)
        if ($request->old_password !== 'admin') {
            return back()->withErrors(['old_password' => 'Password lama salah']);
        }

        // TODO: Implement menyimpan ke database
        // Untuk sekarang hanya validation

        return back()->with('success', 'Pengaturan berhasil diperbarui');
    }
}
