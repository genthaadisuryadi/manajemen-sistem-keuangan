@extends('layouts.index')

@section('title', 'Transaksi')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">

{{-- HEADER --}}
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Data Transaksi</h1>
</div>

{{-- SEARCH --}}
{{-- SEARCH --}}
<div class="mb-4 max-w-md">
    <form method="GET"
          action="{{ route('transaksi.index') }}"
          class="flex flex-col sm:flex-row gap-2">

        <input type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari NIM / Nama / Kelas..."
            class="w-full border rounded px-4 py-3 text-sm">

        <div class="flex gap-2">
            <button type="submit"
                class="flex-1 sm:flex-none bg-indigo-500 text-white px-4 py-3 rounded text-sm">
                Search
            </button>

            @if(request('search'))
            <a href="{{ route('transaksi.index') }}"
               class="flex-1 sm:flex-none bg-gray-300 text-gray-800 px-4 py-3 rounded text-sm text-center">
                Back
            </a>
            @endif
        </div>

    </form>
</div>




{{-- ALERT --}}
@if(session('success'))
<div class="mb-4 bg-green-100 text-green-700 px-4 py-3 rounded">
    {{ session('success') }}
</div>
@endif

{{-- ================= DESKTOP TABLE ================= --}}
<div class="hidden md:block overflow-x-auto bg-white rounded-xl shadow">
<table class="w-full text-sm" id="transaksiTable">
<thead class="bg-gray-100">
<tr>
    <th class="px-4 py-3 text-left">No</th>
    <th class="px-4 py-3 text-left">NIM</th>
    <th class="px-4 py-3 text-left">Nama</th>
    <th class="px-4 py-3 text-left">Kelas</th>
    <th class="px-4 py-3 text-right">Harga Kelas</th>
    <th class="px-4 py-3 text-right">Bayar</th>
    <th class="px-4 py-3 text-right">Sisa</th>
    <th class="px-4 py-3 text-center">Aksi</th>
</tr>
</thead>
<tbody class="divide-y">
@foreach($transaksi as $i => $t)
<tr>
    <td class="px-4 py-3">
        {{ $transaksi->firstItem() + $i }}
    </td>
    <td class="px-4 py-3">{{ $t->nomor_induk }}</td>
    <td class="px-4 py-3">{{ $t->mahasiswa }}</td>
    <td class="px-4 py-3">{{ $t->kategori }}</td>
    <td class="px-4 py-3 text-right">
        Rp {{ number_format($t->total_nominal,0,',','.') }}
    </td>
    <td class="px-4 py-3 text-right">
        Rp {{ number_format($t->transaksi_nominal,0,',','.') }}
    </td>
    <td class="px-4 py-3 text-right text-red-600">
        Rp {{ number_format($t->sisa_nominal,0,',','.') }}
    </td>
    <td class="px-4 py-3 text-center">
        <button onclick="openModal('modalEdit{{ $t->transaksi_id }}')"
            class="bg-indigo-500 text-white px-4 py-2 rounded-md text-sm">
            Edit Bayar
        </button>
    </td>
</tr>
@endforeach
</tbody>
</table>
</div>

{{-- ================= MOBILE CARD VIEW ================= --}}
<div class="md:hidden space-y-4" id="cardTransaksi">
@foreach($transaksi as $t)
<div class="bg-white rounded-xl shadow p-4 border">

    <div class="mb-2">
        <p class="text-xs text-gray-500">NIM</p>
        <p class="font-semibold">{{ $t->nomor_induk }}</p>
    </div>

    <div class="mb-2">
        <p class="text-xs text-gray-500">Nama</p>
        <p class="font-semibold">{{ $t->mahasiswa }}</p>
    </div>

    <div class="mb-2">
        <p class="text-xs text-gray-500">Kelas</p>
        <p class="font-semibold">{{ $t->kategori }}</p>
    </div>

    <div class="grid grid-cols-3 gap-2 text-sm mb-3">
        <div>
            <p class="text-gray-500">Harga</p>
            <p class="font-semibold">
                Rp {{ number_format($t->total_nominal,0,',','.') }}
            </p>
        </div>
        <div>
            <p class="text-gray-500">Bayar</p>
            <p class="font-semibold">
                Rp {{ number_format($t->transaksi_nominal,0,',','.') }}
            </p>
        </div>
        <div>
            <p class="text-gray-500">Sisa</p>
            <p class="font-semibold text-red-600">
                Rp {{ number_format($t->sisa_nominal,0,',','.') }}
            </p>
        </div>
    </div>

    <button onclick="openModal('modalEdit{{ $t->transaksi_id }}')"
        class="w-full bg-indigo-500 text-white py-2 rounded text-sm">
        Edit Bayar
    </button>

</div>
@endforeach
</div>

{{-- ================= PAGINATION ================= --}}
<div class="flex flex-col md:flex-row justify-between items-center gap-3 mt-4 text-sm">

    <div class="text-gray-600">
        Showing
        {{ $transaksi->firstItem() }}
        to
        {{ $transaksi->lastItem() }}
        of
        {{ $transaksi->total() }}
        entries
    </div>

    <div>
        {{ $transaksi->links() }}
    </div>

</div>

</div>

{{-- ================= MODAL EDIT ================= --}}
@foreach($transaksi as $t)
<div id="modalEdit{{ $t->transaksi_id }}"
    class="fixed inset-0 hidden bg-black/40 flex items-center justify-center z-50">

<div class="bg-white w-full max-w-md rounded-xl p-6">
<h2 class="text-lg font-bold mb-4 text-center">Edit Pembayaran</h2>

<form action="{{ route('transaksi.update') }}" method="POST">
@csrf
<input type="hidden" name="transaksi_id" value="{{ $t->transaksi_id }}">

<label class="text-sm font-medium">NIM</label>
<input readonly value="{{ $t->nomor_induk }}"
 class="w-full mb-3 bg-gray-100 border px-3 py-2">

<label class="text-sm font-medium">Nama</label>
<input readonly value="{{ $t->mahasiswa }}"
 class="w-full mb-3 bg-gray-100 border px-3 py-2">

<label class="text-sm font-medium">Kelas</label>
<input readonly value="{{ $t->kategori }}"
 class="w-full mb-3 bg-gray-100 border px-3 py-2">

<label class="text-sm font-medium">Tanggal</label>
<input type="date" name="tanggal"
 value="{{ $t->transaksi_tanggal }}"
 class="w-full mb-3 border px-3 py-2">

<label class="text-sm font-medium">Nominal Bayar</label>
<input type="number" name="nominal"
 value="{{ $t->transaksi_nominal }}"
 class="w-full mb-4 border px-3 py-2">

<div class="flex gap-2">
<button type="button"
 onclick="closeModal('modalEdit{{ $t->transaksi_id }}')"
 class="w-1/2 border py-2 rounded">
 Batal
</button>
<button class="w-1/2 bg-indigo-500 text-white py-2 rounded">
 Simpan
</button>
</div>
</form>
</div>
</div>
@endforeach

{{-- ================= SCRIPT ================= --}}
<script>
function openModal(id){
    document.getElementById(id).classList.remove('hidden')
}
function closeModal(id){
    document.getElementById(id).classList.add('hidden')
}

// document.getElementById('searchInput').addEventListener('keyup', function(){
//     let val = this.value.toLowerCase()

//     document.querySelectorAll('#transaksiTable tbody tr').forEach(tr=>{
//         tr.style.display = tr.innerText.toLowerCase().includes(val) ? '' : 'none'
//     })

//     document.querySelectorAll('#cardTransaksi > div').forEach(card=>{
//         card.style.display = card.innerText.toLowerCase().includes(val) ? '' : 'none'
//     })
// })
</script>
@endsection
 