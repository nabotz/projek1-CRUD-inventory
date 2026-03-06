<?php
require_once '../auth.php';
include "../koneksi.php";

$base_url = '../';
$current_page = 'produk';

$kode = $_GET['id'] ?? '';
$row = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM produk WHERE kode_produk = '$kode'"));
if (!$row) {
    header('Location: TampilProduk.php');
    exit;
}

$kategori = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY nama_kategori");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>Edit Produk - Sistem Inventori</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>
    <div class="dashboard">
        <?php include '../includes/sidebar.php'; ?>

        <main class="main">
            <header class="header">
                <div>
                    <div class="breadcrumb">
                        <a href="TampilProduk.php">Produk</a>
                        <span>→</span>
                        <span>Edit</span>
                    </div>
                    <h1 class="page-title">Edit Data Produk</h1>
                </div>
            </header>

            <div class="content">
                <div class="card" style="max-width: 600px;">
                    <form action="SimpanKoreksiProduk.php" method="POST">
                        <input type="hidden" name="id" value="<?= $row['kode_produk'] ?>">

                        <div class="form-group">
                            <label class="form-label">Kode Produk</label>
                            <input type="text" name="kode_produk" class="form-control" readonly
                                value="<?= htmlspecialchars($row['kode_produk']) ?>"
                                style="background: #f1f5f9; cursor: not-allowed;">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kategori</label>
                            <select name="id_kategori" class="form-control" required>
                                <?php while ($k = mysqli_fetch_assoc($kategori)): ?>
                                    <option value="<?= $k['id_kategori'] ?>" <?= $k['id_kategori'] == $row['id_kategori'] ? 'selected' : '' ?>><?= htmlspecialchars($k['nama_kategori']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Lokasi</label>
                            <input type="text" name="lokasi" class="form-control" required maxlength="20"
                                value="<?= htmlspecialchars($row['lokasi']) ?>">
                        </div>

                        <div style="display: flex; gap: 12px; margin-top: 24px;">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <a href="TampilProduk.php" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
