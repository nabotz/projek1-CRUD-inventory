<?php
require_once 'auth.php';
include 'koneksi.php';

// Query statistik overview
$stok_masuk_hari_ini = mysqli_fetch_row(mysqli_query(
    $koneksi,
    "SELECT COUNT(*) FROM transaksi_stok WHERE DATE(tgl_transaksi) = CURDATE()"
))[0];
$stok_keluar_hari_ini = mysqli_fetch_row(mysqli_query(
    $koneksi,
    "SELECT COUNT(*) FROM transaksi_stok WHERE DATE(tgl_kadaluarsa) = CURDATE()"
))[0];
$total_transaksi = mysqli_fetch_row(mysqli_query(
    $koneksi,
    "SELECT COUNT(*) FROM transaksi_stok"
))[0];
$total_produk = mysqli_fetch_row(mysqli_query(
    $koneksi,
    "SELECT COUNT(*) FROM produk"
))[0];
$total_supplier = mysqli_fetch_row(mysqli_query(
    $koneksi,
    "SELECT COUNT(*) FROM supplier"
))[0];

// Query kategori dengan jumlah produk dan harga
$kategori_list = mysqli_query(
    $koneksi,
    "SELECT k.*, COUNT(p.kode_produk) as jumlah_produk
     FROM kategori k
     LEFT JOIN produk p ON k.id_kategori = p.id_kategori
     GROUP BY k.id_kategori
     ORDER BY k.nama_kategori"
);

// Data nilai stok per bulan untuk chart (12 bulan terakhir)
$nilai_chart = mysqli_query(
    $koneksi,
    "SELECT DATE_FORMAT(tgl_transaksi, '%Y-%m') as periode, MONTH(tgl_transaksi) as bulan, YEAR(tgl_transaksi) as tahun, COALESCE(SUM(total_nilai),0) as total
     FROM transaksi_stok
     WHERE tgl_transaksi >= DATE_SUB(CURDATE(), INTERVAL 11 MONTH)
     GROUP BY YEAR(tgl_transaksi), MONTH(tgl_transaksi)
     ORDER BY YEAR(tgl_transaksi), MONTH(tgl_transaksi)"
);

$bulan_nama = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
$chart_labels = [];
$chart_data = [];
while ($row = mysqli_fetch_assoc($nilai_chart)) {
    $chart_labels[] = $bulan_nama[$row['bulan']] . ' ' . $row['tahun'];
    $chart_data[] = (int) $row['total'];
}

// 5 Transaksi terbaru
$transaksi_terbaru = mysqli_query(
    $koneksi,
    "SELECT ts.*, s.nama FROM transaksi_stok ts
     JOIN supplier s ON ts.id_supplier = s.id_supplier
     ORDER BY ts.id_transaksi DESC LIMIT 5"
);

// Total nilai masuk bulan ini
$nilai_bulan = mysqli_fetch_row(mysqli_query(
    $koneksi,
    "SELECT COALESCE(SUM(total_nilai),0) FROM transaksi_stok
     WHERE MONTH(tgl_transaksi) = MONTH(CURDATE())"
))[0];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>Dashboard - Sistem Inventori</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/menu.css">
    <script src="js/chart.js"></script>
</head>

<body>
    <div class="dashboard">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <span class="logo-text">Sistem Manajemen Inventori</span>
            </div>

            <ul class="nav-menu">
                <li><a href="menu.php" class="active"><img src="includes/images/dashboard.png" class="nav-icon" alt="">
                        Dashboard</a></li>
                <li><a href="stok_masuk.php"><img src="includes/images/transaksibaru.png" class="nav-icon" alt="">
                        Stok Masuk</a></li>
                <li><a href="supplier/TampilSupplier.php"><img src="includes/images/penyewa.png" class="nav-icon" alt="">
                        Supplier</a></li>
                <li><a href="produk/TampilProduk.php"><img src="includes/images/kamar.png" class="nav-icon" alt="">
                        Produk</a></li>
                <li><a href="kategori/TampilKategori.php"><img src="includes/images/tipekamar.png" class="nav-icon"
                            alt=""> Kategori</a></li>
                <li><a href="riwayat_stok/TampilRiwayatStok.php"><img src="includes/images/transaksi.png" class="nav-icon"
                            alt=""> Riwayat Stok</a></li>
                <li><a href="user/TampilUser.php"><img src="includes/images/usericon.png" class="nav-icon" alt="">
                        Kelola User</a></li>
            </ul>

            <div class="sidebar-footer">
                <div class="user-card">
                    <div class="user-avatar">
                        <?php if (!empty($_SESSION['foto'])): ?>
                            <img src="user/uploads/<?= htmlspecialchars($_SESSION['foto']) ?>" alt="Avatar"
                                style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                        <?php else: ?>
                            👤
                        <?php endif; ?>
                    </div>
                    <div class="user-info">
                        <div class="user-name"><?= htmlspecialchars($_SESSION['nama']) ?></div>
                        <div class="user-role">Admin</div>
                    </div>
                    <a href="logout.php" class="logout-link" title="Logout"><img src="includes/images/logout.png"
                            alt="Logout" style="width: 18px; height: 18px;"></a>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main">
            <!-- Header -->
            <header class="header">
                <div class="header-right" style="margin-left: auto;">
                    <span class="date-display"><?= date('l, d F Y') ?></span>
                    <a href="stok_masuk.php" class="btn-primary">
                        <span>+</span> Stok Masuk
                    </a>
                </div>
            </header>

            <!-- Content -->
            <div class="content">
                <!-- Overview Section -->
                <h2 class="section-title">Overview</h2>
                <div class="overview-grid">
                    <div class="overview-card">
                        <div class="overview-label">Stok Masuk Hari Ini</div>
                        <div class="overview-value"><?= $stok_masuk_hari_ini ?></div>
                    </div>
                    <div class="overview-card">
                        <div class="overview-label">Stok Keluar Hari Ini</div>
                        <div class="overview-value"><?= $stok_keluar_hari_ini ?></div>
                    </div>
                    <div class="overview-card">
                        <div class="overview-label">Total Transaksi</div>
                        <div class="overview-value"><?= $total_transaksi ?></div>
                    </div>
                    <div class="overview-card">
                        <div class="overview-label">Total Produk</div>
                        <div class="overview-value"><?= $total_produk ?></div>
                    </div>
                    <div class="overview-card">
                        <div class="overview-label">Total Supplier</div>
                        <div class="overview-value"><?= $total_supplier ?></div>
                    </div>
                </div>

                <!-- Kategori Section -->
                <h2 class="section-title">Kategori</h2>
                <div class="rooms-grid">
                    <?php mysqli_data_seek($kategori_list, 0); ?>
                    <?php while ($kat = mysqli_fetch_assoc($kategori_list)): ?>
                        <div class="room-card">
                            <span class="room-badge"><?= $kat['jumlah_produk'] ?> Produk</span>
                            <span class="room-menu">⋮</span>
                            <div class="room-type"><?= htmlspecialchars($kat['nama_kategori']) ?></div>
                            <div class="room-count"><?= $kat['jumlah_produk'] ?><span> / <?= $kat['stok_minimum'] ?>
                                    min</span></div>
                            <div class="room-price">Rp <?= number_format($kat['harga_satuan'], 0, ',', '.') ?><span> /
                                    unit</span></div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <!-- Stats & Revenue Row -->
                <div class="grid-2">
                    <!-- Statistics -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Statistik</h3>
                        </div>
                        <div class="stats-grid">
                            <div class="stat-group">
                                <h4>Data Produk</h4>
                                <div class="stat-row">
                                    <span class="stat-label">Total Produk</span>
                                    <span class="stat-value"><?= $total_produk ?></span>
                                </div>
                                <div class="stat-row">
                                    <span class="stat-label">Kategori</span>
                                    <span class="stat-value"><?= mysqli_num_rows($kategori_list) ?></span>
                                </div>
                                <div class="stat-row">
                                    <span class="stat-label">Total Supplier</span>
                                    <span class="stat-value"><?= $total_supplier ?></span>
                                </div>
                            </div>
                            <div class="stat-group">
                                <h4>Data Transaksi</h4>
                                <div class="stat-row">
                                    <span class="stat-label">Total Transaksi</span>
                                    <span class="stat-value"><?= $total_transaksi ?></span>
                                </div>
                                <div class="stat-row">
                                    <span class="stat-label">Stok Masuk Hari Ini</span>
                                    <span class="stat-value"><?= $stok_masuk_hari_ini ?></span>
                                </div>
                                <div class="stat-row">
                                    <span class="stat-label">Stok Keluar Hari Ini</span>
                                    <span class="stat-value"><?= $stok_keluar_hari_ini ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Nilai -->
                    <div class="card revenue-card">
                        <div class="card-header" style="justify-content: center;">
                            <h3 class="card-title">Total Nilai Masuk Bulan Ini</h3>
                        </div>
                        <div class="revenue-amount">Rp <?= number_format($nilai_bulan, 0, ',', '.') ?></div>
                        <div class="revenue-label"><?= date('F Y') ?></div>
                    </div>
                </div>

                <!-- Chart & Recent Transactions Row -->
                <div class="grid-2">
                    <!-- Chart -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Statistik Nilai Stok Masuk</h3>
                        </div>
                        <div class="chart-container">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>

                    <!-- Recent Transactions -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Transaksi Terbaru</h3>
                            <a href="riwayat_stok/TampilRiwayatStok.php" class="card-link">Lihat Semua →</a>
                        </div>
                        <?php if (mysqli_num_rows($transaksi_terbaru) > 0): ?>
                            <?php while ($t = mysqli_fetch_assoc($transaksi_terbaru)): ?>
                                <div class="transaction-item">
                                    <div class="transaction-info">
                                        <div class="transaction-avatar">👤</div>
                                        <div>
                                            <div class="transaction-name"><?= htmlspecialchars($t['nama']) ?></div>
                                            <div class="transaction-amount">Rp
                                                <?= number_format($t['total_nilai'], 0, ',', '.') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="transaction-room"><?= $t['kode_produk'] ?></div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="empty-state">Belum ada transaksi</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($chart_labels) ?>,
                datasets: [{
                    label: 'Nilai Stok Masuk',
                    data: <?= json_encode($chart_data) ?>,
                    backgroundColor: '#2563eb',
                    borderRadius: 8,
                    barThickness: 32
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { display: false }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            callback: function (value) {
                                if (value >= 1000000) return (value / 1000000) + ' jt';
                                if (value >= 1000) return (value / 1000) + ' rb';
                                return value;
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>
