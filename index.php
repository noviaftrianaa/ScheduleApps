<!DOCTYPE html>
<html lang="id" >
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Selamat Datang di WebSchedule</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
</head>
<body class="min-h-screen bg-gradient-to-tr from-indigo-700 via-purple-700 to-pink-600 flex flex-col">

  <!-- Header -->
  <header class="flex justify-between items-center px-8 py-4 bg-white bg-opacity-20 backdrop-blur-md shadow-md">
    <h1 class="text-3xl font-extrabold text-white drop-shadow-lg">WebSchedule</h1>
    <nav class="space-x-6">
      <a href="login.php" class="text-white font-semibold hover:text-pink-300 transition">Masuk</a>
      <a href="register.php" class="text-white font-semibold hover:text-pink-300 transition">Daftar</a>
    </nav>
  </header>

  <!-- Main content -->
  <main class="flex-grow flex flex-col md:flex-row items-center justify-center px-8 gap-12">

    <!-- Kiri: Teks & tombol -->
    <section class="max-w-lg text-center md:text-left text-white drop-shadow-lg">
      <h2 class="text-5xl font-extrabold mb-6 leading-tight">
        Kelola Jadwal Kuliah & Aktivitasmu <br />
        dengan Mudah & Cepat
      </h2>
      <p class="mb-8 text-lg font-light">
        WebSchedule membantu kamu mengatur jadwal, to-do list, dan catatan dalam satu aplikasi yang simpel dan efisien.
      </p>
      <div class="space-x-4">
        <a href="register.php" class="inline-block bg-pink-500 hover:bg-pink-600 transition text-white font-bold py-3 px-8 rounded-lg shadow-lg">
          Daftar Sekarang
        </a>
        <a href="login.php" class="inline-block bg-transparent border-2 border-white hover:border-pink-500 transition text-white font-semibold py-3 px-8 rounded-lg">
          Masuk
        </a>
      </div>
    </section>

    <!-- Kanan: Ilustrasi -->
    <section class="max-w-md">
      <img
        src="https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=600&q=80"
        alt="Ilustrasi produktivitas"
        class="rounded-xl shadow-lg hover:scale-105 transition-transform duration-500"
      />
    </section>
  </main>

  <!-- Footer -->
  <footer class="bg-white bg-opacity-20 backdrop-blur-md shadow-inner text-white text-center py-4 font-semibold">
    &copy; 2025 WebSchedule. Semua hak cipta dilindungi.
  </footer>

</body>
</html>
