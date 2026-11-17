<?php
require_once '../layout/header.php'; 
require_once '../../Backend/Transaksi.php';

if (!in_array($_SESSION['Role'], ['guru', 'siswa'])) {
    header("Location: ../../index.php");
    exit;
}

$trx  = new Transaksi();
$id   = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$data = $trx->getDetailTransaksi($id);

if (!$data || $data['PembeliUserID'] != $_SESSION['UserID']) {
    echo "<script>alert('Transaksi tidak ditemukan!'); window.location='riwayat.php';</script>";
    exit;
}

$error = '';
if (isset($_POST['upload_bukti']) && isset($_FILES['bukti'])) {
    $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
    $ext         = strtolower(pathinfo($_FILES['bukti']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed_ext)) {
        $error = "Format file harus gambar (JPG, JPEG, PNG, atau WEBP).";
    } elseif ($_FILES['bukti']['error'] === 0) {
        $nama_file = 'bukti_' . $id . '_' . time() . '.' . $ext;
        $folder    = __DIR__ . '/../../Assets/bukti_pembayaran/';

        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        if (move_uploaded_file($_FILES['bukti']['tmp_name'], $folder . $nama_file)) {
            $trx->uploadBuktiPembayaran($id, $nama_file);
            $_SESSION['sukses'] = "Bukti pembayaran QRIS untuk pesanan #$id berhasil dikirim! Menunggu konfirmasi penjual.";
            header("Location: riwayat.php");
            exit;
        } else {
            $error = "Gagal meng-upload bukti pembayaran.";
        }
    } else {
        $error = "Terjadi kesalahan saat memilih file.";
    }
}
?>

<h3>Pembayaran QRIS - Pesanan #<?= $id ?></h3>

<?php if (!empty($error)): ?>
    <p style="color: red; font-weight: bold;"><?= $error ?></p>
<?php endif; ?>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Penjual</th>
        <td><?= htmlspecialchars($data['NamaPenjual'] ?? '-') ?></td>
    </tr>
    <tr>
        <th>Kelas</th>
        <td><?= htmlspecialchars($data['NamaKelas'] ?? 'Umum / Guru') ?></td>
    </tr>
    <tr>
        <th>Catatan</th>
        <td><?= !empty($data['CatatanPembeli']) ? htmlspecialchars($data['CatatanPembeli']) : '-' ?></td>
    </tr>
    <tr>
        <th>Total Tagihan</th>
        <td><strong style="font-size: 1.2em; color: green;">Rp <?= number_format($data['TotalHarga'], 0, ',', '.') ?></strong></td>
    </tr>
    <tr>
        <th>Status Pembayaran</th>
        <td><strong><?= str_replace('_', ' ', strtoupper($data['StatusPembayaran'])) ?></strong></td>
    </tr>
</table>

<br>

<?php if (!empty($data['GambarQRIS'])): ?>
    <p>Silakan scan kode QRIS milik <strong><?= htmlspecialchars($data['NamaPenjual']) ?></strong> di bawah ini:</p>
    <img src="../../Assets/qris_penjual/<?= htmlspecialchars($data['GambarQRIS']) ?>" alt="QRIS Penjual" width="250" style="border: 1px solid #000; padding: 6px;">
    <br><br>

    <?php if ($data['StatusPembayaran'] !== 'lunas'): ?>
        <form method="POST" enctype="multipart/form-data">
            <label><strong>Upload Bukti Pembayaran (Screenshot):</strong></label><br>
            <input type="file" name="bukti" accept="image/*" required><br><br>
            <button type="submit" name="upload_bukti">Kirim Bukti Pembayaran</button>
        </form>
    <?php else: ?>
        <p style="color: green; font-weight: bold;">Pembayaran ini sudah LUNAS.</p>
    <?php endif; ?>

<?php else: ?>
    <p style="color: red; font-weight: bold;">
        Penjual (<?= htmlspecialchars($data['NamaPenjual']) ?>) belum meng-upload kode QRIS. Silakan bayar secara Cash langsung di kantin.
    </p>
<?php endif; ?>

<br>
<a href="riwayat.php">&laquo; Kembali ke Riwayat Belanja</a>

<?php require_once '../layout/footer.php'; ?>