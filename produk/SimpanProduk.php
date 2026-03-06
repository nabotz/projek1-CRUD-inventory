<?php
require_once '../auth.php';
include "../koneksi.php";

$kode_produk = $_POST['kode_produk'];
$id_kategori = $_POST['id_kategori'];
$lokasi = $_POST['lokasi'];

$sql = "INSERT INTO produk (kode_produk, id_kategori, lokasi) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "sis", $kode_produk, $id_kategori, $lokasi);

if (mysqli_stmt_execute($stmt)) {
    header('Location: TampilProduk.php');
} else {
    error_log("Error insert produk: " . mysqli_error($koneksi));
    header('Location: TampilProduk.php?error=1');
}

mysqli_stmt_close($stmt);
exit;
?>
