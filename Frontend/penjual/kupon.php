<?php
require_once '../layout/header.php';
require_once '../../Backend/Transaksi.php';
if ($_SESSION['Role'] != 'penjual') { header("Location: ../../index.php"); exit; }

$trx        = new Transaksi();
$kupon_list = $trx->getAllKupon();
?>
<h3>Buat Kupon Diskon</h3>
<form action="../../Penjual/proses_kupon.php" method="POST">
    <input type="text" name="KodeKupon" placeholder="KodeKupon" required>
    <select name="TipeDiskon">
        <option value="persen">persen</option>
        <option value="nominal">nominal</option>
    </select>
    <input type="number" name="NilaiDiskon" placeholder="NilaiDiskon" required>
    <input type="number" name="MinimalBelanja" placeholder="MinimalBelanja" required>
    <input type="number" name="Kuota" placeholder="Kuota" required>
    <label>Mulai:</label> <input type="date" name="TanggalMulai" required>
    <label>Berakhir:</label> <input type="date" name="TanggalBerakhir" required>
    <button type="submit" name="tambah_kupon">Simpan Kupon</button>
</form>

<hr>

<h3>Daftar Kupon</h3>
<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr>
            <th>KodeKupon</th>
            <th>TipeDiskon</th>
            <th>NilaiDiskon</th>
            <th>MinimalBelanja</th>
            <th>TanggalMulai</th>
            <th>TanggalBerakhir</th>
            <th>Kuota</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($kupon_list as $k): ?>
        <tr>
            <td><?= $k['KodeKupon'] ?></td>
            <td><?= $k['TipeDiskon'] ?></td>
            <td><?= $k['NilaiDiskon'] ?></td>
            <td>Rp <?= number_format($k['MinimalBelanja'], 0, ',', '.') ?></td>
            <td><?= $k['TanggalMulai'] ?></td>
            <td><?= $k['TanggalBerakhir'] ?></td>
            <td><?= $k['Kuota'] ?></td>
            <td><?= $k['Status'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php require_once '../layout/footer.php'; ?>