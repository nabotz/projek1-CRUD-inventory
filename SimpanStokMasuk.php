<?php
require_once 'auth.php';
include 'koneksi.php';

$tipe_supplier = $_POST['tipe_supplier'];
$kode_produk = $_POST['kode_produk'];
$tgl_transaksi = $_POST['tgl_transaksi'];
$tgl_kadaluarsa = !empty($_POST['tgl_kadaluarsa']) ? $_POST['tgl_kadaluarsa'] : null;
$jumlah = (int) $_POST['jumlah'];

if ($jumlah <= 0) {
    $_SESSION['stok_masuk_error'] = 'Jumlah harus lebih dari 0!';
    header('Location: stok_masuk.php');
    exit;
}

// Tentukan id_supplier
if ($tipe_supplier == 'baru') {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_telp = $_POST['no_telp'];
    $no_npwp = $_POST['no_npwp'];
    $jenis_supplier = $_POST['jenis_supplier'];

    $sql = "INSERT INTO supplier (nama, alamat, no_telp, no_npwp, jenis_supplier) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "sssss", $nama, $alamat, $no_telp, $no_npwp, $jenis_supplier);

    if (!mysqli_stmt_execute($stmt)) {
        error_log("Error insert supplier: " . mysqli_error($koneksi));
        $_SESSION['stok_masuk_error'] = 'Gagal menambahkan supplier baru!';
        header('Location: stok_masuk.php');
        exit;
    }
    $id_supplier = mysqli_insert_id($koneksi);
    mysqli_stmt_close($stmt);
} else {
    $id_supplier = $_POST['id_supplier'];
}

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

// Insert transaksi stok
$sql = "INSERT INTO transaksi_stok (id_supplier, kode_produk, tgl_transaksi, tgl_kadaluarsa, jumlah, total_nilai) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "isssid", $id_supplier, $kode_produk, $tgl_transaksi, $tgl_kadaluarsa, $jumlah, $total_nilai);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['stok_masuk_success'] = 'Stok masuk berhasil disimpan! Total: Rp ' . number_format($total_nilai, 0, ',', '.') . ' (' . $jumlah . ' unit)';
} else {
    error_log("Error insert transaksi stok: " . mysqli_error($koneksi));
    $_SESSION['stok_masuk_error'] = 'Gagal menyimpan transaksi stok!';
}

mysqli_stmt_close($stmt);
header('Location: stok_masuk.php');
exit;
?>
