<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class KategoriController extends Controller
{
public function index()
{
$kategori = DB::table('kategori')
->orderBy('kategori_id', 'asc')
->paginate(10);


return view('kategori.index', compact('kategori'));
}
    

public function kategori_act(Request $request)
{
$request->validate([
'kategori' => 'required|string|max:255'
]);


DB::table('kategori')->insert([
'kategori' => $request->kategori
]);


return redirect()->route('kategori.index')
->with('success', 'Kategori berhasil ditambahkan');
}


public function kategori_update(Request $request)
{
$request->validate([
'kategori' => 'required|string|max:255'
]);


DB::table('kategori')
->where('kategori_id', $request->kategori_id)
->update([
'kategori' => $request->kategori
]);


return redirect()->route('kategori.index')
->with('success', 'Kategori berhasil diupdate');
}


public function kategori_hapus($id)
{
DB::table('transaksi')
->where('transaksi_kategori', $id)
->update(['transaksi_kategori' => 1]);


DB::table('kategori')->where('kategori_id', $id)->delete();


return redirect()->route('kategori.index')
->with('success', 'Kategori berhasil dihapus');
}
}