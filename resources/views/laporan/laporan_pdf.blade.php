<!DOCTYPE html>
<html>
<head>
    <title>Laporan PDF</title>
    <style>
        body { font-family: DejaVu Sans; font-size: 12px }
        table { width: 100%; border-collapse: collapse }
        th, td { border: 1px solid #000; padding: 5px }
        th { background: #eee }
    </style>
</head>
<body>

<h3 align="center">LAPORAN KEUANGAN</h3>

<p>
    Tanggal : {{ request('tanggal_dari') }} s/d {{ request('tanggal_sampai') }}<br>
    Kategori :
    {{ request('kategori') == 'semua' ? 'Semua' : request('kategori') }}
</p>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Kategori</th>
            <th>Mahasiswa</th>
            <th>Nominal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $d)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ date('d-m-Y', strtotime($d->transaksi_tanggal)) }}</td>
            <td>{{ $d->kategori }}</td>
            <td>{{ $d->mahasiswa }}</td>
            <td align="right">Rp {{ number_format($d->transaksi_nominal) }}</td>
        </tr>
        @endforeach
        <tr>
            <th colspan="4">TOTAL</th>
            <th align="right">Rp {{ number_format($total_pembayaran) }}</th>
        </tr>
    </tbody>
</table>

</body>
</html>
