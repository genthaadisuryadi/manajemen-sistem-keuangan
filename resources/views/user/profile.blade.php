@extends('layouts.index')
@section('title','Profile')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
<h2 class="text-xl font-bold mb-4">Profile</h2>

@if(session('success'))
<div class="bg-green-100 text-green-700 px-3 py-2 mb-3 rounded">
{{ session('success') }}
</div>
@endif

<p><b>Nama:</b> {{ $user->user_nama }}</p>
<p><b>Username:</b> {{ $user->user_username }}</p>
<p><b>Level:</b> {{ $user->user_level }}</p>

<hr class="my-4">

<form method="POST" action="{{ route('user.password') }}">
@csrf
<label>Password Baru</label>
<input type="password" name="password" class="w-full border px-3 py-2 mb-3">
<button class="bg-blue-600 text-white px-4 py-2 rounded">Ganti Password</button>
</form>
</div>
@endsection
