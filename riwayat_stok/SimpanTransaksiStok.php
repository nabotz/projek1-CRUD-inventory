<?php
require_once '../auth.php';
include "../koneksi.php";

$id_supplier = $_POST['id_supplier'];
$kode_produk = $_POST['kode_produk'];
$tgl_transaksi = $_POST['tgl_transaksi'];
$tgl_kadaluarsa = !empty($_POST['tgl_kadaluarsa']) ? $_POST['tgl_kadaluarsa'] : null;
$jumlah = (int) $_POST['jumlah'];

// Get harga satuan dari kategori produk
$sql_harga = "SELECT k.harga_satuan FROM produk p JOIN kategori k ON p.id_kategori = k.id_kategori WHERE p.kode_produk = ?";
$stmt_harga = mysqli_prepare($koneksi, $sql_harga);
mysqli_stmt_bind_param($stmt_harga, "s", $kode_produk);
mysqli_stmt_execute($stmt_harga);
$result = mysqli_stmt_get_result($stmt_harga);
$row = mysqli_fetch_row($result);
$harga_satuan = $row[0] ?? 0;
mysqli_stmt_close($stmt_harga);

$total_nilai = $jumlah * $harga_satuan;

$sql = "INSERT INTO transaksi_stok (id_supplier, kode_produk, tgl_transaksi, tgl_kadaluarsa, jumlah, total_nilai) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "isssid", $id_supplier, $kode_produk, $tgl_transaksi, $tgl_kadaluarsa, $jumlah, $total_nilai);

if (mysqli_stmt_execute($stmt)) {
    header('Location: TampilRiwayatStok.php');
} else {
    error_log("Error insert transaksi stok: " . mysqli_error($koneksi));
    header('Location: TampilRiwayatStok.php?error=1');
}

mysqli_stmt_close($stmt);
exit;
?>
