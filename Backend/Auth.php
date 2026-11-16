<?php
require_once __DIR__ . '/../Config/koneksi.php';

class Auth extends koneksi {

    public function login($Username, $Password) {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $stmt = $this->conn->prepare(
            "SELECT UserID, NamaLengkap, Username, Role, Password FROM user WHERE Username = ? LIMIT 1"
        );
        $stmt->bind_param("s", $Username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if (!$user) return false;

        if (md5($Password) !== $user['Password']) return false;

        $_SESSION['UserID']      = $user['UserID'];
        $_SESSION['NamaLengkap'] = $user['NamaLengkap'];
        $_SESSION['Username']    = $user['Username'];
        $_SESSION['Role']        = $user['Role'];

        return $user['Role'];
    }

    public function registrasi($NamaLengkap, $Username, $Password, $Role) {
        $cek = $this->conn->prepare("SELECT UserID FROM user WHERE Username = ? LIMIT 1");
        $cek->bind_param("s", $Username);
        $cek->execute();
        if ($cek->get_result()->num_rows > 0) {
            return false;
        }

        $hash = md5($Password);
        $stmt = $this->conn->prepare(
            "INSERT INTO user (NamaLengkap, Username, Password, Role) VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("ssss", $NamaLengkap, $Username, $hash, $Role);
        return $stmt->execute();
    }

    public function cekAkses($allowed_roles = []) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['UserID'], $_SESSION['Role']) || !in_array($_SESSION['Role'], $allowed_roles)) {
            header("Location: ../Auth/logout.php");
            exit;
        }
    }
}
?>