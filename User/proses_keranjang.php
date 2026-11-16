<?php
session_start();
require_once __DIR__ . '/../Backend/Auth.php';
require_once __DIR__ . '/../Backend/Produk.php';
require_once __DIR__ . '/../Backend/Transaksi.php';

$auth = new Auth();
$auth->cekAkses(['guru', 'siswa']);

$produkObj    = new Produk();
$transaksiObj = new Transaksi();

if (!isset($_SESSION['keranjang'])) $_SESSION['keranjang'] = [];
if (!isset($_SESSION['kupon'])) $_SESSION['kupon'] = null;

if (isset($_POST['tambah_keranjang'])) {
    $ProdukID     = (int)$_POST['ProdukID'];
    $JumlahProduk = (int)$_POST['JumlahProduk'];
    $produk       = $produkObj->getProdukById($ProdukID);

    if ($produk && $JumlahProduk > 0 && $JumlahProduk <= $produk['Stok']) {
        if (isset($_SESSION['keranjang'][$ProdukID])) {
            $_SESSION['keranjang'][$ProdukID]['JumlahProduk'] += $JumlahProduk;
        } else {
            $_SESSION['keranjang'][$ProdukID] = [
                'NamaProduk'   => $produk['NamaProduk'],
                'Harga'        => $produk['Harga'],
                'JumlahProduk' => $JumlahProduk
            ];
        }
    }
    header("Location: ../Frontend/user/katalog.php");
    exit;
}

if (isset($_GET['hapus_item'])) {
    $ProdukID = (int)$_GET['hapus_item'];
    unset($_SESSION['keranjang'][$ProdukID]);
    header("Location: ../Frontend/user/katalog.php");
    exit;
}

if (isset($_GET['reset'])) {
    unset($_SESSION['keranjang'], $_SESSION['kupon']);
    header("Location: ../Frontend/user/katalog.php");
    exit;
}

if (isset($_POST['pakai_kupon'])) {
    $total_belanja = 0;
    foreach ($_SESSION['keranjang'] as $item) {
        $total_belanja += ($item['Harga'] * $item['JumlahProduk']);
    }

    $hasil = $transaksiObj->validasiKupon($_POST['KodeKupon'], $total_belanja);
    if ($hasil['status']) {
        $_SESSION['kupon'] = [
            'KuponID'       => $hasil['KuponID'],
            'KodeKupon'     => $hasil['KodeKupon'],
            'PotonganHarga' => $hasil['PotonganHarga']
        ];
        $_SESSION['pesan_kupon'] = $hasil['pesan'];
    } else {
        $_SESSION['kupon'] = null;
        $_SESSION['pesan_kupon'] = $hasil['pesan'];
    }
    header("Location: ../Frontend/user/katalog.php");
    exit;
}
?>