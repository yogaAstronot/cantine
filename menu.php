<?php
require_once '../cantine/Backend/Produk.php';

$produkObj     = new Produk();
$filter_kat    = $_GET['KategoriID'] ?? '';
$kategori_list = $produkObj->getAllKategori();
$produk_list   = $produkObj->getProduk($filter_kat);

$card_colors = [
    'bg-amber-200',
    'bg-rose-200',
    'bg-amber-100',
    'bg-orange-200',
    'bg-emerald-100',
    'bg-sky-200',
];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kantin Bu Ratna - Katalog & Pesan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#111917] text-gray-100 antialiased min-h-screen">

    <header class="bg-[#1f8a70] text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-14">
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">
                <div class="max-w-xl">
                    <p class="text-xs sm:text-sm font-medium text-emerald-100 tracking-wide mb-2">
                        Kantin Bu Ratna &bull; Gedung B
                    </p>
                    <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight leading-tight">
                        Pesan dulu, ambil tanpa antre.
                    </h1>
                    <p class="mt-3 text-sm sm:text-base text-emerald-50/90 leading-relaxed">
                        Pilih menu, kirim pesanan, dan kami panggil nomor mejamu saat makanan siap.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2 text-xs font-medium">
                    <span class="bg-[#facc15] text-gray-900 font-bold px-3.5 py-1.5 rounded-full shadow-sm">
                        Buka sekarang
                    </span>

                    <span class="bg-white/15 backdrop-blur-sm text-white px-3.5 py-1.5 rounded-full">
                        WhatsApp 0812-3456-7890
                    </span>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="w-full space-y-6">
            
            <!-- Search Bar -->
            <div class="relative">
                <input 
                    type="text" 
                    id="searchInput"
                    onkeyup="filterProduk()"
                    placeholder="Cari menu, misalnya nasi goreng" 
                    class="w-full bg-[#1b2824] border border-white/10 text-gray-200 placeholder-gray-400 text-sm rounded-xl px-4 py-3.5 focus:outline-none focus:border-[#1f8a70] focus:ring-1 focus:ring-[#1f8a70] transition"
                >
            </div>

            <!-- Filter Kategori -->
            <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
                <a href="?" 
                   class="px-4 py-2 rounded-full text-xs sm:text-sm font-medium whitespace-nowrap transition <?= ($filter_kat == '') ? 'bg-[#1f8a70] text-white shadow' : 'bg-[#1b2824] text-gray-300 border border-white/10 hover:border-white/30' ?>">
                    Semua
                </a>
                <?php foreach ($kategori_list as $k): ?>
                    <a href="?KategoriID=<?= htmlspecialchars($k['KategoriID']) ?>" 
                       class="px-4 py-2 rounded-full text-xs sm:text-sm font-medium whitespace-nowrap transition <?= ($filter_kat == $k['KategoriID']) ? 'bg-[#1f8a70] text-white shadow' : 'bg-[#1b2824] text-gray-300 border border-white/10 hover:border-white/30' ?>">
                        <?= htmlspecialchars($k['NamaKategori']) ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Grid Kartu Produk (4 Kolom di Desktop) -->
            <?php if (empty($produk_list)): ?>
                <div class="bg-[#1b2824] border border-white/10 rounded-2xl p-10 text-center text-gray-400">
                    Belum ada menu untuk kategori ini.
                </div>
            <?php else: ?>
                <div id="produkGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-5">
                    <?php foreach ($produk_list as $index => $p): ?>
                        <?php 
                            $stokTersedia = ($p['Stok'] ?? 0) > 0;
                            $bgHeader     = $card_colors[$index % count($card_colors)];
                            $namaProduk   = $p['NamaProduk'] ?? 'Produk';
                            $deskripsi    = $p['Deskripsi'] ?? ($p['NamaKategori'] ?? 'Menu kantin segar');
                            $harga        = (int)($p['Harga'] ?? 0);
                        ?>
                        <div class="produk-card bg-[#1b2824] border border-white/10 rounded-2xl overflow-hidden flex flex-col justify-between hover:border-white/25 transition group"
                             data-nama="<?= strtolower(htmlspecialchars($namaProduk)) ?>">
                            
                            <div class="<?= $bgHeader ?> h-28 flex items-center justify-center relative">
                                <?php if (!empty($p['Gambar'])): ?>
                                    <img src="../cantine/uploads/<?= htmlspecialchars($p['Gambar']) ?>" alt="<?= htmlspecialchars($namaProduk) ?>" class="h-20 w-20 object-contain group-hover:scale-105 transition">
                                <?php else: ?>
                                    <span class="text-4xl select-none group-hover:scale-110 transition">🍽️</span>
                                <?php endif; ?>
                            </div>

                            <div class="p-4 flex-1 flex flex-col justify-between">
                                <div>
                                    <h3 class="font-bold text-white text-base leading-snug">
                                        <?= htmlspecialchars($namaProduk) ?>
                                    </h3>
                                    <p class="text-xs text-gray-400 mt-1 line-clamp-1">
                                        <?= htmlspecialchars($deskripsi) ?>
                                    </p>
                                </div>

                                <div class="mt-4 flex items-center justify-between">
                                    <span class="font-bold text-sm sm:text-base text-white">
                                        Rp<?= number_format($harga, 0, ',', '.') ?>
                                    </span>

                                    <?php if ($stokTersedia): ?>
                                        <button 
                                            type="button"
                                            class="w-8 h-8 rounded-full bg-[#facc15] hover:bg-yellow-300 active:scale-95 text-gray-900 font-bold text-lg flex items-center justify-center shadow transition cursor-pointer"
                                            title="Tambah ke pesanan"
                                        >
                                        pesan
                                        </button>
                                    <?php else: ?>
                                        <span class="px-3 py-1 rounded-full bg-white/10 text-gray-400 text-xs font-medium">
                                            Habis
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
    </main>

    <script>
        function filterProduk() {
            const keyword = document.getElementById('searchInput').value.toLowerCase();
            const cards = document.querySelectorAll('.produk-card');

            cards.forEach(card => {
                const nama = card.getAttribute('data-nama');
                card.style.display = nama.includes(keyword) ? 'flex' : 'none';
            });
        }

        function tambahPesanan(id, nama, harga) {
            // Logika tambah pesanan bisa disesuaikan di sini
            alert(nama + ' ditambahkan!');
        }
    </script>
</body>
</html>