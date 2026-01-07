<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        if (!session('login')) {
            return redirect()->route('login')->with('alert', 'belum_login');
        }

        $hariIni = DB::table('transaksi')
            ->where('transaksi_jenis','Pemasukan')
            ->whereDate('transaksi_tanggal', Carbon::today())
            ->sum('transaksi_nominal');

        $bulanIni = DB::table('transaksi')
            ->where('transaksi_jenis','Pemasukan')
            ->whereMonth('transaksi_tanggal', Carbon::now()->month)
            ->whereYear('transaksi_tanggal', Carbon::now()->year)
            ->sum('transaksi_nominal');

        $tahunIni = DB::table('transaksi')
            ->where('transaksi_jenis','Pemasukan')
            ->whereYear('transaksi_tanggal', Carbon::now()->year)
            ->sum('transaksi_nominal');

        $total = DB::table('transaksi')
            ->where('transaksi_jenis','Pemasukan')
            ->sum('transaksi_nominal');

        $grafikBulan = DB::table('transaksi')
            ->selectRaw('MONTH(transaksi_tanggal) bulan, SUM(transaksi_nominal) total')
            ->where('transaksi_jenis','Pemasukan')
            ->whereYear('transaksi_tanggal', Carbon::now()->year)
            ->groupBy('bulan')
            ->pluck('total','bulan')
            ->toArray();

        $grafikTahun = DB::table('transaksi')
            ->selectRaw('YEAR(transaksi_tanggal) tahun, SUM(transaksi_nominal) total')
            ->where('transaksi_jenis','Pemasukan')
            ->groupBy('tahun')
            ->pluck('total','tahun')
            ->toArray();

        return view('index', compact(
            'hariIni','bulanIni','tahunIni','total',
            'grafikBulan','grafikTahun'
        ));
    }
}
