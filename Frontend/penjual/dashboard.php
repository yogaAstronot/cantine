<?php
require_once '../layout/header.php';
require_once '../../Backend/Transaksi.php';
if ($_SESSION['Role'] != 'penjual') { header("Location: ../../index.php"); exit; }

$trx = new Transaksi();
$PenjualID     = $_SESSION['UserID'];
$ringkasan     = $trx->getRingkasanPenjual($PenjualID);
$pesanan_masuk = $trx->getPesananPenjual($PenjualID);
?>
<h2>Dashboard Penjual</h2>
<p>Halo, <strong><?= $_SESSION['NamaLengkap'] ?></strong></p>

<?php if (isset($_SESSION['sukses'])): ?>
    <p><?= $_SESSION['sukses']; unset($_SESSION['sukses']); ?></p>
<?php endif; ?>

<ul>
    <li>Total Pendapatan: <strong>Rp <?= number_format($ringkasan['omzet'], 0, ',', '.') ?></strong></li>
    <li>Pesanan Perlu Diproses: <strong><?= $ringkasan['pending'] ?></strong></li>
    <li>Produk Stok Menipis (&le; 5): <strong><?= $ringkasan['stok_menipis'] ?></strong></li>
</ul>

<h3>Antrean Pesanan Masuk</h3>
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
        <?php if (empty($pesanan_masuk)): ?>
            <tr><td colspan="8">Belum ada pesanan masuk.</td></tr>
        <?php else: ?>
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
                        <a href="../../Penjual/proses_pesanan.php?id=<?= $row['PenjualanID'] ?>&status=diproses">Terima</a> |
                        <a href="../../Penjual/proses_pesanan.php?id=<?= $row['PenjualanID'] ?>&status=dibatalkan" onclick="return confirm('Tolak pesanan ini?')">Tolak</a>
                    <?php elseif ($row['StatusPesanan'] == 'diproses'): ?>
                        <a href="../../Penjual/proses_pesanan.php?id=<?= $row['PenjualanID'] ?>&status=selesai">Selesai</a>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
<?php require_once '../layout/footer.php'; ?>