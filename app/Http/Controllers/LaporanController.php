<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * =========================
     * HALAMAN LAPORAN (FILTER)
     * =========================
     */
    public function index(Request $request)
    {
        $kategori = DB::table('kategori')->get();

        $data = collect();
        $total_pembayaran = 0;

        if ($request->filled(['tanggal_dari', 'tanggal_sampai', 'kategori'])) {

            $query = DB::table('transaksi')
                ->join('kategori', 'kategori.kategori_id', '=', 'transaksi.transaksi_kategori')
                ->join('mahasiswa', 'mahasiswa.nomor_induk', '=', 'transaksi.nomor_induk')
                ->whereBetween('transaksi.transaksi_tanggal', [
                    $request->tanggal_dari,
                    $request->tanggal_sampai
                ]);

            if ($request->kategori !== 'semua') {
                $query->where('transaksi.transaksi_kategori', $request->kategori);
            }

            $data = $query->select(
                'transaksi.transaksi_tanggal',
                'kategori.kategori',
                'mahasiswa.mahasiswa',
                'transaksi.transaksi_nominal'
            )->get();

            $total_pembayaran = $data->sum('transaksi_nominal');
        }

        return view('laporan.index', compact(
            'kategori',
            'data',
            'total_pembayaran'
        ));
    }

    /**
     * =========================
     * PRINT LAPORAN
     * =========================
     */
    public function laporan_print(Request $request)
    {
        $dataLaporan = $this->ambilDataLaporan($request);

        return view('laporan.laporan_print', $dataLaporan);
    }

    /**
     * =========================
     * PDF LAPORAN (DOWNLOAD)
     * =========================
     */
    public function laporan_pdf(Request $request)
    {
        $dataLaporan = $this->ambilDataLaporan($request);

        $pdf = Pdf::loadView('laporan.laporan_pdf', $dataLaporan)
                  ->setPaper('A4', 'portrait');

        return $pdf->download('laporan-keuangan.pdf');
    }

    /**
     * =========================
     * FUNGSI AMBIL DATA LAPORAN
     * =========================
     */
    private function ambilDataLaporan(Request $request)
    {
        $kategori = DB::table('kategori')->get();

        $query = DB::table('transaksi')
            ->join('kategori', 'kategori.kategori_id', '=', 'transaksi.transaksi_kategori')
            ->join('mahasiswa', 'mahasiswa.nomor_induk', '=', 'transaksi.nomor_induk')
            ->whereBetween('transaksi.transaksi_tanggal', [
                $request->tanggal_dari,
                $request->tanggal_sampai
            ]);

        if ($request->kategori !== 'semua') {
            $query->where('transaksi.transaksi_kategori', $request->kategori);
        }

        $data = $query->select(
            'transaksi.transaksi_tanggal',
            'kategori.kategori',
            'mahasiswa.mahasiswa',
            'transaksi.transaksi_nominal'
        )->get();

        return [
            'data' => $data,
            'total_pembayaran' => $data->sum('transaksi_nominal'),
            'kategori' => $kategori,
            'request' => $request
        ];
    }
}
