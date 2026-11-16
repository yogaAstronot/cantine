<?php
session_start();
if (isset($_SESSION['user_id'], $_SESSION['role']) && in_array($_SESSION['role'], ['guru', 'siswa'])) {
    header("Location: ../user/katalog.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Siswa & Guru</title>
</head>
<body>
    <a href="../../index.php">&larr; Kembali ke Beranda</a>
    <h2>Login Siswa & Guru</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <p style="color:red"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION['sukses'])): ?>
        <p style="color:green"><?= htmlspecialchars($_SESSION['sukses']); unset($_SESSION['sukses']); ?></p>
    <?php endif; ?>

    <form action="../../Auth/proses_login.php" method="POST">
        <input type="hidden" name="tipe_portal" value="user">
        <div>
            <label>Username:</label><br>
            <input type="text" name="Username" required>
        </div><br>
        <div>
            <label>Password:</label><br>
            <input type="password" name="Password" required>
        </div><br>
        <button type="submit">Masuk</button>
    </form>

    <p>Belum punya akun? <a href="register_user.php">Daftar di sini</a></p>
</body>
</html>