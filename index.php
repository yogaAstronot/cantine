<?php
require_once '../cantine/Backend/Produk.php';

session_start();

if (isset($_SESSION['UserID'], $_SESSION['Role'])) {
    if ($_SESSION['Role'] === 'admin') {
        header("Location: Frontend/admin/dashboard.php");
        exit;
    } elseif ($_SESSION['Role'] === 'penjual') {
        header("Location: Frontend/penjual/dashboard.php");
        exit;
    } elseif (in_array($_SESSION['Role'], ['guru', 'siswa'])) {
        header("Location: Frontend/user/katalog.php");
        exit;
    } else {
        session_unset();
        session_destroy();
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantine | Pesan makanan kantin sekolah tanpa antre</title>
    <link href="Assets/css/output.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="Assets/css/struk.css">

</head>

<body class="bg-[#F6FAF4] text-[#17261F] antialiased">

    <header class="sticky top-0 z-30 border-b border-[#17261F]/10 bg-[#F6FAF4]/95 backdrop-blur">
        <nav class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3 sm:px-6">
            <a href="#" class="flex items-center gap-2 text-xl font-extrabold tracking-tight">
                CantineSmk
            </a>

            <ul class="hidden items-center gap-8 text-sm font-medium md:ml-auto md:mr-8 md:flex">
                <li><a class="hover:text-[#1F7A4D] focus-visible:outline-2 focus-visible:outline-[#1F7A4D]" href="#cara-pesan">Cara pesan</a></li>
                <li><a class="hover:text-[#1F7A4D] focus-visible:outline-2 focus-visible:outline-[#1F7A4D]" href="#kios">Kios</a></li>
            </ul>

            <div class="hidden items-center gap-3 md:flex">
                <a href="../cantine/Frontend/auth/auth_user.php" class="rounded-lg px-4 py-2 text-sm font-semibold hover:bg-[#17261F]/5 focus-visible:outline-2 focus-visible:outline-[#1F7A4D]">Masuk</a>
                <a href="../cantine/Frontend/auth/register_user.php" class="rounded-lg bg-[#1F7A4D] px-4 py-2 text-sm font-semibold text-white hover:bg-[#17603C] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#1F7A4D]">Daftar</a>
            </div>

            <button id="btn-menu" type="button" aria-expanded="false" aria-controls="menu-hp"
                class="grid h-10 w-10 place-items-center rounded-lg hover:bg-[#17261F]/5 md:hidden focus-visible:outline-2 focus-visible:outline-[#1F7A4D]">
                <span class="sr-only">Buka menu</span>
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                    <path d="M4 7h16M4 12h16M4 17h16" />
                </svg>
            </button>
        </nav>

        <div id="menu-hp" class="hidden border-t border-[#17261F]/10 px-4 pb-4 md:hidden">
            <ul class="flex flex-col py-2 text-base font-medium">
                <li><a class="block py-3" href="#cara-pesan">Cara pesan</a></li>
                <li><a class="block py-3" href="#kios">Kios</a></li>
            </ul>
            <div class="flex gap-3">
                <a href="../auth/login.php" class="flex-1 rounded-lg border border-[#17261F]/20 py-3 text-center font-semibold">Masuk</a>
                <a href="../auth/register.php" class="flex-1 rounded-lg bg-[#1F7A4D] py-3 text-center font-semibold text-white">Daftar</a>
            </div>
        </div>
    </header>

    <main>
        <section class="mx-auto grid max-w-6xl items-center gap-10 px-4 py-12 sm:px-6 md:py-20 lg:grid-cols-2 lg:gap-16">
            <div>
                <h1 class="text-4xl font-extrabold leading-[1.1] tracking-tight sm:text-5xl lg:text-6xl">
                    Pesan dari kelas, ambil saat istirahat.
                </h1>
                <p class="mt-5 max-w-[46ch] text-lg leading-relaxed text-[#17261F]/75">
                    Pilih makanan dan minuman dari semua kios kantin, bayar, lalu ambil pesananmu tanpa berdesakan di antrean.
                </p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="../cantine/Frontend/auth/auth_user.php" class="rounded-xl bg-[#1F7A4D] px-6 py-3.5 text-center font-semibold text-white shadow-sm hover:bg-[#17603C] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#1F7A4D]">
                        Daftar dan mulai pesan
                    </a>
                    <a href="menu.php" class="rounded-xl border-2 border-[#17261F] px-6 py-3.5 text-center font-semibold hover:bg-[#17261F] hover:text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#17261F]">
                        Lihat menu
                    </a>
                </div>
            </div>

            <div>
                <div class="tilt" id="nota">
                    <div class="slot"></div>
                    <div class="out ">
                        <div class="rc mono b">
                            <div class="text-center"><b style="font-size:16px;letter-spacing:.04em">CANTINE SMK</b><br>Nota pesanan No. 014</div>
                            <div class="dash"></div>
                            <div>Pemesan: XI RPL 2</div>
                            <div>Ambil : Istirahat 1</div>
                            <div class="dash"></div>
                            <div class="rl"><span>1x Nasi ayam geprek</span><span>12.000</span></div>
                            <div class="rl"><span>1x Es teh manis</span><span>4.000</span></div>
                            <div class="rl"><span>2x Sempol ayam</span><span>6.000</span></div>
                            <div class="dash"></div>
                            <div class="rl"><b>TOTAL</b><b>Rp 22.000</b></div>
                            <div class="rl"><span>Bayar</span><span>QRIS</span></div>
                            <div class="dash"></div>
                            <div class="text-center" style="font-size:12px">Tunjukkan nota ini ke kios.<br>Contoh tampilan.</div>
                            <div class="stamp mono">SIAP DIAMBIL</div>
                        </div>
                    </div>
                </div>
            </div>
            </div>

        </section>

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


        <section id="menu" class="mx-auto max-w-6xl scroll-mt-20 px-4 py-14 sm:px-6 md:py-20">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                <h2 class="text-3xl font-extrabold tracking-tight sm:text-4xl">Menu yang sering dipesan</h2>
            </div>

        </section>

        <section>

        </section>

        <section id="kios" class="scroll-mt-20 bg-[#1E3B2F] py-14 text-[#F6FAF4] md:py-20">
            <div class="mx-auto grid max-w-6xl items-center gap-10 px-4 sm:px-6 lg:grid-cols-2">
                <div>
                    <h2 class="text-3xl font-extrabold tracking-tight sm:text-4xl">Punya kios di kantin?</h2>
                    <p class="mt-4 max-w-[46ch] text-lg leading-relaxed text-[#F6FAF4]/80">
                        Akun penjual dibuat oleh admin sekolah. Setelah masuk, kamu bisa mengatur menu, harga, foto, dan deskripsi sendiri, lalu melihat pesanan yang masuk ke kiosmu.
                    </p>
                    <a href="../cantine/Frontend/auth/login_staff.php" class="mt-8 inline-block rounded-xl bg-[#FFC93C] px-6 py-3.5 font-semibold text-[#17261F] hover:bg-[#FFD966] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#FFC93C]">
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

        <section class="mx-auto max-w-6xl px-4 py-14 text-center sm:px-6 md:py-20">
            <h2 class="mx-auto max-w-2xl text-3xl font-extrabold tracking-tight sm:text-4xl">Istirahat cuma sebentar, jangan habiskan di antrean.</h2>
        </section>
    </main>

    <footer class="border-t border-[#17261F]/10 bg-white">
        <div class="mx-auto flex max-w-6xl flex-col items-center justify-between gap-3 px-4 py-8 text-sm text-[#17261F]/70 sm:flex-row sm:px-6">
            <p class="font-bold text-[#17261F]">Cantine</p>
            <p>Kantin sekolah digital. &copy; <?= date('Y') ?></p>
        </div>
    </footer>

    <script>
        const btn = document.getElementById('btn-menu');
        const panel = document.getElementById('menu-hp');
        btn.addEventListener('click', () => {
            const terbuka = panel.classList.toggle('hidden') === false;
            btn.setAttribute('aria-expanded', terbuka);
        });
        panel.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
            panel.classList.add('hidden');
            btn.setAttribute('aria-expanded', 'false');
        }));
    </script>
</body>

</html>