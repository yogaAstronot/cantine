<?php
session_start();
if (isset($_SESSION['user_id'], $_SESSION['role']) && in_array($_SESSION['role'], ['guru', 'siswa'])) {
    header("Location: ../user/katalog.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar - Siswa & Guru</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
  <section class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="bg-green-100 flex rounded-2xl shadow-lg max-w-3xl p-5 items-center">

      <div class="md:w-1/2 px-8 md:px-16">
        <a href="../../index.php" class="text-xs text-[#1F7A4D] hover:underline">&larr; Kembali ke Beranda</a>
        <h2 class="font-bold text-2xl text-[#1F7A4D] mt-2">Daftar Akun</h2>
        <p class="text-xs mt-4 text-[#1F7A4D]">Pendaftaran khusus untuk Siswa & Guru</p>

        <?php if (isset($_SESSION['error'])): ?>
          <p class="text-xs text-red-600 mt-3"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></p>
        <?php endif; ?>

        <form action="../../Auth/proses_register.php" method="POST" class="flex flex-col gap-4">
          <input class="p-2 mt-8 rounded-xl border" type="text" name="NamaLengkap" placeholder="Nama Lengkap" required>

          <select class="p-2 rounded-xl border bg-white text-gray-700" name="Role" required>
            <option value="siswa">Siswa</option>
            <option value="guru">Guru</option>
          </select>

          <input class="p-2 rounded-xl border" type="text" name="Username" placeholder="Username" required>

          <div class="relative">
            <input id="password" class="p-2 pr-10 rounded-xl border w-full" type="password" name="Password" placeholder="Password" required>
            <svg id="togglePassword" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="gray" class="bi bi-eye absolute top-1/2 right-3 -translate-y-1/2 cursor-pointer" viewBox="0 0 16 16">
              <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
              <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
            </svg>
          </div>

          <button type="submit" class="bg-[#1F7A4D] rounded-xl text-white py-2 hover:scale-105 duration-300">Daftar Akun</button>
        </form>

        <div class="mt-5 text-xs border-b border-[#1F7A4D] py-4 text-[#1F7A4D]"></div>

        <div class="mt-3 text-xs flex justify-between items-center text-[#1F7A4D]">
          <p>Sudah punya akun?</p>
          <a href="login_user.php" class="py-2 px-5 bg-white border rounded-xl hover:scale-110 duration-300">Login</a>
        </div>
      </div>

      <div class="md:block hidden w-1/2">
        <img class="rounded-2xl h-[500px] object-cover w-full" src="../../Assets/image/hd4.jpg" alt="Register Image">
      </div>

    </div>
  </section>
  <script src="Assets/js/transisi.js"></script>z
  <script src="/cantine/Assets/js/icon.js"></script>
</body>
</html>