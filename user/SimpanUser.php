<?php
require_once '../auth.php';
include "../koneksi.php";

/* ================== FUNGSI SANITASI ================== */
function bersih($data)
{
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/* ================== AMBIL DATA FORM ================== */
$username = bersih($_POST['username'] ?? '');
$nama = bersih($_POST['nama'] ?? '');
$email = bersih($_POST['email'] ?? '');
$password = bersih($_POST['password'] ?? '');
$confirm_password = bersih($_POST['confirm_password'] ?? '');

/* ================== CEK USERNAME DUPLIKAT ================== */
$sql_cek = "SELECT id FROM users WHERE username = ?";
$stmt_cek = mysqli_prepare($koneksi, $sql_cek);
mysqli_stmt_bind_param($stmt_cek, "s", $username);
mysqli_stmt_execute($stmt_cek);
mysqli_stmt_store_result($stmt_cek);

if (mysqli_stmt_num_rows($stmt_cek) > 0) {
    mysqli_stmt_close($stmt_cek);
    header('Location: TambahUser.php?error=username');
    exit;
}
mysqli_stmt_close($stmt_cek);

/* ================== VALIDASI PASSWORD ================== */
if ($password !== $confirm_password) {
    header('Location: TambahUser.php?error=password');
    exit;
}

$hashed_password = password_hash($password, PASSWORD_BCRYPT);

/* ================== PROSES UPLOAD GAMBAR ================== */
if (!isset($_FILES['foto']) || $_FILES['foto']['error'] != 0) {
    header('Location: TambahUser.php?error=foto');
    exit;
}

$namaFile = $_FILES['foto']['name'];
$tmpFile = $_FILES['foto']['tmp_name'];
$ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
$allowedExt = ['jpg', 'jpeg', 'png', 'gif'];

if (!in_array($ext, $allowedExt)) {
    header('Location: TambahUser.php?error=format');
    exit;
}

$namaFotoBaru = uniqid("foto_") . "." . $ext;
$folderUpload = "uploads/";

if (!file_exists($folderUpload)) {
    mkdir($folderUpload, 0777, true);
}

if (!move_uploaded_file($tmpFile, $folderUpload . $namaFotoBaru)) {
    header('Location: TambahUser.php?error=upload');
    exit;
}

/* ================== SIMPAN KE DATABASE ================== */
$sql = "INSERT INTO users (username, password, nama, email, foto) VALUES (?, ?, ?, ?, ?)";

$stmt = $koneksi->prepare($sql);
$stmt->bind_param("sssss", $username, $hashed_password, $nama, $email, $namaFotoBaru);

if ($stmt->execute()) {
    header('Location: TampilUser.php');
} else {
    error_log("Error insert user: " . mysqli_error($koneksi));
    header('Location: TampilUser.php?error=1');
}

$stmt->close();
$koneksi->close();
exit;
?>