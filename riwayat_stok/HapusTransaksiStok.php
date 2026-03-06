<?php
require_once '../auth.php';
include "../koneksi.php";

$id = $_GET['id'] ?? '';

if (empty($id)) {
    header('Location: TampilRiwayatStok.php');
    exit;
}

$sql = "DELETE FROM transaksi_stok WHERE id_transaksi = ?";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    header('Location: TampilRiwayatStok.php');
} else {
    error_log("Error hapus transaksi stok: " . mysqli_error($koneksi));
    header('Location: TampilRiwayatStok.php?error=1');
}

mysqli_stmt_close($stmt);
exit;
?>
