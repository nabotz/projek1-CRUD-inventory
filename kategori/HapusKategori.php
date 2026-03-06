<?php
require_once '../auth.php';
include "../koneksi.php";

$id = $_GET['id'] ?? '';

if (empty($id)) {
    header('Location: TampilKategori.php');
    exit;
}

$sql_foto = "SELECT foto FROM kategori WHERE id_kategori = ?";
$stmt_foto = mysqli_prepare($koneksi, $sql_foto);
mysqli_stmt_bind_param($stmt_foto, "i", $id);
mysqli_stmt_execute($stmt_foto);
$result = mysqli_stmt_get_result($stmt_foto);
$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt_foto);

if (!empty($data['foto']) && file_exists('uploads/' . $data['foto'])) {
    unlink('uploads/' . $data['foto']);
}

$sql = "DELETE FROM kategori WHERE id_kategori = ?";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    header('Location: TampilKategori.php');
} else {
    error_log("Error hapus kategori: " . mysqli_error($koneksi));
    header('Location: TampilKategori.php?error=1');
}

mysqli_stmt_close($stmt);
exit;
?>
