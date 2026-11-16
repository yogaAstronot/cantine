<?php
require_once '../layout/header.php';
require_once '../../Backend/Transaksi.php';
if (!in_array($_SESSION['Role'], ['guru', 'siswa'])) { header("Location: ../../index.php"); exit; }

$trx             = new Transaksi();
$riwayat_belanja = $trx->getRiwayatPembeli($_SESSION['UserID']);
?>
<h3>Riwayat Belanja Saya</h3>
<?php if (isset($_SESSION['sukses'])): ?>
    <p><?= $_SESSION['sukses']; unset($_SESSION['sukses']); ?></p>
<?php endif; ?>

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr>
            <th>PenjualanID</th>
            <th>TanggalPenjualan</th>
            <th>StatusPesanan</th>
            <th>KodeKupon</th>
            <th>PotonganHarga</th>
            <th>TotalHarga</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($riwayat_belanja as $r): ?>
        <tr>
            <td>#<?= $r['PenjualanID'] ?></td>
            <td><?= $r['TanggalPenjualan'] ?></td>
            <td><?= strtoupper($r['StatusPesanan']) ?></td>
            <td><?= $r['KodeKupon'] ?? '-' ?></td>
            <td>Rp <?= number_format($r['PotonganHarga'], 0, ',', '.') ?></td>
            <td>Rp <?= number_format($r['TotalHarga'], 0, ',', '.') ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php require_once '../layout/footer.php'; ?>