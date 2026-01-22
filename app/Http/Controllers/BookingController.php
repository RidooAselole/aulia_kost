<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Kos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    // Menampilkan daftar booking untuk Admin
    public function index()
    {
        $bookings = Booking::with(['user', 'kos'])->latest()->get();
        return view('admin.bookings.index', compact('bookings'));
    }

    // Proses simpan booking baru dari User
    public function store(Request $request)
    {
        $request->validate([
            'kos_id' => 'required|exists:kos,id',
        ]);

        $kos = Kos::find($request->kos_id);

        // Buat data booking
        Booking::create([
            'user_id' => Auth::id(), // Mengambil ID user yang sedang login
            'kos_id' => $kos->id,
            'status' => 'pending',
            'total_harga' => $kos->harga,
            'payment_deadline' => Carbon::now()->addDay(), // Deadline 24 jam dari sekarang
        ]);

        return redirect()->back()->with('success', 'Booking berhasil! Silakan lakukan pembayaran sebelum 24 jam.');
    }

    // Update status booking (misal: Approved oleh Admin)
    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Status booking diperbarui.');
    }
}