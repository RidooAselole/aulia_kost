<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use Illuminate\Http\Request;

class KosanController extends Controller
{
    /**
     * Display the kosan homepage dengan data dari database
     */
    public function index()
    {
        // Ambil semua data kamar dari database
        $rooms = Kos::all();

        return view('kosan.home', [
            'rooms' => $rooms
        ]);
    }

    /**
     * Handle booking request (optional)
     */
    public function store(Request $request)
    {
        $request->validate([
            'room_number' => 'required|integer|min:1|max:16',
        ]);

        // Add your booking logic here
        // For example: save to database, send notification, etc.

        return response()->json([
            'success' => true,
            'message' => 'Booking request received'
        ]);
    }
}
