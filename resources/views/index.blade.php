@extends('layouts.index')

@section('title', 'Dashboard')

@section('content')

{{-- ================= INFO BOX ================= --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">

    {{-- Hari Ini --}}
    <div class="relative overflow-hidden rounded-xl shadow bg-green-500 text-white">
        <div class="p-5">
            <h2 class="text-2xl font-bold">Rp {{ number_format($hariIni) }}</h2>
            <p class="text-sm opacity-90">Pemasukan Hari Ini</p>
        </div>
        <div class="bg-green-500 text-center py-2 text-sm"></div>
    </div>

    {{-- Bulan Ini --}}
    <div class="relative overflow-hidden rounded-xl shadow bg-blue-500 text-white">
        <div class="p-5">
            <h2 class="text-2xl font-bold">Rp {{ number_format($bulanIni) }}</h2>
            <p class="text-sm opacity-90">Pemasukan Bulan Ini</p>
        </div>
        <div class="bg-blue-500 text-center py-2 text-sm"></div>
    </div>

    {{-- Tahun Ini --}}
    <div class="relative overflow-hidden rounded-xl shadow bg-orange-500 text-white">
        <div class="p-5">
            <h2 class="text-2xl font-bold">Rp {{ number_format($tahunIni) }}</h2>
            <p class="text-sm opacity-90">Pemasukan Tahun Ini</p>
        </div>
        <div class="bg-orange-500 text-center py-2 text-sm"></div>
    </div>

    {{-- Total --}}
    <div class="relative overflow-hidden rounded-xl shadow bg-gray-900 text-white">
        <div class="p-5">
            <h2 class="text-2xl font-bold">Rp {{ number_format($total) }}</h2>
            <p class="text-sm opacity-90">Seluruh Pemasukan</p>
        </div>
        <div class="bg-gray-9000 text-center py-2 text-sm"></div>
    </div>

</div>

{{-- ================= GRAFIK + KALENDER ================= --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- GRAFIK --}}
    <div class="lg:col-span-2 bg-white rounded-xl shadow p-6">
        <h3 class="font-semibold mb-4">Grafik Pemasukan Per Bulan</h3>
        <div class="relative h-[280px]">
            <canvas id="chartBulan"></canvas>
        </div>
    </div>

    {{-- KALENDER --}}
    <div class="bg-green-500 text-white rounded-xl shadow p-4">
        <h3 class="font-semibold mb-3 flex items-center gap-2">
            📅 Kalender
        </h3>

        <div id="calendar" class="bg-green-600 rounded-lg p-3"></div>
    </div>

</div>

{{-- ================= GRAFIK TAHUN ================= --}}
<div class="bg-white rounded-xl shadow p-6 mt-6">
    <h3 class="font-semibold mb-4">Grafik Pemasukan Per Tahun</h3>
    <div class="relative h-[220px]">
        <canvas id="chartTahun"></canvas>
    </div>
</div>

@endsection

@push('scripts')
<script>
/* ================= DATA ================= */
const rawBulan = @json($grafikBulan);
const rawTahun = @json($grafikTahun);
const namaBulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

const dataBulanan = [];
for (let i = 1; i <= 12; i++) {
    dataBulanan.push(rawBulan[i] ?? 0);
}

/* ================= CHART BULAN ================= */
new Chart(document.getElementById('chartBulan'), {
    type: 'bar',
    data: {
        labels: namaBulan,
        datasets: [{
            data: dataBulanan,
            backgroundColor: '#22c55e',
            borderRadius: 4,
            barThickness: 18
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: v => 'Rp ' + v.toLocaleString()
                }
            }
        }
    }
});

/* ================= CHART TAHUN ================= */
new Chart(document.getElementById('chartTahun'), {
    type: 'bar',
    data: {
        labels: Object.keys(rawTahun),
        datasets: [{
            data: Object.values(rawTahun),
            backgroundColor: '#3b82f6',
            borderRadius: 6,
            barThickness: 40
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: v => 'Rp ' + v.toLocaleString()
                }
            }
        }
    }
});

/* ================= KALENDER ================= */
function renderCalendar() {
    const now = new Date();
    const year = now.getFullYear();
    const month = now.getMonth();

    const monthNames = [
        "Januari","Februari","Maret","April","Mei","Juni",
        "Juli","Agustus","September","Oktober","November","Desember"
    ];

    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    let html = `
        <div class="text-center font-semibold mb-2">
            ${monthNames[month]} ${year}
        </div>
        <div class="grid grid-cols-7 gap-1 text-center text-sm">
            <div>Su</div><div>Mo</div><div>Tu</div>
            <div>We</div><div>Th</div><div>Fr</div><div>Sa</div>
    `;

    for (let i = 0; i < firstDay; i++) {
        html += `<div></div>`;
    }

    for (let d = 1; d <= daysInMonth; d++) {
        const today = d === now.getDate()
            ? 'bg-white text-green-600 font-bold rounded'
            : '';
        html += `<div class="p-1 ${today}">${d}</div>`;
    }

    html += `</div>`;
    document.getElementById('calendar').innerHTML = html;
}

renderCalendar();
</script>
@endpush
