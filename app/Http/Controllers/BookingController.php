<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Kos;
use App\Models\User;
use Illuminate\Http\Request;

class BookingController extends Controller
{
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

        // Selalu reset kamar jadi tersedia ketika booking dihapus
        // Regardless of approval status, clear the tenant
        $kos->update([
            'status' => 'tersedia',
            'penyewa' => null,
        ]);

        $booking->delete();

        return redirect()->route('admin.dashboard', ['section' => 'manage-bookings'])
            ->with('success', 'Booking berhasil dihapus');
    }

    /**
     * Store From Web - Simpan booking dari form website (public)
     * Method ini dipanggil via AJAX dari halaman home
     */
    public function storeFromWeb(Request $request)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'fullName' => 'required|string|min:3|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|regex:/^[0-9]{10,13}$/',
            'roomNumber' => 'required|string|exists:kos,number',
        ]);

        try {
            // Cari kamar berdasarkan nomor
            $kos = Kos::where('number', $validated['roomNumber'])->firstOrFail();

            // Cari atau buat user berdasarkan email
            $user = User::firstOrCreate(
                ['email' => $validated['email']],
                [
                    'name' => $validated['fullName'],
                    'email' => $validated['email'],
                    'no_hp' => $validated['phone'],
                    'password' => bcrypt('temporary_password_' . uniqid()),
                ]
            );

            // Update nama dan no_hp jika sudah ada tapi infonya berbeda
            if ($user->name !== $validated['fullName'] || $user->no_hp !== $validated['phone']) {
                $user->update([
                    'name' => $validated['fullName'],
                    'no_hp' => $validated['phone'],
                ]);
            }

            // Buat booking baru dengan status "menunggu" (pending approval)
            $booking = Booking::create([
                'user_id' => $user->id,
                'kos_id' => $kos->id,
                'approval_status' => 'menunggu',  // Menunggu approval dari admin
                'payment_status' => 'unpaid',     // Belum bayar
                'registration_date' => now()->toDateString(),
                'payment_deadline' => now()->addMonth()->toDateString(),  // Deadline 1 bulan dari hari ini
                'harga' => $kos->harga,
                'notes' => 'Booking dari website - menunggu konfirmasi WhatsApp',
            ]);

            // Return response JSON dengan data booking
            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil disimpan! Silakan hubungi kami via WhatsApp untuk konfirmasi.',
                'booking' => [
                    'id' => $booking->id,
                    'room_number' => $kos->number,
                    'user_name' => $user->name,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 400);
        }
    }
}
