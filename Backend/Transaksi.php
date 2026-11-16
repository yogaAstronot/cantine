<?php
require_once __DIR__ . '/../Config/koneksi.php';

class Transaksi extends koneksi {
    // --- TABEL: pelanggan (PelangganID, NamaPelanggan, Alamat, NomorTelepon) ---
    public function getAllPelanggan() {
        $res = $this->conn->query("SELECT PelangganID, NamaPelanggan, Alamat, NomorTelepon FROM pelanggan ORDER BY PelangganID ASC");
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    // --- TABEL: kupon (KuponID, KodeKupon, TipeDiskon, NilaiDiskon, MinimalBelanja, TanggalMulai, TanggalBerakhir, Kuota, Status) ---
    public function getAllKupon() {
        $res = $this->conn->query("SELECT KuponID, KodeKupon, TipeDiskon, NilaiDiskon, MinimalBelanja, TanggalMulai, TanggalBerakhir, Kuota, Status FROM kupon ORDER BY KuponID DESC");
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    public function tambahKupon($KodeKupon, $TipeDiskon, $NilaiDiskon, $MinimalBelanja, $TanggalMulai, $TanggalBerakhir, $Kuota) {
        $KodeKupon       = strtoupper($this->conn->real_escape_string($KodeKupon));
        $TipeDiskon      = $this->conn->real_escape_string($TipeDiskon);
        $NilaiDiskon     = (float)$NilaiDiskon;
        $MinimalBelanja  = (float)$MinimalBelanja;
        $TanggalMulai    = $this->conn->real_escape_string($TanggalMulai);
        $TanggalBerakhir = $this->conn->real_escape_string($TanggalBerakhir);
        $Kuota           = (int)$Kuota;

        $sql = "INSERT INTO kupon (KodeKupon, TipeDiskon, NilaiDiskon, MinimalBelanja, TanggalMulai, TanggalBerakhir, Kuota, Status) 
                VALUES ('$KodeKupon', '$TipeDiskon', $NilaiDiskon, $MinimalBelanja, '$TanggalMulai', '$TanggalBerakhir', $Kuota, 'aktif')";
        return $this->conn->query($sql);
    }

    public function validasiKupon($KodeKupon, $total_belanja) {
        $KodeKupon = strtoupper($this->conn->real_escape_string($KodeKupon));
        $hari_ini  = date('Y-m-d');

        $sql = "SELECT KuponID, KodeKupon, TipeDiskon, NilaiDiskon, MinimalBelanja, TanggalMulai, TanggalBerakhir, Kuota, Status 
                FROM kupon 
                WHERE KodeKupon = '$KodeKupon' AND Status = 'aktif' AND Kuota > 0 
                AND '$hari_ini' BETWEEN TanggalMulai AND TanggalBerakhir";
        
        $res = $this->conn->query($sql);
        if (!$res || $res->num_rows == 0) {
            return ['status' => false, 'pesan' => 'Kupon tidak ditemukan, kedaluwarsa, atau kuota habis.'];
        }

        $kupon = $res->fetch_assoc();
        if ($total_belanja < $kupon['MinimalBelanja']) {
            return ['status' => false, 'pesan' => 'Minimal belanja Rp ' . number_format($kupon['MinimalBelanja'], 0, ',', '.')];
        }

        $PotonganHarga = ($kupon['TipeDiskon'] == 'persen') 
                         ? ($kupon['NilaiDiskon'] / 100) * $total_belanja 
                         : $kupon['NilaiDiskon'];

        if ($PotonganHarga > $total_belanja) $PotonganHarga = $total_belanja;

        return [
            'status'        => true,
            'KuponID'       => $kupon['KuponID'],
            'KodeKupon'     => $kupon['KodeKupon'],
            'PotonganHarga' => $PotonganHarga,
            'pesan'         => 'Kupon berhasil diterapkan!'
        ];
    }

    // --- TABEL: penjualan & detailpenjualan ---
    public function simpanTransaksi($PembeliUserID, $keranjang, $KuponID = null, $PotonganHarga = 0, $PelangganID = 1) {
        if (empty($keranjang)) return false;

        $TanggalPenjualan = date('Y-m-d');
        $subtotal_keseluruhan = 0;

        foreach ($keranjang as $item) {
            $subtotal_keseluruhan += ($item['Harga'] * $item['JumlahProduk']);
        }

        $TotalHarga    = max(0, $subtotal_keseluruhan - $PotonganHarga);
        $KuponSQL      = empty($KuponID) ? "NULL" : (int)$KuponID;
        $PembeliSQL    = empty($PembeliUserID) ? "NULL" : (int)$PembeliUserID;
        $PelangganID   = (int)$PelangganID;
        $PotonganHarga = (float)$PotonganHarga;

        $sql_penjualan = "INSERT INTO penjualan (TanggalPenjualan, TotalHarga, StatusPesanan, PotonganHarga, PelangganID, PembeliUserID, KuponID) 
                          VALUES ('$TanggalPenjualan', $TotalHarga, 'menunggu', $PotonganHarga, $PelangganID, $PembeliSQL, $KuponSQL)";
        
        if ($this->conn->query($sql_penjualan)) {
            $PenjualanID = $this->conn->insert_id;

            foreach ($keranjang as $ProdukID => $item) {
                $ProdukID     = (int)$ProdukID;
                $JumlahProduk = (int)$item['JumlahProduk'];
                $Subtotal     = $item['Harga'] * $JumlahProduk;

                $this->conn->query("INSERT INTO detailpenjualan (PenjualanID, ProdukID, JumlahProduk, Subtotal) 
                                    VALUES ($PenjualanID, $ProdukID, $JumlahProduk, $Subtotal)");
                $this->conn->query("UPDATE produk SET Stok = Stok - $JumlahProduk WHERE ProdukID = $ProdukID");
            }

            if (!empty($KuponID)) {
                $KuponID = (int)$KuponID;
                $this->conn->query("UPDATE kupon SET Kuota = Kuota - 1 WHERE KuponID = $KuponID");
            }
            return $PenjualanID;
        }
        return false;
    }

    public function getRingkasanPenjual($PenjualID) {
        $PenjualID = (int)$PenjualID;
        
        $q_omzet = $this->conn->query("
            SELECT COALESCE(SUM(detailpenjualan.Subtotal), 0) AS total_omzet
            FROM detailpenjualan
            JOIN produk ON detailpenjualan.ProdukID = produk.ProdukID
            JOIN penjualan ON detailpenjualan.PenjualanID = penjualan.PenjualanID
            WHERE produk.PenjualID = $PenjualID AND penjualan.StatusPesanan != 'dibatalkan'
        ")->fetch_assoc();

        $q_pending = $this->conn->query("
            SELECT COUNT(DISTINCT penjualan.PenjualanID) AS jml_pending
            FROM detailpenjualan
            JOIN produk ON detailpenjualan.ProdukID = produk.ProdukID
            JOIN penjualan ON detailpenjualan.PenjualanID = penjualan.PenjualanID
            WHERE produk.PenjualID = $PenjualID AND penjualan.StatusPesanan IN ('menunggu', 'diproses')
        ")->fetch_assoc();

        $q_stok = $this->conn->query("
            SELECT COUNT(*) AS stok_menipis 
            FROM produk 
            WHERE PenjualID = $PenjualID AND Stok <= 5
        ")->fetch_assoc();

        return [
            'omzet'        => $q_omzet['total_omzet'],
            'pending'      => $q_pending['jml_pending'],
            'stok_menipis' => $q_stok['stok_menipis']
        ];
    }

    public function updateStatusPesanan($PenjualanID, $StatusPesanan) {
        $PenjualanID   = (int)$PenjualanID;
        $StatusPesanan = $this->conn->real_escape_string($StatusPesanan);

        if ($StatusPesanan === 'dibatalkan') {
            $cek = $this->conn->query("SELECT StatusPesanan FROM penjualan WHERE PenjualanID = $PenjualanID")->fetch_assoc();
            if ($cek && $cek['StatusPesanan'] !== 'dibatalkan') {
                $items = $this->conn->query("SELECT ProdukID, JumlahProduk FROM detailpenjualan WHERE PenjualanID = $PenjualanID");
                while ($item = $items->fetch_assoc()) {
                    $ProdukID     = (int)$item['ProdukID'];
                    $JumlahProduk = (int)$item['JumlahProduk'];
                    $this->conn->query("UPDATE produk SET Stok = Stok + $JumlahProduk WHERE ProdukID = $ProdukID");
                }
            }
        }

        return $this->conn->query("UPDATE penjualan SET StatusPesanan = '$StatusPesanan' WHERE PenjualanID = $PenjualanID");
    }

    public function getPesananPenjual($PenjualID) {
        $PenjualID = (int)$PenjualID;
        $sql = "SELECT 
                    penjualan.PenjualanID,
                    penjualan.TanggalPenjualan,
                    penjualan.StatusPesanan,
                    pembeli.NamaLengkap AS NamaPembeli,
                    pembeli.Role AS RolePembeli,
                    produk.NamaProduk,
                    detailpenjualan.JumlahProduk,
                    detailpenjualan.Subtotal
                FROM detailpenjualan
                JOIN penjualan ON detailpenjualan.PenjualanID = penjualan.PenjualanID
                JOIN produk ON detailpenjualan.ProdukID = produk.ProdukID
                LEFT JOIN user AS pembeli ON penjualan.PembeliUserID = pembeli.UserID
                WHERE produk.PenjualID = $PenjualID
                ORDER BY FIELD(penjualan.StatusPesanan, 'menunggu', 'diproses', 'selesai', 'dibatalkan'), penjualan.PenjualanID DESC";
        $res = $this->conn->query($sql);
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    public function getSemuaTransaksiAdmin() {
        $sql = "SELECT 
                    penjualan.PenjualanID,
                    penjualan.TanggalPenjualan,
                    penjualan.TotalHarga,
                    penjualan.StatusPesanan,
                    penjualan.PotonganHarga,
                    pelanggan.NamaPelanggan,
                    pembeli.NamaLengkap AS NamaPembeli,
                    pembeli.Role AS RolePembeli,
                    kupon.KodeKupon,
                    GROUP_CONCAT(CONCAT(produk.NamaProduk, ' (', detailpenjualan.JumlahProduk, 'x - Penjual: ', penjual.NamaLengkap, ')') SEPARATOR '<br>') AS RincianBarang
                FROM penjualan
                LEFT JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID
                LEFT JOIN user AS pembeli ON penjualan.PembeliUserID = pembeli.UserID
                LEFT JOIN kupon ON penjualan.KuponID = kupon.KuponID
                JOIN detailpenjualan ON penjualan.PenjualanID = detailpenjualan.PenjualanID
                JOIN produk ON detailpenjualan.ProdukID = produk.ProdukID
                LEFT JOIN user AS penjual ON produk.PenjualID = penjual.UserID
                GROUP BY penjualan.PenjualanID
                ORDER BY penjualan.PenjualanID DESC";
        $res = $this->conn->query($sql);
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    public function getRiwayatPembeli($UserID) {
        $UserID = (int)$UserID;
        $sql = "SELECT 
                    penjualan.PenjualanID,
                    penjualan.TanggalPenjualan,
                    penjualan.TotalHarga,
                    penjualan.StatusPesanan,
                    penjualan.PotonganHarga,
                    kupon.KodeKupon 
                FROM penjualan 
                LEFT JOIN kupon ON penjualan.KuponID = kupon.KuponID 
                WHERE penjualan.PembeliUserID = $UserID 
                ORDER BY penjualan.PenjualanID DESC";
        $res = $this->conn->query($sql);
        return $res->fetch_all(MYSQLI_ASSOC);
    }
}
?>