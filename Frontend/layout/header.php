<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['UserID'], $_SESSION['Role'])) {
    header("Location: ../../Auth/logout.php");
    exit;
}
$Role = $_SESSION['Role'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Kasir</title>
</head>
<body>
<nav>
    <strong>KASIR DIGITAL</strong> |
    <?php if ($Role == 'admin'): ?>
        <a href="../admin/dashboard.php">Dashboard</a> |
        <a href="../admin/registrasi.php">Buat Akun</a> |
        <a href="../admin/laporan.php">Semua Transaksi</a> |
    <?php elseif ($Role == 'penjual'): ?>
        <a href="../penjual/dashboard.php">Dashboard</a> |
        <a href="../penjual/produk.php">Produk Saya</a> |
        <a href="../penjual/kupon.php">Kupon Diskon</a> |
        <a href="../penjual/pesanan.php">Pesanan Masuk</a> |
        <a href="../penjual/profil.php">Qris Code</a>
    <?php else: ?>
        <a href="../user/katalog.php">Belanja Produk</a> |
        <a href="../user/riwayat.php">Riwayat Belanja</a> |
    <?php endif; ?>
    <span><?= $_SESSION['NamaLengkap'] ?> (<?= strtoupper($Role) ?>)</span> |
    <a href="../../Auth/logout.php">Logout</a>
</nav>
<hr>