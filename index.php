<?php
session_start();

if (isset($_SESSION['UserID'], $_SESSION['Role'])) {
    if ($_SESSION['Role'] === 'admin') {
        header("Location: Frontend/admin/dashboard.php");
        exit;
    } elseif ($_SESSION['Role'] === 'penjual') {
        header("Location: Frontend/penjual/dashboard.php");
        exit;
    } elseif (in_array($_SESSION['Role'], ['guru', 'siswa'])) {
        header("Location: Frontend/user/katalog.php");
        exit;
    } else {
        session_unset();
        session_destroy();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tampilan sementara</title>
</head>
<body class="bg-light">
 <div class="d-flex gap-2">
            <a href="Frontend/auth/auth_user.php" class="btn btn-light btn-sm fw-semibold text-primary">Masuk Siswa / Guru</a>
            <a href="Frontend/auth/login_staff.php" class="btn btn-outline-light btn-sm">Login Pengelola</a>
        </div>
</body>
</html>