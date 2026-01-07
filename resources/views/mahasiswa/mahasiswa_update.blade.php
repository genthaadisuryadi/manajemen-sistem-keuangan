<?php 
include '../koneksi.php';
$id  = $_POST['id'];
$mahasiswa  = $_POST['mahasiswa'];
$nomor_induk  = $_POST['nomor_induk'];

mysqli_query($koneksi, "update mahasiswa set mahasiswa='$mahasiswa', nomor_induk='$nomor_induk' where mahasiswa_id='$id'");
header("location:mahasiswa.php");