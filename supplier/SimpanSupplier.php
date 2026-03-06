<?php
require_once '../auth.php';
include "../koneksi.php";

$nama = $_POST['nama'];
$alamat = $_POST['alamat'];
$no_telp = $_POST['no_telp'];
$no_npwp = $_POST['no_npwp'];
$jenis_supplier = $_POST['jenis_supplier'];

$sql = "INSERT INTO supplier (nama, alamat, no_telp, no_npwp, jenis_supplier) VALUES (?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "sssss", $nama, $alamat, $no_telp, $no_npwp, $jenis_supplier);

if (mysqli_stmt_execute($stmt)) {
    header('Location: TampilSupplier.php');
} else {
    error_log("Error insert supplier: " . mysqli_error($koneksi));
    header('Location: TampilSupplier.php?error=1');
}

mysqli_stmt_close($stmt);
exit;
?>
