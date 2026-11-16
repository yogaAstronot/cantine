<?php
session_start();
require_once __DIR__ . '/../Backend/Auth.php';

$auth = new Auth();
$auth->cekAkses(['admin']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($auth->registrasi($_POST['NamaLengkap'], $_POST['Username'], $_POST['Password'], $_POST['Role'])) {
        $_SESSION['sukses'] = "Akun berhasil dibuat!";
    } else {
        $_SESSION['error'] = "Gagal! Username sudah terdaftar.";
    }
    header("Location: ../Frontend/admin/registrasi.php");
    exit;
}
?>