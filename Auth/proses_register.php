<?php
session_start();
require_once __DIR__ . '/../Backend/Auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth        = new Auth();
    $NamaLengkap = $_POST['NamaLengkap'];
    $Username    = $_POST['Username'];
    $Password    = $_POST['Password'];
    $Role        = $_POST['Role'];

    if (!in_array($Role, ['siswa', 'guru'])) {
        $_SESSION['error'] = "Pilihan role tidak valid!";
        header("Location: ../Frontend/auth/auth_user.php");
        exit;
    }

    if ($auth->registrasi($NamaLengkap, $Username, $Password, $Role)) {
        $_SESSION['sukses'] = "Registrasi berhasil! Silakan login menggunakan akun Anda.";
    } else {
        $_SESSION['error'] = "Registrasi gagal! Username '$Username' sudah digunakan.";
    }

    header("Location: ../Frontend/auth/auth_user.php");
    exit;
}
?>