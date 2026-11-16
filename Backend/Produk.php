<?php
require_once __DIR__ . '/../Config/koneksi.php';

class Produk extends koneksi {
    // --- TABEL: kategori (KategoriID, NamaKategori) ---
    public function getAllKategori() {
        $res = $this->conn->query("SELECT KategoriID, NamaKategori FROM kategori ORDER BY NamaKategori ASC");
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    // --- TABEL: produk (ProdukID, PenjualID, KategoriID, NamaProduk, Harga, Stok) ---
    public function getProduk($KategoriID = '', $PenjualID = '') {
        $sql = "SELECT 
                    produk.ProdukID,
                    produk.PenjualID,
                    produk.KategoriID,
                    produk.NamaProduk,
                    produk.Harga,
                    produk.Stok,
                    kategori.NamaKategori,
                    user.NamaLengkap AS NamaPenjual 
                FROM produk 
                LEFT JOIN kategori ON produk.KategoriID = kategori.KategoriID
                LEFT JOIN user ON produk.PenjualID = user.UserID
                WHERE 1=1";
        
        if (!empty($KategoriID)) {
            $KategoriID = (int)$KategoriID;
            $sql .= " AND produk.KategoriID = $KategoriID";
        }
        if (!empty($PenjualID)) {
            $PenjualID = (int)$PenjualID;
            $sql .= " AND produk.PenjualID = $PenjualID";
        }
        $sql .= " ORDER BY produk.ProdukID DESC";
        
        $res = $this->conn->query($sql);
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    public function getProdukById($ProdukID) {
        $ProdukID = (int)$ProdukID;
        $res = $this->conn->query("SELECT ProdukID, PenjualID, KategoriID, NamaProduk, Harga, Stok FROM produk WHERE ProdukID = $ProdukID");
        return $res->fetch_assoc();
    }

    public function tambahProduk($PenjualID, $KategoriID, $NamaProduk, $Harga, $Stok) {
        $PenjualID  = (int)$PenjualID;
        $KategoriID = empty($KategoriID) ? "NULL" : (int)$KategoriID;
        $NamaProduk = $this->conn->real_escape_string($NamaProduk);
        $Harga      = (float)$Harga;
        $Stok       = (int)$Stok;

        $sql = "INSERT INTO produk (PenjualID, KategoriID, NamaProduk, Harga, Stok) 
                VALUES ($PenjualID, $KategoriID, '$NamaProduk', $Harga, $Stok)";
        return $this->conn->query($sql);
    }

    public function hapusProduk($ProdukID, $PenjualID) {
        $ProdukID  = (int)$ProdukID;
        $PenjualID = (int)$PenjualID;
        return $this->conn->query("DELETE FROM produk WHERE ProdukID = $ProdukID AND PenjualID = $PenjualID");
    }
}
?>