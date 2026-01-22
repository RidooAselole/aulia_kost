<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use Illuminate\Http\Request;

class KosController extends Controller
{
    // Menampilkan semua daftar kamar untuk Admin
    public function index()
    {
        $semuaKos = Kos::all();
        return view('admin.kos.index', compact('semuaKos'));
    }

    // Update Harga dan Status sekaligus
    public function update(Request $request, $id)
    {
        $request->validate([
            'harga' => 'required|numeric',
            'status' => 'required|in:tersedia,ditempati',
        ]);

        $kos = Kos::findOrFail($id);
        $kos->update([
            'harga' => $request->harga,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Data kamar berhasil diperbarui!');
    }
}