<?php
require_once '../layout/header.php';
require_once '../../Backend/Produk.php';
if ($_SESSION['Role'] != 'penjual') { header("Location: ../../index.php"); exit; }

$produkObj     = new Produk();
$filter_kat    = $_GET['KategoriID'] ?? '';
$kategori_list = $produkObj->getAllKategori();
$produk_list   = $produkObj->getProduk($filter_kat, $_SESSION['UserID']);
?>
<h3>Tambah Produk Baru</h3>
<form action="../../Penjual/proses_produk.php" method="POST">
    <select name="KategoriID" required>
        <option value="">-- Pilih Kategori --</option>
        <?php foreach ($kategori_list as $k): ?>
            <option value="<?= $k['KategoriID'] ?>"><?= $k['NamaKategori'] ?></option>
        <?php endforeach; ?>
    </select>
    <input type="text" name="NamaProduk" placeholder="NamaProduk" required>
    <input type="number" name="Harga" placeholder="Harga" required>
    <input type="number" name="Stok" placeholder="Stok" required>
    <button type="submit" name="tambah">Tambah</button>
</form>

<hr>

<h3>Daftar Produk Saya</h3>
<form method="GET">
    <label>Filter Kategori:</label>
    <select name="KategoriID" onchange="this.form.submit()">
        <option value="">Semua Kategori</option>
        <?php foreach ($kategori_list as $k): ?>
            <option value="<?= $k['KategoriID'] ?>" <?= ($filter_kat == $k['KategoriID']) ? 'selected' : '' ?>><?= $k['NamaKategori'] ?></option>
        <?php endforeach; ?>
    </select>
</form>
<br>

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr>
            <th>ProdukID</th>
            <th>NamaKategori</th>
            <th>NamaProduk</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($produk_list as $p): ?>
        <tr>
            <td><?= $p['ProdukID'] ?></td>
            <td><?= $p['NamaKategori'] ?? 'Umum' ?></td>
            <td><?= $p['NamaProduk'] ?></td>
            <td>Rp <?= number_format($p['Harga'], 0, ',', '.') ?></td>
            <td><?= $p['Stok'] ?></td>
            <td>
                <a href="../../Penjual/proses_produk.php?hapus=<?= $p['ProdukID'] ?>" onclick="return confirm('Hapus produk?')">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php require_once '../layout/footer.php'; ?>