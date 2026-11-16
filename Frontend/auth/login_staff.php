<?php
session_start();
if (isset($_SESSION['user_id'], $_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: ../admin/dashboard.php");
        exit;
    } elseif ($_SESSION['role'] === 'penjual') {
        header("Location: ../penjual/dashboard.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Pengelola</title>
</head>
<body>
    <a href="../../index.php">&larr; Kembali ke Beranda</a>
    <h3>Login Pengelola (Penjual & Admin)</h3>

    <?php if (isset($_SESSION['error'])): ?>
        <p style="color:red"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <form action="../../Auth/proses_login.php" method="POST">
        <input type="hidden" name="tipe_portal" value="staff">
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
</body>
</html>