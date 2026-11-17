<?php
session_start();
require_once __DIR__ . '/../Backend/Auth.php';
require_once __DIR__ . '/../Backend/Transaksi.php';

$auth = new Auth();
$auth->cekAkses(['guru', 'siswa']);

if (isset($_POST['checkout']) && !empty($_SESSION['keranjang'])) {
    $transaksiObj = new Transaksi();
    
    $PembeliUserID    = $_SESSION['UserID'];
    $PelangganID      = $_POST['PelangganID'] ?? 1;
    $KuponID          = $_SESSION['kupon']['KuponID'] ?? null;
    $PotonganHarga    = $_SESSION['kupon']['PotonganHarga'] ?? 0;
    
    $MetodePembayaran = $_POST['MetodePembayaran'] ?? 'cash';
    $KelasID          = !empty($_POST['KelasID']) ? $_POST['KelasID'] : null;
    $CatatanPembeli   = $_POST['CatatanPembeli'] ?? '';

    $PenjualanID = $transaksiObj->simpanTransaksi(
        $PembeliUserID, 
        $_SESSION['keranjang'], 
        $KuponID, 
        $PotonganHarga, 
        $PelangganID,
        $MetodePembayaran,
        $KelasID,
        $CatatanPembeli
    );

    if ($PenjualanID) {
        unset($_SESSION['keranjang'], $_SESSION['kupon']);

        if ($MetodePembayaran === 'qris') {
            header("Location: ../Frontend/user/bayar_qris.php?id=" . $PenjualanID);
            exit;
        }

        $_SESSION['sukses'] = "Pesanan #$PenjualanID berhasil dibuat! Silakan bayar cash di kantin.";
        header("Location: ../Frontend/user/riwayat.php");
        exit;
    } else {
        $_SESSION['error'] = "Gagal memproses transaksi.";
        header("Location: ../Frontend/user/katalog.php");
        exit;
    }
}
?>