<?php
require_once '../auth.php';
include "../koneksi.php";

$id = $_POST['id'];
$id_kategori = $_POST['id_kategori'];
$lokasi = $_POST['lokasi'];

$sql = "UPDATE produk SET id_kategori = ?, lokasi = ? WHERE kode_produk = ?";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "iss", $id_kategori, $lokasi, $id);

if (mysqli_stmt_execute($stmt)) {
    header('Location: TampilProduk.php');
} else {
    error_log("Error update produk: " . mysqli_error($koneksi));
    header('Location: TampilProduk.php?error=1');
}

mysqli_stmt_close($stmt);
exit;
?>
