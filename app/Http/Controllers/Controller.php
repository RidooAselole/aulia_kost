<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KosanController extends Controller
{
    /**
     * Display the kosan homepage
     */
    public function index()
    {
        return view('kosan.index');
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
