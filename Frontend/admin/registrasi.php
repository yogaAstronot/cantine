<?php
require_once '../layout/header.php';
if ($_SESSION['Role'] != 'admin') { header("Location: ../../index.php"); exit; }
?>
<h2>Buat Akun Penjual / User</h2>

<?php if (isset($_SESSION['sukses'])): ?>
    <p><?= $_SESSION['sukses']; unset($_SESSION['sukses']); ?></p>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <p><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
<?php endif; ?>

<form action="../../Admin/proses_registrasi.php" method="POST">
    <div>
        <label>Nama Lengkap / Toko:</label><br>
        <input type="text" name="NamaLengkap" required>
    </div><br>
    <div>
        <label>Username:</label><br>
        <input type="text" name="Username" required>
    </div><br>
    <div>
        <label>Password:</label><br>
        <input type="password" name="Password" required>
    </div><br>
    <div>
        <label>Role:</label><br>
        <select name="Role">
            <option value="penjual" selected>Penjual</option>
            <option value="guru">Guru</option>
            <option value="siswa">Siswa</option>
            <option value="admin">Admin</option>
        </select>
    </div><br>
    <button type="submit">Simpan Akun</button>
</form>
<?php require_once '../layout/footer.php'; ?>