<?php 
session_start();
include '../koneksi.php';

// 1. Tangkap data dari modal
$nomor_induk   = $_POST['nomor_induk']; 
$tanggal       = $_POST['tanggal'];
$kategori_id   = $_POST['kategori']; 
$nominal_bayar = $_POST['nominal'];     
$harga_kelas   = $_POST['total_nominal']; 

// PERBAIKAN DISINI:
// Cek apakah input keterangan ada? Jika tidak ada atau kosong, isi dengan tanda strip "-"
if (isset($_POST['keterangan']) && !empty($_POST['keterangan'])) {
    $keterangan = $_POST['keterangan'];
} else {
    $keterangan = "-"; // Default value agar tidak NULL
}

// 2. Hitung Sisa Pembayaran
$sisa_pembayaran = (int)$harga_kelas - (int)$nominal_bayar;

// 3. Simpan ke database 'transaksi'
$sql = "INSERT INTO transaksi (
            transaksi_tanggal, 
            nomor_induk, 
            transaksi_kategori, 
            transaksi_nominal, 
            total_nominal, 
            sisa_nominal, 
            transaksi_keterangan,
            transaksi_jenis
        ) VALUES (?, ?, ?, ?, ?, ?, ?, 'Pemasukan')";

$stmt = mysqli_prepare($koneksi, $sql);

if ($stmt) {
    // 4. Bind tipe data:
    // s = string (tanggal)
    // s = string (nomor_induk)
    // i = integer (kategori_id)
    // i = integer (nominal_bayar)
    // i = integer (harga_kelas)
    // i = integer (sisa_pembayaran)
    // s = string (keterangan)
    mysqli_stmt_bind_param($stmt, "ssiiiis", 
        $tanggal, 
        $nomor_induk, 
        $kategori_id, 
        $nominal_bayar, 
        $harga_kelas, 
        $sisa_pembayaran,
        $keterangan
    );

    // 5. Eksekusi dan Beri Feedback
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['pesan'] = 'Pembayaran berhasil disimpan.';
        $_SESSION['pesan_tipe'] = 'success';
    } else {
        $_SESSION['pesan'] = 'ERROR: Gagal menyimpan. ' . mysqli_stmt_error($stmt);
        $_SESSION['pesan_tipe'] = 'danger';
    }
    mysqli_stmt_close($stmt);

} else {
    $_SESSION['pesan'] = 'ERROR: Gagal mempersiapkan statement. ' . mysqli_error($koneksi);
    $_SESSION['pesan_tipe'] = 'danger';
}

mysqli_close($koneksi);

// 6. Kembalikan ke halaman data transaksi
header("Location: transaksi.php"); 
exit();
?>