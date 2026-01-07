<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    /**
     * =========================
     * INDEX TRANSAKSI + SEARCH
     * =========================
     */
   public function index(Request $request)
{
    $search = trim($request->search);

    $transaksi = DB::table('transaksi')
        ->leftJoin('kategori', 'kategori.kategori_id', '=', 'transaksi.transaksi_kategori')
        ->leftJoin('mahasiswa', 'mahasiswa.nomor_induk', '=', 'transaksi.nomor_induk')
        ->select(
            'transaksi.transaksi_id',
            'transaksi.nomor_induk',
            'transaksi.transaksi_tanggal',
            'transaksi.transaksi_nominal',
            'transaksi.total_nominal',
            'kategori.kategori',
            'mahasiswa.mahasiswa',
            DB::raw('(transaksi.total_nominal - transaksi.transaksi_nominal) as sisa_nominal')
        )
        ->when($search, function ($q) use ($search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('transaksi.nomor_induk', 'like', "%$search%")
                    ->orWhere('mahasiswa.mahasiswa', 'like', "%$search%")
                    ->orWhere('kategori.kategori', 'like', "%$search%");
            });
        })
        ->orderBy('transaksi.transaksi_id', 'desc')
        ->paginate(10)
        ->withQueryString(); // ⬅️ HARUS DI SINI

    return view('transaksi.index', compact('transaksi'));
}


    /**
     * =========================
     * TAMBAH TRANSAKSI
     * =========================
     */
    public function transaksi_act(Request $request)
    {
        $request->validate([
            'nomor_induk'   => 'required',
            'tanggal'       => 'required|date',
            'total_nominal' => 'required|numeric|min:0',
            'nominal'       => 'required|numeric|min:0',
        ]);

        $mhs = DB::table('mahasiswa')
            ->where('nomor_induk', $request->nomor_induk)
            ->first();

        if (!$mhs) {
            return back()->withErrors('Mahasiswa tidak ditemukan');
        }

        if ($request->nominal > $request->total_nominal) {
            return back()->withErrors('Pembayaran melebihi harga kelas');
        }

        DB::table('transaksi')->insert([
            'nomor_induk'          => $request->nomor_induk,
            'transaksi_tanggal'    => $request->tanggal,
            'transaksi_jenis'      => 'Pemasukan',
            'transaksi_kategori'   => $mhs->kategori_id,
            'transaksi_nominal'    => $request->nominal,
            'total_nominal'        => $request->total_nominal,
            'transaksi_keterangan' => 'Pembayaran Mahasiswa',
            'transaksi_bank'       => 1,
        ]);

            return redirect()->back()

            ->with('success', 'Transaksi berhasil ditambahkan');
    }

    /**
     * =========================
     * UPDATE BAYAR
     * =========================
     */
    public function transaksi_update(Request $request)
    {
        $request->validate([
            'transaksi_id' => 'required',
            'nominal'      => 'required|numeric|min:0',
        ]);

        $trx = DB::table('transaksi')
            ->where('transaksi_id', $request->transaksi_id)
            ->first();

        if (!$trx) {
            return back()->withErrors('Transaksi tidak ditemukan');
        }

        if ($request->nominal > $trx->total_nominal) {
            return back()->withErrors('Pembayaran melebihi harga kelas');
        }

        DB::table('transaksi')
            ->where('transaksi_id', $request->transaksi_id)
            ->update([
                'transaksi_nominal' => $request->nominal,
            ]);

        return back()->with('success', 'Pembayaran berhasil diperbarui');
    }
}
