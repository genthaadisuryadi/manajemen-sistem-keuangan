@extends('layouts.index')

@section('title', 'Kategori')

@section('content')
<div class="container mx-auto px-4">

    {{-- CARD --}}
    <div class="bg-white rounded-xl shadow p-6">

        {{-- TITLE & BUTTON --}}
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-700">
                Kategori Kelas Rakit AI
            </h3>

            <button onclick="openTambah()"
                    class="bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 rounded-lg shadow flex items-center gap-2">
                <i class="bi bi-plus-lg"></i>
                Tambah Kategori
            </button>
        </div>

        {{-- ALERT --}}
        @if(session('success'))
            <div class="mb-4 bg-green-100 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        {{-- ================= TABLE DESKTOP ================= --}}
        <div class="hidden md:block overflow-x-auto border rounded-lg">
            <table class="w-full border border-gray-200 text-sm">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left w-16">NO</th>
                        <th class="px-4 py-3 text-left">NAMA</th>
                        <th class="px-4 py-3 text-center w-32">OPSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kategori as $k)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-3">
                            {{ $kategori->firstItem() + $loop->index }}
                        </td>
                        <td class="px-4 py-3">{{ $k->kategori }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($k->kategori_id != 1)
                            <div class="flex justify-center gap-2">
                                <button onclick="openEdit({{ $k->kategori_id }})"
                                        class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-2 rounded-lg shadow">
                                    <i class="bi bi-gear"></i>
                                </button>
                                <form action="{{ route('kategori.hapus', $k->kategori_id) }}" method="GET" onsubmit="return confirm('Yakin hapus?')">
                                    <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg shadow">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- ================= CARD MOBILE ================= --}}
        <div class="md:hidden space-y-3">
            @foreach($kategori as $k)
            <div class="border rounded-lg p-4 shadow-sm bg-white flex justify-between items-center">
                <div>{{ $k->kategori }}</div>
                @if($k->kategori_id != 1)
                <div class="flex gap-2">
                    <button onclick="openEdit({{ $k->kategori_id }})"
                            class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded-lg shadow text-xs">
                        Edit
                    </button>
                    <form action="{{ route('kategori.hapus', $k->kategori_id) }}" method="GET" onsubmit="return confirm('Yakin hapus?')">
                        <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg shadow text-xs">
                            Hapus
                        </button>
                    </form>
                </div>
                @endif
            </div>
            @endforeach
        </div>

        {{-- ================= PAGINATION ================= --}}
        <div class="flex flex-col md:flex-row justify-between items-center gap-3 mt-4 text-sm">
            <div class="text-gray-600">
                Showing
                {{ $kategori->firstItem() }}
                to
                {{ $kategori->lastItem() }}
                of
                {{ $kategori->total() }}
                entries
            </div>

            <div>
                {{ $kategori->links() }}
            </div>
        </div>

    </div>
</div>

{{-- MODAL TAMBAH --}}
<div id="modalTambah" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl w-full max-w-md shadow-lg">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h2 class="text-lg font-semibold">Tambah Kategori</h2>
            <button onclick="closeTambah()" class="text-gray-400 hover:text-gray-600">✕</button>
        </div>
        <form action="{{ route('kategori.act') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">Nama Kategori</label>
                <input type="text" name="kategori" class="w-full border rounded-lg px-4 py-2" required>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeTambah()" class="px-4 py-2 rounded-lg border">Batal</button>
                <button class="px-4 py-2 rounded-lg bg-sky-500 hover:bg-sky-600 text-white">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT --}}
@foreach($kategori as $k)
<div id="modalEdit{{ $k->kategori_id }}" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl w-full max-w-md shadow-lg">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h2 class="text-lg font-semibold">Edit Kategori</h2>
            <button onclick="closeEdit({{ $k->kategori_id }})" class="text-gray-400 hover:text-gray-600">✕</button>
        </div>
        <form action="{{ route('kategori.update') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="kategori_id" value="{{ $k->kategori_id }}">
            <div>
                <label class="block text-sm font-medium mb-1">Nama Kategori</label>
                <input type="text" name="kategori" value="{{ $k->kategori }}" class="w-full border rounded-lg px-4 py-2" required>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeEdit({{ $k->kategori_id }})" class="px-4 py-2 rounded-lg border">Batal</button>
                <button class="px-4 py-2 rounded-lg bg-primary text-white">Update</button>
            </div>
        </form>
    </div>
</div>
@endforeach

{{-- SCRIPT MODAL --}}
<script>
function openTambah() {
    const modal = document.getElementById('modalTambah');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closeTambah() {
    document.getElementById('modalTambah').classList.add('hidden');
}
function openEdit(id) {
    const modal = document.getElementById('modalEdit'+id);
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closeEdit(id) {
    document.getElementById('modalEdit'+id).classList.add('hidden');
}
</script>
@endsection
