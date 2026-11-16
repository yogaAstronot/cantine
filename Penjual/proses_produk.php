<?php
session_start();
require_once __DIR__ . '/../Backend/Auth.php';
require_once __DIR__ . '/../Backend/Produk.php';

$auth = new Auth();
$auth->cekAkses(['penjual']);

$produkObj = new Produk();
$PenjualID = $_SESSION['UserID'];

if (isset($_POST['tambah'])) {
    $produkObj->tambahProduk($PenjualID, $_POST['KategoriID'], $_POST['NamaProduk'], $_POST['Harga'], $_POST['Stok']);
    header("Location: ../Frontend/penjual/produk.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $produkObj->hapusProduk($_GET['hapus'], $PenjualID);
    header("Location: ../Frontend/penjual/produk.php");
    exit;
}
?>