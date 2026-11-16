<?php

require_once '../layout/header.php';
if ($_SESSION['Role'] != 'admin') { header("Location: ../../index.php"); exit; }
?>
<h2>Dashboard Administrator</h2>
<p>Halo, <strong><?= $_SESSION['NamaLengkap'] ?></strong>.</p>
<ul>
    <li><a href="registrasi.php">Buat Akun Penjual / User</a></li>
    <li><a href="laporan.php">Cek Semua Transaksi</a></li>
</ul>
<?php require_once '../layout/footer.php'; ?>
