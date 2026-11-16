<?php
require_once '../layout/header.php';
require_once '../../Backend/Transaksi.php';
if ($_SESSION['Role'] != 'penjual') { header("Location: ../../index.php"); exit; }

$trx           = new Transaksi();
$pesanan_masuk = $trx->getPesananPenjual($_SESSION['UserID']);
?>
<h3>Daftar Pesanan Masuk</h3>
<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr>
            <th>PenjualanID</th>
            <th>TanggalPenjualan</th>
            <th>Pembeli</th>
            <th>NamaProduk</th>
            <th>JumlahProduk</th>
            <th>Subtotal</th>
            <th>StatusPesanan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pesanan_masuk as $row): ?>
        <tr>
            <td>#<?= $row['PenjualanID'] ?></td>
            <td><?= $row['TanggalPenjualan'] ?></td>
            <td><?= $row['NamaPembeli'] ?? 'Umum' ?> (<?= $row['RolePembeli'] ?? '-' ?>)</td>
            <td><?= $row['NamaProduk'] ?></td>
            <td><?= $row['JumlahProduk'] ?></td>
            <td>Rp <?= number_format($row['Subtotal'], 0, ',', '.') ?></td>
            <td><?= strtoupper($row['StatusPesanan']) ?></td>
            <td>
                <?php if ($row['StatusPesanan'] == 'menunggu'): ?>
                    <a href="../../Penjual/proses_pesanan.php?id=<?= $row['PenjualanID'] ?>&status=diproses&from=pesanan">Terima</a>
                <?php elseif ($row['StatusPesanan'] == 'diproses'): ?>
                    <a href="../../Penjual/proses_pesanan.php?id=<?= $row['PenjualanID'] ?>&status=selesai&from=pesanan">Selesai</a>
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php require_once '../layout/footer.php'; ?>