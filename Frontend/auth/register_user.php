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
    <title>Daftar - Siswa & Guru</title>
</head>
<body>
    <a href="../../index.php">&larr; Kembali ke Beranda</a>
    <h2>Daftar Akun Siswa & Guru</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <p style="color:red"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <form action="../../Auth/proses_registrasi.php" method="POST">
        <div>
            <label>Nama Lengkap:</label><br>
            <input type="text" name="NamaLengkap" required>
        </div><br>
        <div>
            <label>Role:</label><br>
            <select name="Role" required>
                <option value="siswa">Siswa</option>
                <option value="guru">Guru</option>
            </select>
        </div><br>
        <div>
            <label>Username:</label><br>
            <input type="text" name="Username" required>
        </div><br>
        <div>
            <label>Password:</label><br>
            <input type="password" name="Password" required>
        </div><br>
        <button type="submit">Daftar Akun</button>
    </form>

    <p>Sudah punya akun? <a href="login_user.php">Login di sini</a></p>
</body>
</html>