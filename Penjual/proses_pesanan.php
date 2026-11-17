<?php
session_start();
require_once __DIR__ . '/../Backend/Auth.php';
require_once __DIR__ . '/../Backend/Transaksi.php';

$auth = new Auth();
$auth->cekAkses(['penjual']);

$trx = new Transaksi();
$id  = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    if (isset($_GET['status'])) {
        $allowed_status = ['diproses', 'selesai', 'dibatalkan'];
        if (in_array($_GET['status'], $allowed_status)) {
            $trx->updateStatusPesanan($id, $_GET['status']);
            $_SESSION['sukses'] = "Status pesanan #$id berhasil diperbarui!";
        }
    }

    if (isset($_GET['bayar'])) {
        $allowed_bayar = ['lunas', 'belum_bayar'];
        if (in_array($_GET['bayar'], $allowed_bayar)) {
            $trx->updateStatusPembayaran($id, $_GET['bayar']);
            $_SESSION['sukses'] = "Status pembayaran pesanan #$id diperbarui menjadi " . strtoupper($_GET['bayar']) . "!";
        }
    }
}

$redirect = ($_GET['from'] ?? '') === 'pesanan' ? 'pesanan.php' : 'dashboard.php';
header("Location: ../Frontend/penjual/" . $redirect);
exit;
?>