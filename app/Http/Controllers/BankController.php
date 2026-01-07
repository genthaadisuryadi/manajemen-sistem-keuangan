<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankController extends Controller
{
    public function index()
    {
        $bank = DB::table('bank')->get();
        return view('bank.index', compact('bank'));
    }

    public function store(Request $request)
    {
        DB::table('bank')->insert([
            'bank_nama' => $request->bank_nama
        ]);

        return redirect()->route('bank.index');
    }

    public function hapus($id)
    {
        DB::table('bank')->where('bank_id', $id)->delete();
        return redirect()->route('bank.index');
    }

    public function update(Request $request)
    {
        DB::table('bank')->where('bank_id', $request->bank_id)->update([
            'bank_nama' => $request->bank_nama
        ]);

        return redirect()->route('bank.index');
    }
}
