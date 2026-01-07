@extends('layouts.index')
@section('title','Laporan')

@section('content')
<div class="bg-white rounded-2xl shadow-lg p-6">

  <h3 class="text-xl font-semibold mb-6 text-gray-700 flex items-center gap-2">
    <i class="bi bi-file-earmark-text text-[#e8543e]"></i> Laporan Keuangan
  </h3>

  {{-- FILTER --}}
  <form method="GET" action="{{ route('laporan.index') }}"
        class="bg-gray-50 border rounded-xl p-5 mb-6">

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

      <div>
        <label class="text-sm font-medium text-gray-600">Mulai Tanggal</label>
        <input type="date" name="tanggal_dari"
               value="{{ request('tanggal_dari') }}"
               class="w-full mt-1 border rounded-lg p-2 focus:ring-2 focus:ring-[#e8543e]"
               required>
      </div>

      <div>
        <label class="text-sm font-medium text-gray-600">Sampai Tanggal</label>
        <input type="date" name="tanggal_sampai"
               value="{{ request('tanggal_sampai') }}"
               class="w-full mt-1 border rounded-lg p-2 focus:ring-2 focus:ring-[#e8543e]"
               required>
      </div>

      <div>
        <label class="text-sm font-medium text-gray-600">Kategori</label>
        <select name="kategori"
                class="w-full mt-1 border rounded-lg p-2 focus:ring-2 focus:ring-[#e8543e]"
                required>
          <option value="semua">- Semua Kategori -</option>
          @foreach($kategori as $k)
            <option value="{{ $k->kategori_id }}"
              {{ request('kategori') == $k->kategori_id ? 'selected' : '' }}>
              {{ $k->kategori }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="flex items-end">
        <button
          class="w-full bg-[#e8543e] hover:bg-[#d94b36] text-white py-2 rounded-lg font-semibold transition">
          <i class="bi bi-search"></i> Tampilkan
        </button>
      </div>

    </div>
  </form>

@if(request()->filled(['tanggal_dari','tanggal_sampai','kategori']))

{{-- BUTTON AKSI --}}
<div class="flex flex-wrap gap-3 mb-4">
  <a target="_blank"
     href="{{ route('laporan.pdf', request()->all()) }}"
     class="bg-[#e8543e] hover:bg-[#d94b36] text-white px-4 py-2 rounded-lg flex items-center gap-2 transition">
     <i class="bi bi-file-earmark-pdf"></i> Cetak PDF
  </a>

  <a target="_blank"
     href="{{ route('laporan.print', request()->all()) }}"
     class="bg-[#e8543e] hover:bg-[#d94b36] text-white px-4 py-2 rounded-lg flex items-center gap-2 transition">
     <i class="bi bi-printer"></i> Print
  </a>
</div>

{{-- TABEL --}}
<div class="overflow-x-auto rounded-xl border">
<table class="w-full text-sm">
<thead class="bg-gray-100 text-gray-700">
<tr>
  <th class="px-4 py-3 border">No</th>
  <th class="px-4 py-3 border">Tanggal</th>
  <th class="px-4 py-3 border">Kategori</th>
  <th class="px-4 py-3 border">Mahasiswa</th>
  <th class="px-4 py-3 border text-right">Nominal</th>
</tr>
</thead>
<tbody class="divide-y">
@forelse($data as $d)
<tr class="hover:bg-gray-50">
  <td class="px-4 py-2 text-center">{{ $loop->iteration }}</td>
  <td class="px-4 py-2">{{ date('d-m-Y', strtotime($d->transaksi_tanggal)) }}</td>
  <td class="px-4 py-2">{{ $d->kategori }}</td>
  <td class="px-4 py-2">{{ $d->mahasiswa }}</td>
  <td class="px-4 py-2 text-right">
    Rp {{ number_format($d->transaksi_nominal) }}
  </td>
</tr>
@empty
<tr>
  <td colspan="5" class="text-center py-4 text-gray-500">
    Data tidak ditemukan
  </td>
</tr>
@endforelse

<tr class="font-semibold">
  <td colspan="4" class="px-4 py-3 text-right text-gray-700">
    TOTAL
  </td>
  <td class="px-4 py-3 text-right bg-[#e8543e] text-white rounded-l-lg">
    Rp {{ number_format($total_pembayaran) }}
  </td>
</tr>

</tbody>
</table>
</div>

@else
<div class="bg-[#fdecea] text-[#e8543e] p-4 rounded-xl text-center">
  <i class="bi bi-info-circle"></i>
  Silahkan filter laporan terlebih dahulu
</div>
@endif

</div>
@endsection
