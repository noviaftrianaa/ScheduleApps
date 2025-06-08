<!DOCTYPE html>
<html lang="id" >
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Register Keren dengan Tailwind CSS</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
</head>
<body class="min-h-screen bg-gradient-to-tr from-purple-700 via-pink-600 to-indigo-700 flex items-center justify-center px-4">

  <div class="max-w-md w-full bg-white bg-opacity-90 rounded-xl shadow-2xl p-10 relative overflow-hidden">
    <!-- Dekorasi lingkaran -->
    <div class="absolute top-0 -right-20 w-40 h-40 bg-pink-400 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
    <div class="absolute bottom-0 -left-20 w-40 h-40 bg-purple-400 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>

    <h2 class="text-4xl font-extrabold text-center mb-8 text-purple-800 drop-shadow-lg">
      Daftar Akun Baru
    </h2>

    <form id="register-form" class="space-y-6" autocomplete="off">
      <div>
        <label for="username" class="block mb-2 font-semibold text-purple-700">Nama Pengguna</label>
        <div class="flex items-center border border-purple-300 rounded-md px-3 py-2 focus-within:ring-2 focus-within:ring-purple-500 transition">
          <i class="fa fa-user text-purple-600 mr-2"></i>
          <input
            type="text"
            id="username"
            name="username"
            placeholder="Masukkan nama pengguna"
            required
            class="w-full outline-none"
          />
        </div>
      </div>

      <div>
        <label for="email" class="block mb-2 font-semibold text-purple-700">Email</label>
        <div class="flex items-center border border-purple-300 rounded-md px-3 py-2 focus-within:ring-2 focus-within:ring-purple-500 transition">
          <i class="fa fa-envelope text-purple-600 mr-2"></i>
          <input
            type="email"
            id="email"
            name="email"
            placeholder="Masukkan email"
            required
            class="w-full outline-none"
          />
        </div>
      </div>

      <div>
        <label for="password" class="block mb-2 font-semibold text-purple-700">Kata Sandi</label>
        <div class="flex items-center border border-purple-300 rounded-md px-3 py-2 focus-within:ring-2 focus-within:ring-purple-500 transition">
          <i class="fa fa-lock text-purple-600 mr-2"></i>
          <input
            type="password"
            id="password"
            name="password"
            placeholder="Masukkan kata sandi"
            required
            class="w-full outline-none"
          />
        </div>
      </div>

      <button
        type="submit"
        class="w-full bg-gradient-to-r from-pink-500 via-purple-600 to-indigo-600 text-white font-bold py-3 rounded-lg shadow-lg hover:from-pink-600 hover:via-purple-700 hover:to-indigo-700 transition duration-300"
      >
        Daftar
      </button>
    </form>

    <p class="mt-6 text-center text-purple-700">
      Sudah punya akun?
      <a href="login.php" class="font-semibold underline hover:text-pink-600 transition">Masuk di sini</a>
    </p>
  </div>

  <style>
    /* Animasi blob background */
    @keyframes blob {
      0%, 100% {
        transform: translate(0px, 0px) scale(1);
      }
      33% {
        transform: translate(30px, -50px) scale(1.1);
      }
      66% {
        transform: translate(-20px, 20px) scale(0.9);
      }
    }

    .animate-blob {
      animation: blob 7s infinite;
    }

    .animation-delay-2000 {
      animation-delay: 2s;
    }
  </style>
 <script src="scripts/auth.js"></script>
</body>
</html>
