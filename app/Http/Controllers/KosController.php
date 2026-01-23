<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use Illuminate\Http\Request;

class KosController extends Controller
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
     * Store - Tambah kamar baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'number' => 'required|string|unique:kos,number',
            'harga' => 'required|numeric|min:0',
            'status' => 'required|in:tersedia,ditempati',
            'penyewa' => 'nullable|string',
            'foto' => 'nullable|string',
        ]);

        Kos::create($validated);

        return redirect()->route('admin.dashboard', ['section' => 'manage-rooms'])
            ->with('success', 'Kamar berhasil ditambahkan');
    }

    /**
     * Update - Edit kamar
     */
    public function update(Request $request, Kos $kos)
    {
        $validated = $request->validate([
            'number' => 'required|string|unique:kos,number,' . $kos->id,
            'harga' => 'required|numeric|min:0',
            'status' => 'required|in:tersedia,ditempati',
            'penyewa' => 'nullable|string',
            'foto' => 'nullable|string',
        ]);

        $kos->update($validated);

        return redirect()->route('admin.dashboard', ['section' => 'manage-rooms'])
            ->with('success', 'Kamar berhasil diperbarui');
    }

    /**
     * Destroy - Hapus kamar
     */
    public function destroy(Kos $kos)
    {
        $kos->delete();

        return redirect()->route('admin.dashboard', ['section' => 'manage-rooms'])
            ->with('success', 'Kamar berhasil dihapus');
    }
}