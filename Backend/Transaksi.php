<?php
require_once __DIR__ . '/../Config/koneksi.php';

class Transaksi extends koneksi {
    public function getAllKelas() {
        $res = $this->conn->query("SELECT KelasID, NamaKelas FROM kelas ORDER BY NamaKelas ASC");
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getAllPelanggan() {
        $res = $this->conn->query("SELECT PelangganID, NamaPelanggan, Alamat, NomorTelepon FROM pelanggan ORDER BY PelangganID ASC");
        return $res->fetch_all(MYSQLI_ASSOC);
    }

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

    public function simpanTransaksi(
        $PembeliUserID, 
        $keranjang, 
        $KuponID = null, 
        $PotonganHarga = 0, 
        $PelangganID = 1, 
        $MetodePembayaran = 'cash', 
        $KelasID = null, 
        $CatatanPembeli = ''
    ) {
        if (empty($keranjang)) return false;

        $TanggalPenjualan = date('Y-m-d');
        $subtotal_keseluruhan = 0;

        foreach ($keranjang as $item) {
            $subtotal_keseluruhan += ($item['Harga'] * $item['JumlahProduk']);
        }

        $PotonganHarga = (float)$PotonganHarga;
        $TotalHarga    = max(0, $subtotal_keseluruhan - $PotonganHarga);
        
        $KuponSQL      = empty($KuponID) ? "NULL" : (int)$KuponID;
        $PembeliSQL    = empty($PembeliUserID) ? "NULL" : (int)$PembeliUserID;
        $KelasSQL      = empty($KelasID) ? "NULL" : (int)$KelasID;
        $PelangganID   = (int)$PelangganID;
        
        $MetodePembayaran = ($MetodePembayaran === 'qris') ? 'qris' : 'cash';
        $CatatanSQL       = empty(trim($CatatanPembeli)) 
                            ? "NULL" 
                            : "'" . $this->conn->real_escape_string(trim($CatatanPembeli)) . "'";

        $sql_penjualan = "INSERT INTO penjualan 
            (TanggalPenjualan, TotalHarga, StatusPesanan, MetodePembayaran, StatusPembayaran, PotonganHarga, CatatanPembeli, PelangganID, PembeliUserID, KelasID, KuponID) 
            VALUES 
            ('$TanggalPenjualan', $TotalHarga, 'menunggu', '$MetodePembayaran', 'belum_bayar', $PotonganHarga, $CatatanSQL, $PelangganID, $PembeliSQL, $KelasSQL, $KuponSQL)";
        
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

    public function uploadBuktiPembayaran($PenjualanID, $NamaFileBukti) {
        $PenjualanID   = (int)$PenjualanID;
        $NamaFileBukti = $this->conn->real_escape_string($NamaFileBukti);
        
        $sql = "UPDATE penjualan 
                SET BuktiPembayaran = '$NamaFileBukti', StatusPembayaran = 'menunggu_verifikasi' 
                WHERE PenjualanID = $PenjualanID";
        return $this->conn->query($sql);
    }

    public function updateStatusPembayaran($PenjualanID, $StatusPembayaran) {
        $PenjualanID = (int)$PenjualanID;
        $allowed     = ['belum_bayar', 'menunggu_verifikasi', 'lunas'];
        if (!in_array($StatusPembayaran, $allowed)) return false;

        $StatusPembayaran = $this->conn->real_escape_string($StatusPembayaran);
        return $this->conn->query("UPDATE penjualan SET StatusPembayaran = '$StatusPembayaran' WHERE PenjualanID = $PenjualanID");
    }

    public function getDetailTransaksi($PenjualanID) {
        $PenjualanID = (int)$PenjualanID;
        $sql = "SELECT p.*, k.NamaKelas, pembeli.NamaLengkap AS NamaPembeli,
                       penjual.NamaLengkap AS NamaPenjual, penjual.GambarQRIS
                FROM penjualan p
                LEFT JOIN kelas k ON p.KelasID = k.KelasID
                LEFT JOIN user pembeli ON p.PembeliUserID = pembeli.UserID
                JOIN detailpenjualan dp ON p.PenjualanID = dp.PenjualanID
                JOIN produk pr ON dp.ProdukID = pr.ProdukID
                JOIN user penjual ON pr.PenjualID = penjual.UserID
                WHERE p.PenjualanID = $PenjualanID
                LIMIT 1";
        $res = $this->conn->query($sql);
        return $res ? $res->fetch_assoc() : null;
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

        if ($StatusPesanan === 'selesai') {
            $this->conn->query("UPDATE penjualan SET StatusPesanan = '$StatusPesanan', StatusPembayaran = 'lunas' WHERE PenjualanID = $PenjualanID");
            return true;
        }

        return $this->conn->query("UPDATE penjualan SET StatusPesanan = '$StatusPesanan' WHERE PenjualanID = $PenjualanID");
    }

    public function getPesananPenjual($PenjualID) {
        $PenjualID = (int)$PenjualID;
        $sql = "SELECT p.*, k.NamaKelas, u.NamaLengkap AS NamaPembeli, u.Role AS RolePembeli,
                       pr.NamaProduk, dp.JumlahProduk, dp.Subtotal
                FROM detailpenjualan dp
                JOIN penjualan p ON dp.PenjualanID = p.PenjualanID
                JOIN produk pr ON dp.ProdukID = pr.ProdukID
                LEFT JOIN user u ON p.PembeliUserID = u.UserID
                LEFT JOIN kelas k ON p.KelasID = k.KelasID
                WHERE pr.PenjualID = $PenjualID
                ORDER BY FIELD(p.StatusPesanan, 'menunggu', 'diproses', 'selesai', 'dibatalkan'), p.PenjualanID DESC";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function getSemuaTransaksiAdmin() {
        $sql = "SELECT p.*, k.NamaKelas, pl.NamaPelanggan, u.NamaLengkap AS NamaPembeli, u.Role AS RolePembeli, kp.KodeKupon,
                       GROUP_CONCAT(CONCAT(pr.NamaProduk, ' (', dp.JumlahProduk, 'x - Penjual: ', pj.NamaLengkap, ')') SEPARATOR '<br>') AS RincianBarang
                FROM penjualan p
                LEFT JOIN pelanggan pl ON p.PelangganID = pl.PelangganID
                LEFT JOIN user u ON p.PembeliUserID = u.UserID
                LEFT JOIN kelas k ON p.KelasID = k.KelasID
                LEFT JOIN kupon kp ON p.KuponID = kp.KuponID
                JOIN detailpenjualan dp ON p.PenjualanID = dp.PenjualanID
                JOIN produk pr ON dp.ProdukID = pr.ProdukID
                LEFT JOIN user pj ON pr.PenjualID = pj.UserID
                GROUP BY p.PenjualanID
                ORDER BY p.PenjualanID DESC";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function getRiwayatPembeli($UserID) {
        $UserID = (int)$UserID;
        $sql = "SELECT p.*, k.NamaKelas, kp.KodeKupon
                FROM penjualan p
                LEFT JOIN kupon kp ON p.KuponID = kp.KuponID
                LEFT JOIN kelas k ON p.KelasID = k.KelasID
                WHERE p.PembeliUserID = $UserID
                ORDER BY p.PenjualanID DESC";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }
}
?>