<?php
require_once '../auth.php';
include "../koneksi.php";

$id = $_GET['id'] ?? '';

if (empty($id)) {
    header('Location: TampilProduk.php');
    exit;
}

$sql = "DELETE FROM produk WHERE kode_produk = ?";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "s", $id);

if (mysqli_stmt_execute($stmt)) {
    header('Location: TampilProduk.php');
} else {
    error_log("Error hapus produk: " . mysqli_error($koneksi));
    header('Location: TampilProduk.php?error=1');
}

mysqli_stmt_close($stmt);
exit;
?>
