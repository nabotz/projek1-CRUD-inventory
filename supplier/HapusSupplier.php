<?php
require_once '../auth.php';
include "../koneksi.php";

$id = $_GET['id'] ?? '';

if (empty($id)) {
    header('Location: TampilSupplier.php');
    exit;
}

$sql = "DELETE FROM supplier WHERE id_supplier = ?";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    header('Location: TampilSupplier.php');
} else {
    error_log("Error hapus supplier: " . mysqli_error($koneksi));
    header('Location: TampilSupplier.php?error=1');
}

mysqli_stmt_close($stmt);
exit;
?>
