<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    /**
     * Menampilkan form login admin
     */
    public function showLoginForm()
    {
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

        if ($request->username === 'admin' && $request->password === 'password') {
            Session::put('admin_logged_in', true);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['Invalid credentials']);
    }

    /**
     * Menampilkan dashboard admin (Frontend Only - No Database)
     */
    public function dashboard(Request $request)
    {
        $section = $request->get('section', 'dashboard');
        
        // Data dummy untuk tampilan (tidak dari database)
        $totalRooms = 0;
        $availableRooms = 0;
        $occupiedRooms = 0;
        $rooms = collect([]); // Empty collection
        $bookings = collect([]); // Empty collection

        return view('admin.dashboard', compact(
            'section',
            'totalRooms',
            'availableRooms',
            'occupiedRooms',
            'rooms',
            'bookings'
        ));
    }

    /**
     * Logout
     */
    public function logout()
    {
        Session::forget('admin_logged_in');
        return redirect()->route('admin.login');
    }
}
