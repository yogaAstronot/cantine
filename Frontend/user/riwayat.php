<?php
require_once '../layout/header.php';
require_once '../../Backend/Transaksi.php';
if (!in_array($_SESSION['Role'], ['guru', 'siswa'])) { header("Location: ../../index.php"); exit; }

$trx             = new Transaksi();
$riwayat_belanja = $trx->getRiwayatPembeli($_SESSION['UserID']);
?>
<h3>Riwayat Belanja Saya</h3>
<?php if (isset($_SESSION['sukses'])): ?>
    <p style="color: green; font-weight: bold;"><?= $_SESSION['sukses']; unset($_SESSION['sukses']); ?></p>
<?php endif; ?>

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tanggal</th>
            <th>Kelas</th>
            <th>Catatan</th>
            <th>Metode & Status Bayar</th>
            <th>Status Pesanan</th>
            <th>Kupon</th>
            <th>Potongan</th>
            <th>Total Harga</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($riwayat_belanja as $r): ?>
        <tr>
            <td>#<?= $r['PenjualanID'] ?></td>
            <td><?= $r['TanggalPenjualan'] ?></td>
            <td><?= htmlspecialchars($r['NamaKelas'] ?? 'Umum / Guru') ?></td>
            <td><?= !empty($r['CatatanPembeli']) ? htmlspecialchars($r['CatatanPembeli']) : '-' ?></td>
            <td>
                <strong><?= strtoupper($r['MetodePembayaran']) ?></strong><br>
                <small>(<?= str_replace('_', ' ', strtoupper($r['StatusPembayaran'])) ?>)</small>
            </td>
            <td><?= strtoupper($r['StatusPesanan']) ?></td>
            <td><?= $r['KodeKupon'] ?? '-' ?></td>
            <td>Rp <?= number_format($r['PotonganHarga'], 0, ',', '.') ?></td>
            <td>Rp <?= number_format($r['TotalHarga'], 0, ',', '.') ?></td>
            <td>
                <?php if ($r['MetodePembayaran'] === 'qris' && $r['StatusPembayaran'] === 'belum_bayar' && $r['StatusPesanan'] !== 'dibatalkan'): ?>
                    <a href="bayar_qris.php?id=<?= $r['PenjualanID'] ?>"><strong>Bayar QRIS</strong></a>
                <?php elseif (!empty($r['BuktiPembayaran'])): ?>
                    <a href="../../Assets/bukti_pembayaran/<?= htmlspecialchars($r['BuktiPembayaran']) ?>" target="_blank">Lihat Bukti</a>
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php require_once '../layout/footer.php'; ?>