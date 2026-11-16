<?php
session_start();
require_once __DIR__ . '/../Backend/Auth.php';
require_once __DIR__ . '/../Backend/Transaksi.php';

$auth = new Auth();
$auth->cekAkses(['penjual']);

if (isset($_GET['id']) && isset($_GET['status'])) {
    $trx = new Transaksi();
    $allowed_status = ['diproses', 'selesai', 'dibatalkan'];
    
    if (in_array($_GET['status'], $allowed_status)) {
        $trx->updateStatusPesanan($_GET['id'], $_GET['status']);
        $_SESSION['sukses'] = "Status pesanan #" . (int)$_GET['id'] . " berhasil diperbarui!";
    }
}

$redirect = ($_GET['from'] ?? '') === 'pesanan' ? 'pesanan.php' : 'dashboard.php';
header("Location: ../Frontend/penjual/" . $redirect);
exit;
?>