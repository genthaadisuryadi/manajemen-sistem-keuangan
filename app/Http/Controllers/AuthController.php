<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function index()
    {
        if (session('login')) {
            return redirect()->route('dashboard');
        }
        return view('login.login');
    }

    public function proses(Request $request)
    {
        $user = DB::table('user')
            ->where('user_username', $request->username)
            ->where('user_password', md5($request->password))
            ->first();

        if (!$user) {
            return redirect()->route('login')->with('alert', 'gagal');
        }

        session([
            'login'   => true,
            'user_id' => $user->user_id,
            'nama'    => $user->user_nama,
            'level'   => $user->user_level, // administrator / manajemen
            'foto'    => $user->user_foto,
        ]);

        return redirect()->route('dashboard');
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('login')->with('alert', 'logout');
    }
}
