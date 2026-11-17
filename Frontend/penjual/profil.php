<?php
require_once '../layout/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="../../Penjual/proses_qris.php" method="POST" enctype="multipart/form-data">
    <label>Upload Gambar QRIS Toko Kamu:</label><br>
    <input type="file" name="qris" accept="image/*" required><br><br>
    <button type="submit" name="simpan_qris">Simpan QRIS</button>
</form>
</body>
</html>



