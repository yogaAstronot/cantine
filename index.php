<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantine | Pesan makanan kantin sekolah tanpa antre</title>
    <link href="Assets/css/output.css" rel="stylesheet">
</head>
<body class="bg-[#F6FAF4] text-[#17261F] antialiased">

    <!-- ===== NAVBAR ===== -->
    <header class="sticky top-0 z-30 border-b border-[#17261F]/10 bg-[#F6FAF4]/95 backdrop-blur">
        <nav class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3 sm:px-6">
            <a href="#" class="flex items-center gap-2 text-xl font-extrabold tracking-tight">
                CantineSmk
            </a>

            <ul class="hidden items-center gap-8 text-sm font-medium md:ml-auto md:mr-8 md:flex">
                <li><a class="hover:text-[#1F7A4D] focus-visible:outline-2 focus-visible:outline-[#1F7A4D]" href="#cara-pesan">Cara pesan</a></li>
                <li><a class="hover:text-[#1F7A4D] focus-visible:outline-2 focus-visible:outline-[#1F7A4D]" href="#menu">Menu favorit</a></li>
                <li><a class="hover:text-[#1F7A4D] focus-visible:outline-2 focus-visible:outline-[#1F7A4D]" href="#kios">Kios</a></li>
            </ul>

            <div class="hidden items-center gap-3 md:flex">
                <a href="../auth/login.php" class="rounded-lg px-4 py-2 text-sm font-semibold hover:bg-[#17261F]/5 focus-visible:outline-2 focus-visible:outline-[#1F7A4D]">Masuk</a>
                <a href="../auth/register.php" class="rounded-lg bg-[#1F7A4D] px-4 py-2 text-sm font-semibold text-white hover:bg-[#17603C] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#1F7A4D]">Daftar</a>
            </div>

            <!-- Tombol menu HP -->
            <button id="btn-menu" type="button" aria-expanded="false" aria-controls="menu-hp"
                    class="grid h-10 w-10 place-items-center rounded-lg hover:bg-[#17261F]/5 md:hidden focus-visible:outline-2 focus-visible:outline-[#1F7A4D]">
                <span class="sr-only">Buka menu</span>
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                    <path d="M4 7h16M4 12h16M4 17h16"/>
                </svg>
            </button>
        </nav>

        <div id="menu-hp" class="hidden border-t border-[#17261F]/10 px-4 pb-4 md:hidden">
            <ul class="flex flex-col py-2 text-base font-medium">
                <li><a class="block py-3" href="#cara-pesan">Cara pesan</a></li>
                <li><a class="block py-3" href="#menu">Menu favorit</a></li>
                <li><a class="block py-3" href="#kios">Kios</a></li>
            </ul>
            <div class="flex gap-3">
                <a href="../auth/login.php" class="flex-1 rounded-lg border border-[#17261F]/20 py-3 text-center font-semibold">Masuk</a>
                <a href="../auth/register.php" class="flex-1 rounded-lg bg-[#1F7A4D] py-3 text-center font-semibold text-white">Daftar</a>
            </div>
        </div>
    </header>

    <main>

        <!-- ===== HERO ===== -->
        <section class="mx-auto grid max-w-6xl items-center gap-10 px-4 py-12 sm:px-6 md:py-20 lg:grid-cols-2 lg:gap-16">
            <div>
                <h1 class="text-4xl font-extrabold leading-[1.1] tracking-tight sm:text-5xl lg:text-6xl">
                    Pesan dari kelas, ambil saat istirahat.
                </h1>
                <p class="mt-5 max-w-[46ch] text-lg leading-relaxed text-[#17261F]/75">
                    Pilih makanan dan minuman dari semua kios kantin, bayar, lalu ambil pesananmu tanpa berdesakan di antrean.
                </p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="../auth/register.php" class="rounded-xl bg-[#1F7A4D] px-6 py-3.5 text-center font-semibold text-white shadow-sm hover:bg-[#17603C] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#1F7A4D]">
                        Daftar dan mulai pesan
                    </a>
                    <a href="#menu" class="rounded-xl border-2 border-[#17261F] px-6 py-3.5 text-center font-semibold hover:bg-[#17261F] hover:text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#17261F]">
                        Lihat menu
                    </a>
                </div>
            </div>

            <!-- Papan menu hari ini: elemen utama halaman -->
            <div class="relative mx-auto w-full max-w-md lg:max-w-none">
                <div class="rotate-1 rounded-2xl border-[6px] border-[#C98B4B] bg-[#1E3B2F] p-6 text-[#F6FAF4] shadow-xl sm:p-8">
                    <div class="flex items-baseline justify-between border-b border-dashed border-[#F6FAF4]/40 pb-4">
                        <h2 class="text-2xl font-extrabold">Menu hari ini</h2>
                        <span class="text-sm text-[#F6FAF4]/70">Istirahat 1</span>
                    </div>
                    <ul class="divide-y divide-dashed divide-[#F6FAF4]/25">
                        <li class="flex items-center justify-between gap-4 py-3.5">
                            <span class="flex items-center gap-3"><span class="text-2xl" aria-hidden="true">🍛</span> Nasi ayam geprek</span>
                            <span class="font-bold text-[#FFC93C]">Rp 12.000</span>
                        </li>
                        <li class="flex items-center justify-between gap-4 py-3.5">
                            <span class="flex items-center gap-3"><span class="text-2xl" aria-hidden="true">🍜</span> Mie ayam bakso</span>
                            <span class="font-bold text-[#FFC93C]">Rp 10.000</span>
                        </li>
                        <li class="flex items-center justify-between gap-4 py-3.5">
                            <span class="flex items-center gap-3"><span class="text-2xl" aria-hidden="true">🍢</span> Sempol ayam</span>
                            <span class="font-bold text-[#FFC93C]">Rp 3.000</span>
                        </li>
                        <li class="flex items-center justify-between gap-4 py-3.5">
                            <span class="flex items-center gap-3"><span class="text-2xl" aria-hidden="true">🧋</span> Es teh manis</span>
                            <span class="font-bold text-[#FFC93C]">Rp 4.000</span>
                        </li>
                    </ul>
                    <p class="mt-4 text-sm text-[#F6FAF4]/60">Contoh tampilan. Menu asli diisi oleh penjual.</p>
                </div>
            </div>
        </section>

        <!-- ===== CARA PESAN (urutan nyata, jadi diberi nomor) ===== -->
        <section id="cara-pesan" class="scroll-mt-20 bg-white py-14 md:py-20">
            <div class="mx-auto max-w-6xl px-4 sm:px-6">
                <h2 class="max-w-xl text-3xl font-extrabold tracking-tight sm:text-4xl">Pesan dalam tiga langkah</h2>

                <ol class="mt-10 grid gap-6 md:grid-cols-3">
                    <li class="rounded-2xl bg-[#F6FAF4] p-6">
                        <span class="grid h-10 w-10 place-items-center rounded-full bg-[#1F7A4D] font-bold text-white">1</span>
                        <h3 class="mt-4 text-xl font-bold">Pilih menu</h3>
                        <p class="mt-2 leading-relaxed text-[#17261F]/75">Lihat menu dari semua kios, lengkap dengan harga, foto, dan stok yang tersisa.</p>
                    </li>
                    <li class="rounded-2xl bg-[#F6FAF4] p-6">
                        <span class="grid h-10 w-10 place-items-center rounded-full bg-[#1F7A4D] font-bold text-white">2</span>
                        <h3 class="mt-4 text-xl font-bold">Checkout</h3>
                        <p class="mt-2 leading-relaxed text-[#17261F]/75">Masukkan ke keranjang, cek total belanja, lalu kirim pesananmu ke penjual.</p>
                    </li>
                    <li class="rounded-2xl bg-[#F6FAF4] p-6">
                        <span class="grid h-10 w-10 place-items-center rounded-full bg-[#1F7A4D] font-bold text-white">3</span>
                        <h3 class="mt-4 text-xl font-bold">Ambil pesanan</h3>
                        <p class="mt-2 leading-relaxed text-[#17261F]/75">Saat status berubah menjadi selesai, datang ke kios dan ambil makananmu.</p>
                    </li>
                </ol>
            </div>
        </section>

        <!-- ===== MENU FAVORIT ===== -->
        <section id="menu" class="mx-auto max-w-6xl scroll-mt-20 px-4 py-14 sm:px-6 md:py-20">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                <h2 class="text-3xl font-extrabold tracking-tight sm:text-4xl">Menu yang sering dipesan</h2>
                <a href="../pelanggan/index.php" class="font-semibold text-[#1F7A4D] underline underline-offset-4 hover:text-[#17603C]">Lihat semua menu</a>
            </div>

            <!--
                Nanti bagian ini bisa diganti data dari database, contoh:
                <?php /* foreach ($menu as $m): ?> ... <?php endforeach; */ ?>
            -->
            <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">

                <article class="flex flex-col overflow-hidden rounded-2xl border border-[#17261F]/10 bg-white">
                    <div class="grid h-36 place-items-center bg-[#FFE9B8] text-6xl" aria-hidden="true">🍛</div>
                    <div class="flex flex-1 flex-col p-5">
                        <h3 class="text-lg font-bold">Nasi ayam geprek</h3>
                        <p class="mt-1 text-sm text-[#17261F]/70">Kios Bu Sari</p>
                        <div class="mt-auto flex items-center justify-between pt-5">
                            <span class="text-lg font-extrabold">Rp 12.000</span>
                            <a href="../auth/login.php" class="rounded-lg bg-[#1F7A4D] px-4 py-2 text-sm font-semibold text-white hover:bg-[#17603C]">Pesan</a>
                        </div>
                    </div>
                </article>

                <article class="flex flex-col overflow-hidden rounded-2xl border border-[#17261F]/10 bg-white">
                    <div class="grid h-36 place-items-center bg-[#FFD9D4] text-6xl" aria-hidden="true">🍜</div>
                    <div class="flex flex-1 flex-col p-5">
                        <h3 class="text-lg font-bold">Mie ayam bakso</h3>
                        <p class="mt-1 text-sm text-[#17261F]/70">Kios Pak Joko</p>
                        <div class="mt-auto flex items-center justify-between pt-5">
                            <span class="text-lg font-extrabold">Rp 10.000</span>
                            <a href="../auth/login.php" class="rounded-lg bg-[#1F7A4D] px-4 py-2 text-sm font-semibold text-white hover:bg-[#17603C]">Pesan</a>
                        </div>
                    </div>
                </article>

                <article class="flex flex-col overflow-hidden rounded-2xl border border-[#17261F]/10 bg-white">
                    <div class="grid h-36 place-items-center bg-[#D9EEDD] text-6xl" aria-hidden="true">🍢</div>
                    <div class="flex flex-1 flex-col p-5">
                        <h3 class="text-lg font-bold">Sempol ayam</h3>
                        <p class="mt-1 text-sm text-[#17261F]/70">Kios Snack Ceria</p>
                        <div class="mt-auto flex items-center justify-between pt-5">
                            <span class="text-lg font-extrabold">Rp 3.000</span>
                            <a href="../auth/login.php" class="rounded-lg bg-[#1F7A4D] px-4 py-2 text-sm font-semibold text-white hover:bg-[#17603C]">Pesan</a>
                        </div>
                    </div>
                </article>

                <article class="flex flex-col overflow-hidden rounded-2xl border border-[#17261F]/10 bg-white">
                    <div class="grid h-36 place-items-center bg-[#DCE9FA] text-6xl" aria-hidden="true">🧋</div>
                    <div class="flex flex-1 flex-col p-5">
                        <h3 class="text-lg font-bold">Es teh manis</h3>
                        <p class="mt-1 text-sm text-[#17261F]/70">Kios Segar</p>
                        <div class="mt-auto flex items-center justify-between pt-5">
                            <span class="text-lg font-extrabold">Rp 4.000</span>
                            <a href="../auth/login.php" class="rounded-lg bg-[#1F7A4D] px-4 py-2 text-sm font-semibold text-white hover:bg-[#17603C]">Pesan</a>
                        </div>
                    </div>
                </article>
            </div>
        </section>

        <!-- ===== KIOS / UNTUK PENJUAL ===== -->
        <section id="kios" class="scroll-mt-20 bg-[#1E3B2F] py-14 text-[#F6FAF4] md:py-20">
            <div class="mx-auto grid max-w-6xl items-center gap-10 px-4 sm:px-6 lg:grid-cols-2">
                <div>
                    <h2 class="text-3xl font-extrabold tracking-tight sm:text-4xl">Punya kios di kantin?</h2>
                    <p class="mt-4 max-w-[46ch] text-lg leading-relaxed text-[#F6FAF4]/80">
                        Akun penjual dibuat oleh admin sekolah. Setelah masuk, kamu bisa mengatur menu, harga, foto, dan deskripsi sendiri, lalu melihat pesanan yang masuk ke kiosmu.
                    </p>
                    <a href="../auth/login.php" class="mt-8 inline-block rounded-xl bg-[#FFC93C] px-6 py-3.5 font-semibold text-[#17261F] hover:bg-[#FFD966] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#FFC93C]">
                        Masuk sebagai penjual
                    </a>
                </div>
                <ul class="grid gap-4 sm:grid-cols-2">
                    <li class="rounded-2xl bg-[#F6FAF4]/10 p-5">
                        <h3 class="font-bold">Atur menu sendiri</h3>
                        <p class="mt-1 text-sm leading-relaxed text-[#F6FAF4]/75">Tambah, ubah, atau hapus menu kapan saja.</p>
                    </li>
                    <li class="rounded-2xl bg-[#F6FAF4]/10 p-5">
                        <h3 class="font-bold">Pesanan tercatat</h3>
                        <p class="mt-1 text-sm leading-relaxed text-[#F6FAF4]/75">Tidak ada lagi pesanan yang terlewat atau salah catat.</p>
                    </li>
                    <li class="rounded-2xl bg-[#F6FAF4]/10 p-5">
                        <h3 class="font-bold">Stok terkontrol</h3>
                        <p class="mt-1 text-sm leading-relaxed text-[#F6FAF4]/75">Menu yang habis tidak bisa dipesan lagi.</p>
                    </li>
                    <li class="rounded-2xl bg-[#F6FAF4]/10 p-5">
                        <h3 class="font-bold">Ubah status pesanan</h3>
                        <p class="mt-1 text-sm leading-relaxed text-[#F6FAF4]/75">Tandai diproses atau selesai, pembeli langsung tahu.</p>
                    </li>
                </ul>
            </div>
        </section>

        <!-- ===== CTA AKHIR ===== -->
        <section class="mx-auto max-w-6xl px-4 py-14 text-center sm:px-6 md:py-20">
            <h2 class="mx-auto max-w-2xl text-3xl font-extrabold tracking-tight sm:text-4xl">Istirahat cuma sebentar, jangan habiskan di antrean.</h2>
            <a href="../auth/register.php" class="mt-8 inline-block rounded-xl bg-[#1F7A4D] px-8 py-4 font-semibold text-white hover:bg-[#17603C] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#1F7A4D]">
                Buat akun gratis
            </a>
        </section>
    </main>

    <!-- ===== FOOTER ===== -->
    <footer class="border-t border-[#17261F]/10 bg-white">
        <div class="mx-auto flex max-w-6xl flex-col items-center justify-between gap-3 px-4 py-8 text-sm text-[#17261F]/70 sm:flex-row sm:px-6">
            <p class="font-bold text-[#17261F]">Cantine</p>
            <p>Kantin sekolah digital. &copy; <?= date('Y') ?></p>
        </div>
    </footer>

    <script>
        // Buka/tutup menu di layar HP
        const btn = document.getElementById('btn-menu');
        const panel = document.getElementById('menu-hp');
        btn.addEventListener('click', () => {
            const terbuka = panel.classList.toggle('hidden') === false;
            btn.setAttribute('aria-expanded', terbuka);
        });
        // Tutup menu setelah salah satu link diklik
        panel.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
            panel.classList.add('hidden');
            btn.setAttribute('aria-expanded', 'false');
        }));
    </script>
</body>
</html>