<?php 
include '../koneksi.php';
$mahasiswa  = $_POST['mahasiswa'];
$nomor_induk  = $_POST['nomor_induk'];

mysqli_query($koneksi, "insert into mahasiswa values (NULL,'$mahasiswa', '$nomor_induk')");
header("location:mahasiswa.php");