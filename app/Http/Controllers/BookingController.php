<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Kos;
use App\Models\User;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Middleware untuk protect routes
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!session('admin_logged_in')) {
                return redirect()->route('admin.login');
            }
            return $next($request);
        });
    }

    /**
     * Store - Tambah booking baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'kos_id' => 'required|exists:kos,id',
            'approval_status' => 'required|in:menunggu,disetujui,ditolak',
            'payment_status' => 'required|in:unpaid,paid',
            'registration_date' => 'required|date',
            'payment_deadline' => 'required|date',
            'harga' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $booking = Booking::create($validated);

        // Jika approval_status disetujui, update kamar jadi ditempati
        if ($booking->approval_status === 'disetujui') {
            $booking->kos->update([
                'status' => 'ditempati',
                'penyewa' => $booking->user->name,
            ]);
        }

        return redirect()->route('admin.dashboard', ['section' => 'manage-bookings'])
            ->with('success', 'Booking berhasil ditambahkan');
    }

    /**
     * Update - Edit booking
     */
    public function update(Request $request, Booking $booking)
    {
        $oldApprovalStatus = $booking->approval_status;
        
        $validated = $request->validate([
            'approval_status' => 'required|in:menunggu,disetujui,ditolak',
            'payment_status' => 'required|in:unpaid,paid',
            'payment_deadline' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $booking->update($validated);

        // Handle approval status change
        $kos = $booking->kos;
        
        if ($booking->approval_status === 'disetujui' && $oldApprovalStatus !== 'disetujui') {
            // Approval dibuat
            $kos->update([
                'status' => 'ditempati',
                'penyewa' => $booking->user->name,
            ]);
        } elseif ($booking->approval_status !== 'disetujui' && $oldApprovalStatus === 'disetujui') {
            // Approval dibatalkan
            $kos->update([
                'status' => 'tersedia',
                'penyewa' => null,
            ]);
        }

        return redirect()->route('admin.dashboard', ['section' => 'manage-bookings'])
            ->with('success', 'Booking berhasil diperbarui');
    }

    /**
     * Destroy - Hapus booking
     */
    public function destroy(Booking $booking)
    {
        $kos = $booking->kos;

        // Reset kamar jadi tersedia jika booking disetujui
        if ($booking->approval_status === 'disetujui') {
            $kos->update([
                'status' => 'tersedia',
                'penyewa' => null,
            ]);
        }

        $booking->delete();

        return redirect()->route('admin.dashboard', ['section' => 'manage-bookings'])
            ->with('success', 'Booking berhasil dihapus');
    }
}
