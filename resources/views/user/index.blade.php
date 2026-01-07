@extends('layouts.index')

@section('title','User')

@section('content')
<div class="container mx-auto px-4">

{{-- ================= HEADER ================= --}}
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Manajemen User</h1>
    <button onclick="openTambah()"
        class="bg-blue-600 text-white px-5 py-2 rounded">
        + Tambah User
    </button>
</div>

{{-- ================= SEARCH ================= --}}
<div class="mb-4 max-w-md">
<form method="GET" action="{{ route('user.index') }}"
      class="flex flex-col sm:flex-row gap-2">

    <input type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="Cari nama / username..."
        class="w-full border rounded px-4 py-3 text-sm">

    <div class="flex gap-2">
        <button class="flex-1 sm:flex-none bg-indigo-500 text-white px-4 py-3 rounded text-sm">
            Search
        </button>

        @if(request('search'))
        <a href="{{ route('user.index') }}"
           class="flex-1 sm:flex-none bg-gray-300 px-4 py-3 rounded text-sm text-center">
            Back
        </a>
        @endif
    </div>
</form>
</div>

{{-- ================= ALERT ================= --}}
@if(session('success'))
<div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
    {{ session('success') }}
</div>
@endif

{{-- ================= DESKTOP TABLE ================= --}}
<div class="hidden md:block overflow-x-auto bg-white rounded-lg">
<table class="w-full text-sm">
<thead class="bg-gray-100">
<tr>
    <th class="px-4 py-3">No</th>
    <th class="px-4 py-3">Foto</th>
    <th class="px-4 py-3">Nama</th>
    <th class="px-4 py-3">Username</th>
    <th class="px-4 py-3">Level</th>
    <th class="px-4 py-3 text-center">Aksi</th>
</tr>
</thead>
<tbody class="divide-y">
@foreach($user as $i => $u)
<tr>
<td class="px-4 py-2">
    {{ $user->firstItem() + $i }}
</td>

<td class="px-4 py-2">
<img src="{{ $u->user_foto ? asset($u->user_foto) : asset('default.png') }}"
     class="w-10 h-10 rounded-full object-cover">
</td>

<td class="px-4 py-2">{{ $u->user_nama }}</td>
<td class="px-4 py-2">{{ $u->user_username }}</td>
<td class="px-4 py-2 capitalize">{{ $u->user_level }}</td>

<td class="px-4 py-2 text-center space-x-2">
<button onclick="openEdit(
    '{{ $u->user_id }}',
    '{{ $u->user_nama }}',
    '{{ $u->user_username }}',
    '{{ $u->user_level }}'
)"
class="bg-yellow-500 text-white px-4 py-2 rounded">
<i class="bi bi-gear"></i>
</button>

<a href="{{ route('user.hapus',$u->user_id) }}"
   onclick="return confirm('Hapus user?')"
   class="bg-red-600 text-white px-4 py-2 rounded">
<i class="bi bi-trash"></i>
</a>
</td>
</tr>
@endforeach
</tbody>
</table>
</div>

{{-- ================= MOBILE CARD VIEW ================= --}}
<div class="md:hidden space-y-4">
@foreach($user as $u)
<div class="bg-white rounded-xl shadow p-4 border">

    <div class="flex items-center gap-3 mb-3">
        <img src="{{ $u->user_foto ? asset($u->user_foto) : asset('default.png') }}"
             class="w-12 h-12 rounded-full object-cover">
        <div>
            <p class="font-semibold">{{ $u->user_nama }}</p>
            <p class="text-sm text-gray-500">{{ $u->user_username }}</p>
        </div>
    </div>

    <div class="mb-3">
        <p class="text-xs text-gray-500">Level</p>
        <p class="font-semibold capitalize">{{ $u->user_level }}</p>
    </div>

    <div class="flex gap-2">
        <button onclick="openEdit(
            '{{ $u->user_id }}',
            '{{ $u->user_nama }}',
            '{{ $u->user_username }}',
            '{{ $u->user_level }}'
        )"
        class="flex-1 bg-yellow-500 text-white py-2 rounded text-sm">
            <i class="bi bi-gear"></i>
        </button>

        <a href="{{ route('user.hapus',$u->user_id) }}"
           onclick="return confirm('Hapus user?')"
           class="flex-1 bg-red-600 text-white py-2 rounded text-sm text-center">
            <i class="bi bi-trash"></i>
        </a>
    </div>

</div>
@endforeach
</div>

{{-- ================= PAGINATION ================= --}}
<div class="flex flex-col md:flex-row justify-between items-center gap-3 mt-4 text-sm">
    <div class="text-gray-600">
        Showing {{ $user->firstItem() }}
        to {{ $user->lastItem() }}
        of {{ $user->total() }} entries
    </div>
    <div>
        {{ $user->links() }}
    </div>
</div>

</div>

{{-- ================= MODAL TAMBAH ================= --}}
<div id="modalTambah"
 class="fixed inset-0 hidden bg-black/40 flex items-center justify-center z-50">
<div class="bg-white p-6 rounded w-full max-w-md">
<h2 class="text-lg font-bold mb-4">Tambah User</h2>

<form action="{{ route('user.act') }}" method="POST" enctype="multipart/form-data">
@csrf
<input name="nama" placeholder="Nama" class="w-full mb-3 border px-3 py-2">
<input name="username" placeholder="Username" class="w-full mb-3 border px-3 py-2">
<input name="password" type="password" placeholder="Password" class="w-full mb-3 border px-3 py-2">

<select name="level" class="w-full mb-3 border px-3 py-2">
<option value="administrator">Administrator</option>
<option value="manajemen">Manajemen</option>
</select>

<input type="file" name="foto" class="w-full mb-4 border px-3 py-2">

<div class="flex justify-end gap-2">
<button type="button" onclick="closeTambah()">Batal</button>
<button class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
</div>
</form>
</div>
</div>

{{-- ================= MODAL EDIT ================= --}}
<div id="modalEdit"
 class="fixed inset-0 hidden bg-black/40 flex items-center justify-center z-50">
<div class="bg-white p-6 rounded w-full max-w-md">
<h2 class="text-lg font-bold mb-4">Edit User</h2>

<form action="{{ route('user.update') }}" method="POST" enctype="multipart/form-data">
@csrf
<input type="hidden" name="user_id" id="e_id">

<input name="nama" id="e_nama" class="w-full mb-3 border px-3 py-2">
<input name="username" id="e_username" class="w-full mb-3 border px-3 py-2">

<input type="password" name="password"
 placeholder="Password baru (opsional)"
 class="w-full mb-3 border px-3 py-2">

<input id="e_level_lama" disabled
 class="w-full mb-3 border px-3 py-2 bg-gray-100">

<select name="level" class="w-full mb-3 border px-3 py-2">
<option value="administrator">Administrator</option>
<option value="manajemen">Manajemen</option>
</select>

<input type="file" name="foto" class="w-full mb-4 border px-3 py-2">

<div class="flex justify-end gap-2">
<button type="button" onclick="closeEdit()">Batal</button>
<button class="bg-yellow-500 text-white px-4 py-2 rounded">Update</button>
</div>
</form>
</div>
</div>

{{-- ================= SCRIPT ================= --}}
<script>
function openTambah(){ modalTambah.classList.remove('hidden') }
function closeTambah(){ modalTambah.classList.add('hidden') }

function openEdit(id,nama,username,level){
    e_id.value=id
    e_nama.value=nama
    e_username.value=username
    e_level_lama.value='Level saat ini: '+level
    modalEdit.classList.remove('hidden')
}
function closeEdit(){ modalEdit.classList.add('hidden') }
</script>

@endsection
