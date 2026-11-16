<?php
session_start();
require_once __DIR__ . '/../Backend/Auth.php';
require_once __DIR__ . '/../Backend/Transaksi.php';

$auth = new Auth();
$auth->cekAkses(['guru', 'siswa']);

if (isset($_POST['checkout']) && !empty($_SESSION['keranjang'])) {
    $transaksiObj = new Transaksi();
    
    $PembeliUserID = $_SESSION['UserID'];
    $PelangganID   = $_POST['PelangganID'] ?? 1;
    $KuponID       = $_SESSION['kupon']['KuponID'] ?? null;
    $PotonganHarga = $_SESSION['kupon']['PotonganHarga'] ?? 0;

    $PenjualanID = $transaksiObj->simpanTransaksi($PembeliUserID, $_SESSION['keranjang'], $KuponID, $PotonganHarga, $PelangganID);

    if ($PenjualanID) {
        unset($_SESSION['keranjang'], $_SESSION['kupon']);
        $_SESSION['sukses'] = "Transaksi #$PenjualanID berhasil dibayar!";
        header("Location: ../Frontend/user/riwayat.php");
        exit;
    } else {
        $_SESSION['error'] = "Gagal memproses transaksi.";
        header("Location: ../Frontend/user/katalog.php");
        exit;
    }
}
?>