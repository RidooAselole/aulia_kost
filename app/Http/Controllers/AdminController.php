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
 * Menampilkan dashboard admin
 */
    public function dashboard()
    {
        return view('admin.dashboard');
    }

/**
*  Logout
*/
    public function logout()
    {
        Session::forget('admin_logged_in');
        return redirect()->route('admin.login');
    }
}
