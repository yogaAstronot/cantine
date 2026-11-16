<?php
require_once '../layout/header.php';
require_once '../../Backend/Transaksi.php';
if ($_SESSION['Role'] != 'admin') { header("Location: ../../index.php"); exit; }

$trx = new Transaksi();
$semua_transaksi = $trx->getSemuaTransaksiAdmin();
?>
<h2>Monitoring Semua Transaksi</h2>
<button onclick="window.print()">Cetak Laporan</button>
<br><br>

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr>
            <th>PenjualanID</th>
            <th>TanggalPenjualan</th>
            <th>Pembeli</th>
            <th>Rincian Barang & Penjual</th>
            <th>StatusPesanan</th>
            <th>KodeKupon</th>
            <th>PotonganHarga</th>
            <th>TotalHarga</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($semua_transaksi as $row): ?>
        <tr>
            <td>#<?= $row['PenjualanID'] ?></td>
            <td><?= $row['TanggalPenjualan'] ?></td>
            <td><?= $row['NamaPembeli'] ?? $row['NamaPelanggan'] ?? 'Umum' ?> (<?= $row['RolePembeli'] ?? '-' ?>)</td>
            <td><?= $row['RincianBarang'] ?></td>
            <td><?= strtoupper($row['StatusPesanan']) ?></td>
            <td><?= $row['KodeKupon'] ?? '-' ?></td>
            <td>Rp <?= number_format($row['PotonganHarga'], 0, ',', '.') ?></td>
            <td>Rp <?= number_format($row['TotalHarga'], 0, ',', '.') ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php require_once '../layout/footer.php'; ?>