<?php
session_start();
require_once __DIR__ . '/../Backend/Auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth        = new Auth();
    $tipe_portal = $_POST['tipe_portal'] ?? 'user';
    $Username    = $_POST['Username'] ?? '';
    $Password    = $_POST['Password'] ?? '';

    $halaman_login = ($tipe_portal == 'staff') ? 'login_staff.php' : 'login_user.php';

    $role_db = $auth->login($Username, $Password);

    if (!$role_db) {
        $_SESSION['error'] = "Username atau Password salah!";
        header("Location: ../Frontend/auth/$halaman_login");
        exit;
    }

    $role_boleh = ($tipe_portal == 'staff') ? ['admin', 'penjual'] : ['siswa', 'guru'];

    if (!in_array($role_db, $role_boleh)) {
        session_unset();
        $_SESSION['error'] = ($tipe_portal == 'staff')
            ? "Akun ini bukan akun pengelola. Silakan masuk lewat portal Siswa / Guru."
            : "Akun Admin/Penjual silakan masuk melalui Portal Pengelola!";
        header("Location: ../Frontend/auth/$halaman_login");
        exit;
    }

    switch ($role_db) {
        case 'admin':
            header("Location: ../Frontend/admin/dashboard.php");
            break;
        case 'penjual':
            header("Location: ../Frontend/penjual/dashboard.php");
            break;
        default:
            header("Location: ../Frontend/user/katalog.php");
            break;
    }
    exit;
}
?>