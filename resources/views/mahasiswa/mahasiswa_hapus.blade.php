<?php 
include '../koneksi.php';

// Menangkap data id yang dikirim dari url
$id = $_GET['id'];

// 1. Ambil data mahasiswa dulu untuk mendapatkan NOMOR INDUK
// Kita butuh nomor_induk karena tabel transaksi terhubung lewat nomor_induk, bukan id
$cek = mysqli_query($koneksi, "SELECT nomor_induk FROM mahasiswa WHERE mahasiswa_id='$id'");
$data = mysqli_fetch_assoc($cek);
$nomor_induk = $data['nomor_induk'];

// 2. Hapus data riwayat transaksi milik mahasiswa tersebut (Agar database bersih)
// Perhatikan: Menggunakan kolom 'nomor_induk', BUKAN 'transaksi_mahasiswa'
mysqli_query($koneksi, "DELETE FROM transaksi WHERE nomor_induk='$nomor_induk'");

// 3. Hapus data mahasiswa
mysqli_query($koneksi, "DELETE FROM mahasiswa WHERE mahasiswa_id='$id'");

// Mengalihkan halaman kembali ke data mahasiswa
header("location:mahasiswa.php");
?>