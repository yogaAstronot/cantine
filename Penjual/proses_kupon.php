<?php
session_start();
require_once __DIR__ . '/../Backend/Auth.php';
require_once __DIR__ . '/../Backend/Transaksi.php';

$auth = new Auth();
$auth->cekAkses(['penjual']);

$trxObj = new Transaksi();

if (isset($_POST['tambah_kupon'])) {
    $trxObj->tambahKupon(
        $_POST['KodeKupon'], 
        $_POST['TipeDiskon'], 
        $_POST['NilaiDiskon'], 
        $_POST['MinimalBelanja'], 
        $_POST['TanggalMulai'], 
        $_POST['TanggalBerakhir'], 
        $_POST['Kuota']
    );
    header("Location: ../Frontend/penjual/kupon.php");
    exit;
}
?>