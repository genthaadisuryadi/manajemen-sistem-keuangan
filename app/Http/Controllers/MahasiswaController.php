<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    
{
    $search = $request->search;

    $mahasiswa = DB::table('mahasiswa')
        ->leftJoin('kategori', 'kategori.kategori_id', '=', 'mahasiswa.kategori_id')
        ->select(
            'mahasiswa.mahasiswa_id',
            'mahasiswa.nomor_induk',
            'mahasiswa.mahasiswa',
            'mahasiswa.kategori_id',
            'kategori.kategori'
        )
        ->when($search, function ($q) use ($search) {
            $q->where('mahasiswa.nomor_induk', 'like', "%$search%")
              ->orWhere('mahasiswa.mahasiswa', 'like', "%$search%")
              ->orWhere('kategori.kategori', 'like', "%$search%");
        })
        ->orderBy('mahasiswa.mahasiswa_id', 'desc')
        ->paginate(10)
        ->withQueryString(); // penting biar search tidak hilang saat pindah page

    $kategori = DB::table('kategori')
        ->orderBy('kategori', 'asc')
        ->get();

    return view('mahasiswa.index', compact('mahasiswa', 'kategori'));
}

    public function mahasiswa_act(Request $request)
{
    $request->validate([
        'nomor_induk' => 'required',
        'mahasiswa'   => 'required',
        'kategori_id' => 'required',
    ]);

    DB::table('mahasiswa')->insert([
        'nomor_induk' => $request->nomor_induk,
        'mahasiswa'   => $request->mahasiswa,
        'kategori_id' => $request->kategori_id,
    ]);

    return redirect()->route('mahasiswa.index')
        ->with('success', 'Mahasiswa berhasil ditambahkan');
}

    public function mahasiswa_update(Request $request)
    {
        DB::table('mahasiswa')
            ->where('mahasiswa_id', $request->id)
            ->update([
                'nomor_induk' => $request->nomor_induk,
                'mahasiswa'   => $request->mahasiswa,
                'kategori_id' => $request->kategori_id,
            ]);

        return redirect()->route('mahasiswa.index');
    }

    /**
     * =========================
     * HAPUS MAHASISWA + TRANSAKSI
     * =========================
     */
    public function mahasiswa_hapus($id)
    {
        // ambil mahasiswa dulu
        $mhs = DB::table('mahasiswa')
            ->where('mahasiswa_id', $id)
            ->first();

        if (!$mhs) {
            return redirect()->route('mahasiswa.index');
        }

        DB::beginTransaction();

        try {
            // 1️⃣ hapus semua transaksi mahasiswa
            DB::table('transaksi')
                ->where('nomor_induk', $mhs->nomor_induk)
                ->delete();

            // 2️⃣ hapus mahasiswa
            DB::table('mahasiswa')
                ->where('mahasiswa_id', $id)
                ->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Gagal menghapus mahasiswa & transaksi');
        }

        return redirect()->route('mahasiswa.index');
    }
}
