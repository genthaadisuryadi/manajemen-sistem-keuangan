@extends('layouts.index')

@section('title','Mahasiswa')

@section('content')
<div class="container mx-auto px-4">

{{-- HEADER --}}
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Data Mahasiswa</h1>
    <button onclick="openTambah()" class="bg-blue-600 text-white px-5 py-2 rounded">
        + Tambah Mahasiswa
    </button>
</div>

{{-- SEARCH --}}

{{-- SEARCH --}}
<div class="mb-4 max-w-md">
    <form method="GET"
          action="{{ route('mahasiswa.index') }}"
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
            <a href="{{ route('mahasiswa.index') }}"
               class="flex-1 sm:flex-none bg-gray-300 text-gray-800 px-4 py-3 rounded text-sm text-center">
                Back
            </a>
            @endif
        </div>

    </form>
</div>

{{-- <div class="mb-3">
    <form method="GET" action="{{ route('mahasiswa.index') }}">
        <input type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari NIM / Nama / Kategori..."
            class="w-full md:w-1/3 border px-3 py-2 rounded">
    </form>
</div> --}}

{{-- ================= DESKTOP TABLE ================= --}}
<div class="hidden md:block overflow-x-auto bg-white rounded-lg">
<table class="w-full text-sm" id="tableMahasiswa">
<thead class="bg-gray-100">
<tr>
    <th class="px-4 py-3">No</th>
    <th class="px-4 py-3">NIM</th>
    <th class="px-4 py-3">Nama</th>
    <th class="px-4 py-3">Kategori</th>
    <th class="px-4 py-3 text-center">Aksi</th>
</tr>
</thead>
<tbody class="divide-y">
@foreach($mahasiswa as $i => $m)
<tr>
    <td class="px-4 py-2">
    {{ $mahasiswa->firstItem() + $i }}
</td>
    {{-- <td class="px-4 py-2">{{ $i+1 }}</td> --}}
    <td class="px-4 py-2">{{ $m->nomor_induk }}</td>
    <td class="px-4 py-2">{{ $m->mahasiswa }}</td>
    <td class="px-4 py-2">{{ $m->kategori }}</td>
    <td class="px-4 py-2 text-center space-x-2">

        <button onclick="openTransaksi('{{ $m->nomor_induk }}','{{ $m->mahasiswa }}','{{ $m->kategori }}')"
            class="bg-green-600 text-white px-4 py-2 rounded">
            <i class="bi bi-currency-dollar"></i>
        </button>

        <button onclick="openEdit(
            '{{ $m->mahasiswa_id }}',
            '{{ $m->nomor_induk }}',
            '{{ $m->mahasiswa }}',
            '{{ $m->kategori_id }}'
        )"
        class="bg-yellow-500 text-white px-4 py-2 rounded">
            <i class="bi bi-gear"></i>
        </button>

        <a href="{{ route('mahasiswa.hapus',$m->mahasiswa_id) }}"
           onclick="return confirm('Hapus?')"
           class="bg-red-600 text-white px-4 py-2 rounded">
           <i class="bi bi-trash"></i>
        </a>

    </td>
</tr>
@endforeach
</tbody>
</table>
{{-- 
INFO ENTRIES (JS SEARCH)
<div id="infoEntries" class="px-4 py-2 text-sm text-gray-600">
    Showing 0 to 0 of 0 entries
</div> --}}

</div>

{{-- ================= MOBILE CARD VIEW ================= --}}
<div class="md:hidden space-y-4" id="cardMahasiswa">
@foreach($mahasiswa as $m)
<div class="bg-white rounded-xl shadow p-4 border">

    <div class="mb-2">
        <p class="text-xs text-gray-500">NIM</p>
        <p class="font-semibold">{{ $m->nomor_induk }}</p>
    </div>

    <div class="mb-2">
        <p class="text-xs text-gray-500">Nama</p>
        <p class="font-semibold">{{ $m->mahasiswa }}</p>
    </div>

    <div class="mb-3">
        <p class="text-xs text-gray-500">Kategori</p>
        <p class="font-semibold">{{ $m->kategori }}</p>
    </div>

    <div class="flex gap-2">
        <button onclick="openTransaksi('{{ $m->nomor_induk }}','{{ $m->mahasiswa }}','{{ $m->kategori }}')"
            class="flex-1 bg-green-600 text-white py-2 rounded text-sm">
            <i class="bi bi-currency-dollar"></i>
        </button>

        <button onclick="openEdit(
            '{{ $m->mahasiswa_id }}',
            '{{ $m->nomor_induk }}',
            '{{ $m->mahasiswa }}',
            '{{ $m->kategori_id }}'
        )"
        class="flex-1 bg-yellow-500 text-white py-2 rounded text-sm">
            <i class="bi bi-gear"></i>
        </button>

        <a href="{{ route('mahasiswa.hapus',$m->mahasiswa_id) }}"
           onclick="return confirm('Hapus?')"
           class="flex-1 bg-red-600 text-white py-2 rounded text-sm text-center">
            <i class="bi bi-trash"></i>
        </a>
    </div>

</div>
@endforeach
</div>

{{-- ================= PAGINATION (DITAMBAH, TIDAK NGUBAH APAPUN) ================= --}}
<div class="flex flex-col md:flex-row justify-between items-center gap-3 mt-4 text-sm">
    <div class="text-gray-600">
        Showing
        {{ $mahasiswa->firstItem() }}
        to
        {{ $mahasiswa->lastItem() }}
        of
        {{ $mahasiswa->total() }}
        entries
    </div>

    <div>
        {{ $mahasiswa->links() }}
    </div>
</div>

</div>

{{-- ================= MODAL TAMBAH ================= --}}
<div id="modalTambah" class="fixed inset-0 hidden bg-black/40 flex items-center justify-center z-50">
<div class="bg-white p-6 rounded w-full max-w-md">
<h2 class="text-lg font-bold mb-4">Tambah Mahasiswa</h2>

<form action="{{ route('mahasiswa.act') }}" method="POST">
@csrf
<label class="text-sm font-medium">Nomor Induk (NIM)</label>
<input name="nomor_induk" class="w-full mb-3 border px-3 py-2">

<label class="text-sm font-medium">Nama Mahasiswa</label>
<input name="mahasiswa" class="w-full mb-3 border px-3 py-2">

<label class="text-sm font-medium">Kategori</label>
<select name="kategori_id" class="w-full mb-4 border px-3 py-2">
@foreach($kategori as $k)
<option value="{{ $k->kategori_id }}">{{ $k->kategori }}</option>
@endforeach
</select>

<div class="flex justify-end gap-2">
<button type="button" onclick="closeTambah()">Batal</button>
<button class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
</div>
</form>
</div>
</div>

{{-- ================= MODAL EDIT ================= --}}
<div id="modalEdit" class="fixed inset-0 hidden bg-black/40 flex items-center justify-center z-50">
<div class="bg-white p-6 rounded w-full max-w-md">
<h2 class="text-lg font-bold mb-4">Edit Mahasiswa</h2>

<form action="{{ route('mahasiswa.update') }}" method="POST">
@csrf
<input type="hidden" name="id" id="e_id">

<label class="text-sm font-medium">Nomor Induk (NIM)</label>
<input name="nomor_induk" id="e_nim" class="w-full mb-3 border px-3 py-2">

<label class="text-sm font-medium">Nama Mahasiswa</label>
<input name="mahasiswa" id="e_nama" class="w-full mb-3 border px-3 py-2">

<label class="text-sm font-medium">Kategori</label>
<select name="kategori_id" id="e_kategori" class="w-full mb-4 border px-3 py-2">
@foreach($kategori as $k)
<option value="{{ $k->kategori_id }}">{{ $k->kategori }}</option>
@endforeach
</select>

<div class="flex justify-end gap-2">
<button type="button" onclick="closeEdit()">Batal</button>
<button class="bg-yellow-500 text-white px-4 py-2 rounded">Update</button>
</div>
</form>
</div>
</div>

{{-- ================= MODAL TRANSAKSI ================= --}}
<div id="modalTransaksi" class="fixed inset-0 hidden bg-black/40 flex items-center justify-center z-50">
<div class="bg-white p-6 rounded w-full max-w-md">
<h2 class="text-lg font-bold mb-4">Transaksi Pembayaran</h2>

<form action="{{ route('transaksi.act') }}" method="POST" id="formTransaksi">
@csrf
<input type="hidden" name="nomor_induk" id="t_nomor">

<label class="text-sm font-medium">Nama Mahasiswa</label>
<input id="t_nama" readonly class="w-full mb-3 border px-3 py-2 bg-gray-100">

<label class="text-sm font-medium">Kategori</label>
<input id="t_kategori" readonly class="w-full mb-3 border px-3 py-2 bg-gray-100">

<label class="text-sm font-medium">Tanggal Pembayaran</label>
<input type="date" name="tanggal" class="w-full mb-3 border px-3 py-2">

<label class="text-sm font-medium">Harga Kelas</label>
<input type="number" name="total_nominal" class="w-full mb-3 border px-3 py-2">

<label class="text-sm font-medium">Nominal Dibayar</label>
<input type="number" name="nominal" class="w-full mb-4 border px-3 py-2">

<div class="flex justify-end gap-2">
<button type="button" onclick="closeTransaksi()">Batal</button>
<button class="bg-green-600 text-white px-4 py-2 rounded">Bayar</button>
</div>
</form>
</div>
</div>

{{-- ================= SCRIPT ================= --}}
<script>
function openTambah(){ modalTambah.classList.remove('hidden') }
function closeTambah(){ modalTambah.classList.add('hidden') }

function openEdit(id,nim,nama,kategori){
    e_id.value=id
    e_nim.value=nim
    e_nama.value=nama
    e_kategori.value=kategori
    modalEdit.classList.remove('hidden')
}
function closeEdit(){ modalEdit.classList.add('hidden') }

function openTransaksi(nim,nama,kategori){
    t_nomor.value=nim
    t_nama.value=nama
    t_kategori.value=kategori
    modalTransaksi.classList.remove('hidden')
}
function closeTransaksi(){ modalTransaksi.classList.add('hidden') }

// function filterMahasiswa(){
//     let keyword = document.getElementById('searchMahasiswa').value.toLowerCase()

//     document.querySelectorAll('#tableMahasiswa tbody tr').forEach(row=>{
//         row.style.display = row.innerText.toLowerCase().includes(keyword) ? '' : 'none'
//     })

//     document.querySelectorAll('#cardMahasiswa > div').forEach(card=>{
//         card.style.display = card.innerText.toLowerCase().includes(keyword) ? '' : 'none'
//     })

//     updateEntriesInfo()
// }

// function updateEntriesInfo(){
//     let rows = document.querySelectorAll('#tableMahasiswa tbody tr')
//     let visible = Array.from(rows).filter(r => r.style.display !== 'none')

//     let total = rows.length
//     let show = visible.length

//     let from = show > 0 ? 1 : 0
//     let to = show

//     document.getElementById('infoEntries').innerText =
//         `Showing ${from} to ${to} of ${total} entaries`
// }

// document.addEventListener('DOMContentLoaded', updateEntriesInfo)
// </script>
<script>
document.getElementById('formTransaksi').addEventListener('submit', function(e){
    let harga = document.querySelector('input[name="total_nominal"]').value
    let bayar = document.querySelector('input[name="nominal"]').value

    harga = parseInt(harga)
    bayar = parseInt(bayar)

    if(bayar > harga){
        e.preventDefault()
        alert('Nominal dibayar tidak boleh lebih besar dari harga kelas!')
    }
})
</script>

@endsection
