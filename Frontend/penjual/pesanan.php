<?php
require_once '../layout/header.php';
require_once '../../Backend/Transaksi.php';
if ($_SESSION['Role'] != 'penjual') { header("Location: ../../index.php"); exit; }

$trx           = new Transaksi();
$pesanan_masuk = $trx->getPesananPenjual($_SESSION['UserID']);
?>
<h3>Daftar Pesanan Masuk</h3>
<?php if (isset($_SESSION['sukses'])): ?>
    <p style="color: green; font-weight: bold;"><?= $_SESSION['sukses']; unset($_SESSION['sukses']); ?></p>
<?php endif; ?>

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tanggal</th>
            <th>Pembeli & Kelas</th>
            <th>Produk & Catatan</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
            <th>Pembayaran</th>
            <th>Status Pesanan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pesanan_masuk as $row): ?>
        <tr>
            <td>#<?= $row['PenjualanID'] ?></td>
            <td><?= $row['TanggalPenjualan'] ?></td>
            <td>
                <strong><?= htmlspecialchars($row['NamaPembeli'] ?? 'Umum') ?></strong> (<?= $row['RolePembeli'] ?? '-' ?>)<br>
                <small>Kelas: <strong><?= htmlspecialchars($row['NamaKelas'] ?? 'Umum / Guru') ?></strong></small>
            </td>
            <td>
                <strong><?= htmlspecialchars($row['NamaProduk']) ?></strong><br>
                <?php if (!empty($row['CatatanPembeli'])): ?>
                    <small style="color: #d9534f;">Catatan: "<?= htmlspecialchars($row['CatatanPembeli']) ?>"</small>
                <?php else: ?>
                    <small style="color: gray;">Tidak ada catatan</small>
                <?php endif; ?>
            </td>
            <td><?= $row['JumlahProduk'] ?></td>
            <td>Rp <?= number_format($row['Subtotal'], 0, ',', '.') ?></td>
            <td>
                <strong><?= strtoupper($row['MetodePembayaran']) ?></strong><br>
                <small>(<?= str_replace('_', ' ', strtoupper($row['StatusPembayaran'])) ?>)</small>

                <?php if ($row['MetodePembayaran'] === 'qris'): ?>
                    <br>
                    <?php if (!empty($row['BuktiPembayaran'])): ?>
                        <a href="../../Assets/bukti_pembayaran/<?= htmlspecialchars($row['BuktiPembayaran']) ?>" target="_blank">Lihat Bukti</a>
                        <?php if ($row['StatusPembayaran'] !== 'lunas'): ?>
                            | <a href="../../Penjual/proses_pesanan.php?id=<?= $row['PenjualanID'] ?>&bayar=lunas&from=pesanan" onclick="return confirm('Konfirmasi pembayaran QRIS ini sudah lunas?')"><strong>Konfirmasi Lunas</strong></a>
                        <?php endif; ?>
                    <?php else: ?>
                        <small style="color: orange;">Belum upload bukti</small>
                    <?php endif; ?>
                <?php endif; ?>
            </td>
            <td><?= strtoupper($row['StatusPesanan']) ?></td>
            <td>
                <?php if ($row['StatusPesanan'] == 'menunggu'): ?>
                    <a href="../../Penjual/proses_pesanan.php?id=<?= $row['PenjualanID'] ?>&status=diproses&from=pesanan">Terima</a> | 
                    <a href="../../Penjual/proses_pesanan.php?id=<?= $row['PenjualanID'] ?>&status=dibatalkan&from=pesanan" onclick="return confirm('Batalkan pesanan ini?')">Batal</a>
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