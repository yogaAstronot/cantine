<?php
session_start();
require_once __DIR__ . '/../Backend/Auth.php';
require_once __DIR__ . '/../Config/koneksi.php';

$auth = new Auth();
$auth->cekAkses(['penjual']);

if (isset($_POST['simpan_qris']) && isset($_FILES['qris'])) {
    $db          = new koneksi();
    $penjual_id  = (int)$_SESSION['UserID'];
    $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
    $ext         = strtolower(pathinfo($_FILES['qris']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed_ext)) {
        $_SESSION['error'] = "Format gambar QRIS harus JPG, JPEG, PNG, atau WEBP.";
    } elseif ($_FILES['qris']['error'] === 0) {
        $nama_file = 'qris_' . $penjual_id . '_' . time() . '.' . $ext;
        $folder    = __DIR__ . '/../Assets/qris_penjual/';

        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $cek_lama = $db->conn->query("SELECT GambarQRIS FROM user WHERE UserID = $penjual_id")->fetch_assoc();
        if (!empty($cek_lama['GambarQRIS']) && file_exists($folder . $cek_lama['GambarQRIS'])) {
            unlink($folder . $cek_lama['GambarQRIS']);
        }

        if (move_uploaded_file($_FILES['qris']['tmp_name'], $folder . $nama_file)) {
            $db->conn->query("UPDATE user SET GambarQRIS = '$nama_file' WHERE UserID = $penjual_id");
            $_SESSION['sukses'] = "QRIS toko berhasil diperbarui!";
        } else {
            $_SESSION['error'] = "Gagal meng-upload gambar QRIS ke server.";
        }
    } else {
        $_SESSION['error'] = "Terjadi kesalahan saat memilih file gambar.";
    }
}

header("Location: ../Frontend/penjual/dashboard.php");
exit;
?>