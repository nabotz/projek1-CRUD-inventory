<?php
// Sidebar template for all pages
// Usage: include this file after starting session and getting user info
?>
<aside class="sidebar">
    <div class="logo">
        <span class="logo-text">Sistem Manajemen Inventori</span>
    </div>

    <ul class="nav-menu">
        <li><a href="<?= $base_url ?>menu.php" class="<?= $current_page == 'dashboard' ? 'active' : '' ?>">
                <img src="<?= $base_url ?>includes/images/dashboard.png" class="nav-icon" alt=""> Dashboard</a></li>
        <li><a href="<?= $base_url ?>stok_masuk.php" class="<?= $current_page == 'stok_masuk' ? 'active' : '' ?>">
                <img src="<?= $base_url ?>includes/images/transaksibaru.png" class="nav-icon" alt=""> Stok Masuk</a>
        </li>
        <li><a href="<?= $base_url ?>supplier/TampilSupplier.php"
                class="<?= $current_page == 'supplier' ? 'active' : '' ?>">
                <img src="<?= $base_url ?>includes/images/penyewa.png" class="nav-icon" alt=""> Supplier</a></li>
        <li><a href="<?= $base_url ?>produk/TampilProduk.php" class="<?= $current_page == 'produk' ? 'active' : '' ?>">
                <img src="<?= $base_url ?>includes/images/kamar.png" class="nav-icon" alt=""> Produk</a></li>
        <li><a href="<?= $base_url ?>kategori/TampilKategori.php"
                class="<?= $current_page == 'kategori' ? 'active' : '' ?>">
                <img src="<?= $base_url ?>includes/images/tipekamar.png" class="nav-icon" alt=""> Kategori</a></li>
        <li><a href="<?= $base_url ?>riwayat_stok/TampilRiwayatStok.php"
                class="<?= $current_page == 'riwayat_stok' ? 'active' : '' ?>">
                <img src="<?= $base_url ?>includes/images/transaksi.png" class="nav-icon" alt=""> Riwayat Stok</a></li>
        <li><a href="<?= $base_url ?>user/TampilUser.php" class="<?= $current_page == 'user' ? 'active' : '' ?>">
                <img src="<?= $base_url ?>includes/images/usericon.png" class="nav-icon" alt=""> Kelola User</a></li>
    </ul>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar">
                <?php if (!empty($_SESSION['foto'])): ?>
                    <img src="<?= $base_url ?>user/uploads/<?= htmlspecialchars($_SESSION['foto']) ?>" alt="Avatar"
                        style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                <?php else: ?>
                    👤
                <?php endif; ?>
            </div>
            <div class="user-info">
                <div class="user-name">
                    <?= htmlspecialchars($_SESSION['nama'] ?? 'User') ?>
                </div>
                <div class="user-role">Admin</div>
            </div>
            <a href="<?= $base_url ?>logout.php" class="logout-link" title="Logout"><img
                    src="<?= $base_url ?>includes/images/logout.png" alt="Logout"
                    style="width: 18px; height: 18px;"></a>
        </div>
    </div>
</aside>

<style>
    .nav-icon {
        width: 20px;
        height: 20px;
        object-fit: contain;
        margin-right: 12px;
        vertical-align: middle;
    }
</style>
