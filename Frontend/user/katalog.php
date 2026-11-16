<?php
require_once '../layout/header.php';
require_once '../../Backend/Produk.php';
require_once '../../Backend/Transaksi.php';
if (!in_array($_SESSION['Role'], ['guru', 'siswa'])) { header("Location: ../../index.php"); exit; }

$produkObj      = new Produk();
$trxObj         = new Transaksi();
$filter_kat     = $_GET['KategoriID'] ?? '';
$kategori_list  = $produkObj->getAllKategori();
$produk_list    = $produkObj->getProduk($filter_kat);
$pelanggan_list = $trxObj->getAllPelanggan();

$keranjang = $_SESSION['keranjang'] ?? [];
$kupon     = $_SESSION['kupon'] ?? null;

$total_belanja = 0;
foreach ($keranjang as $item) { $total_belanja += ($item['Harga'] * $item['JumlahProduk']); }
$PotonganHarga = $kupon['PotonganHarga'] ?? 0;
$TotalHarga    = max(0, $total_belanja - $PotonganHarga);
?>
<h3>Katalog Produk</h3>
<form method="GET">
    <label>Kategori:</label>
    <select name="KategoriID" onchange="this.form.submit()">
        <option value="">-- Semua Kategori --</option>
        <?php foreach ($kategori_list as $k): ?>
            <option value="<?= $k['KategoriID'] ?>" <?= ($filter_kat == $k['KategoriID']) ? 'selected' : '' ?>><?= $k['NamaKategori'] ?></option>
        <?php endforeach; ?>
    </select>
</form>
<br>

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr>
            <th>Kategori</th>
            <th>NamaProduk</th>
            <th>Penjual</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Beli</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($produk_list as $p): ?>
        <tr>
            <td><?= $p['NamaKategori'] ?? 'Umum' ?></td>
            <td><?= $p['NamaProduk'] ?></td>
            <td><?= $p['NamaPenjual'] ?? '-' ?></td>
            <td>Rp <?= number_format($p['Harga'], 0, ',', '.') ?></td>
            <td><?= $p['Stok'] ?></td>
            <td>
                <form action="../../User/proses_keranjang.php" method="POST">
                    <input type="hidden" name="ProdukID" value="<?= $p['ProdukID'] ?>">
                    <input type="number" name="JumlahProduk" value="1" min="1" max="<?= $p['Stok'] ?>" <?= $p['Stok'] <= 0 ? 'disabled' : '' ?>>
                    <button type="submit" name="tambah_keranjang" <?= $p['Stok'] <= 0 ? 'disabled' : '' ?>>+ Keranjang</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<hr>

<h3>Keranjang Belanja (<a href="../../User/proses_keranjang.php?reset=1">Kosongkan</a>)</h3>
<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr>
            <th>NamaProduk</th>
            <th>Harga</th>
            <th>JumlahProduk</th>
            <th>Subtotal</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($keranjang as $id => $item): ?>
        <tr>
            <td><?= $item['NamaProduk'] ?></td>
            <td>Rp <?= number_format($item['Harga'], 0, ',', '.') ?></td>
            <td><?= $item['JumlahProduk'] ?></td>
            <td>Rp <?= number_format($item['Harga'] * $item['JumlahProduk'], 0, ',', '.') ?></td>
            <td><a href="../../User/proses_keranjang.php?hapus_item=<?= $id ?>">Hapus</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<br>

<?php if (isset($_SESSION['pesan_kupon'])): ?>
    <p><?= $_SESSION['pesan_kupon']; unset($_SESSION['pesan_kupon']); ?></p>
<?php endif; ?>
<form action="../../User/proses_keranjang.php" method="POST">
    <input type="text" name="KodeKupon" placeholder="KodeKupon" value="<?= $kupon['KodeKupon'] ?? '' ?>">
    <button type="submit" name="pakai_kupon">Gunakan Kupon</button>
</form>

<p>
    Subtotal: <strong>Rp <?= number_format($total_belanja, 0, ',', '.') ?></strong><br>
    PotonganHarga: <strong>- Rp <?= number_format($PotonganHarga, 0, ',', '.') ?></strong><br>
    TotalHarga: <strong>Rp <?= number_format($TotalHarga, 0, ',', '.') ?></strong>
</p>

<form action="../../User/proses_checkout.php" method="POST">
    <label>PelangganID:</label>
    <select name="PelangganID">
        <?php foreach ($pelanggan_list as $plg): ?>
            <option value="<?= $plg['PelangganID'] ?>"><?= $plg['NamaPelanggan'] ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit" name="checkout" <?= empty($keranjang) ? 'disabled' : '' ?>>Bayar Sekarang</button>
</form>
<?php require_once '../layout/footer.php'; ?>