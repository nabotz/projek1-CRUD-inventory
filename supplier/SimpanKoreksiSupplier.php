<?php
require_once '../auth.php';
include "../koneksi.php";

$id = $_POST['id'];
$nama = $_POST['nama'];
$alamat = $_POST['alamat'];
$no_telp = $_POST['no_telp'];
$no_npwp = $_POST['no_npwp'];
$jenis_supplier = $_POST['jenis_supplier'];

$sql = "UPDATE supplier SET nama = ?, alamat = ?, no_telp = ?, no_npwp = ?, jenis_supplier = ? WHERE id_supplier = ?";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "sssssi", $nama, $alamat, $no_telp, $no_npwp, $jenis_supplier, $id);

if (mysqli_stmt_execute($stmt)) {
    header('Location: TampilSupplier.php');
} else {
    error_log("Error update supplier: " . mysqli_error($koneksi));
    header('Location: TampilSupplier.php?error=1');
}

mysqli_stmt_close($stmt);
exit;
?>
