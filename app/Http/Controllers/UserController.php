<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /* ================= INDEX ================= */
    public function index(Request $request)
    {
        $search = $request->search;

        $user = DB::table('user')
            ->when($search, function ($q) use ($search) {
                $q->where('user_nama', 'like', "%$search%")
                  ->orWhere('user_username', 'like', "%$search%");
            })
            ->orderBy('user_id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('user.index', compact('user', 'search'));
    }

    /* ================= TAMBAH USER ================= */
    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required',
            'username' => 'required|unique:user,user_username',
            'password' => 'required|min:4',
            'level'    => 'required|in:administrator,manajemen',
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png|max:5120' // 5MB
        ]);

        $fotoPath = null;

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $namaFoto = time().'_'.$foto->getClientOriginalName();
            $foto->move(public_path('img/user'), $namaFoto);
            $fotoPath = 'img/user/'.$namaFoto;
        }

        DB::table('user')->insert([
            'user_nama'     => $request->nama,
            'user_username' => $request->username,
            'user_password' => Hash::make($request->password),
            'user_level'    => $request->level,
            'user_foto'     => $fotoPath
        ]);

        return redirect()->route('user.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    /* ================= UPDATE USER ================= */
    public function update(Request $request)
    {
        $request->validate([
            'user_id'  => 'required',
            'nama'     => 'required',
            'username' => 'required',
            'level'    => 'required|in:administrator,manajemen',
            'password' => 'nullable|min:4',
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png|max:5120' // 5MB
        ]);

        $user = DB::table('user')
            ->where('user_id', $request->user_id)
            ->first();

        // ✅ JIKA USER TIDAK ADA
        if (!$user) {
            return redirect()->route('user.index')
                ->with('error', 'User tidak ditemukan');
        }

        $fotoPath = $user->user_foto;

        /* ===== UPLOAD FOTO BARU ===== */
        if ($request->hasFile('foto')) {

            // hapus foto lama
            if ($fotoPath && file_exists(public_path($fotoPath))) {
                unlink(public_path($fotoPath));
            }

            $foto = $request->file('foto');
            $namaFoto = time().'_'.$foto->getClientOriginalName();
            $foto->move(public_path('img/user'), $namaFoto);
            $fotoPath = 'img/user/'.$namaFoto;
        }

        $dataUpdate = [
            'user_nama'     => $request->nama,
            'user_username' => $request->username,
            'user_level'    => $request->level,
            'user_foto'     => $fotoPath
        ];

        // password hanya diupdate jika diisi
        if ($request->filled('password')) {
            $dataUpdate['user_password'] = Hash::make($request->password);
        }

        DB::table('user')
            ->where('user_id', $request->user_id)
            ->update($dataUpdate);

        // update session jika user login diedit
        if (session('user_id') == $request->user_id) {
            session([
                'nama'  => $request->nama,
                'level' => $request->level,
                'foto'  => $fotoPath
            ]);
        }

        return redirect()->route('user.index')
            ->with('success', 'User berhasil diupdate');
    }

    /* ================= HAPUS USER ================= */
    public function hapus($id)
    {
        $user = DB::table('user')->where('user_id', $id)->first();

        if ($user && $user->user_foto && file_exists(public_path($user->user_foto))) {
            unlink(public_path($user->user_foto));
        }

        DB::table('user')->where('user_id', $id)->delete();

        return redirect()->route('user.index')
            ->with('success', 'User berhasil dihapus');
    }
}
