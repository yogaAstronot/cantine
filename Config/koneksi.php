<?php
session_start();
$conn = new mysqli("localhost", "root", "", "cantine1db");

if ($conn->connect_error) {
    die("Koneksi gagal!");
}
?>