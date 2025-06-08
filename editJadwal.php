<?php
session_start();
include 'api/db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

// Pastikan ada parameter id
if (!isset($_GET['id'])) {
  header("Location: jadwal.php"); // ganti ke halaman daftar jadwal
  exit();
}

$id = intval($_GET['id']);

// Ambil data jadwal yang mau diedit, pastikan milik user
$stmt = $conn->prepare("SELECT * FROM jadwal WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  // Jadwal tidak ditemukan atau bukan milik user
  header("Location: jadwal.php");
  exit();
}

$jadwal = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama_mata_kuliah = $_POST['nama_mata_kuliah'];
  $hari = $_POST['hari'];
  $jam_mulai = $_POST['jam_mulai'];
  $jam_selesai = $_POST['jam_selesai'];

  $stmt = $conn->prepare("UPDATE jadwal SET nama_mata_kuliah = ?, hari = ?, jam_mulai = ?, jam_selesai = ? WHERE id = ? AND user_id = ?");
  $stmt->bind_param("ssssii", $nama_mata_kuliah, $hari, $jam_mulai, $jam_selesai, $id, $user_id);
  $stmt->execute();

  header("Location: jadwal.php");
  exit();
}

$hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit Jadwal | WebSchedule</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-indigo-700 via-purple-700 to-pink-600 min-h-screen text-gray-800">

  <header class="flex justify-between items-center px-8 py-4 bg-white bg-opacity-20 backdrop-blur-md shadow-md">
    <h1 class="text-3xl font-extrabold text-white drop-shadow-lg">WebSchedule</h1>
    <nav class="space-x-6">
      <a href="dashboard.php" class="text-white font-semibold hover:text-pink-300 transition">Dashboard</a>
      <a href="jadwal.php" class="text-white font-semibold hover:text-pink-300 transition">Kembali ke Jadwal</a>
      <a href="logout.php" class="text-white font-semibold hover:text-pink-300 transition">Logout</a>
    </nav>
  </header>

  <main class="max-w-4xl mx-auto mt-10 p-6 bg-white bg-opacity-90 backdrop-blur-md rounded-2xl shadow-lg">
    <h2 class="text-3xl font-bold text-center text-indigo-700 mb-6">Edit Jadwal Kuliah</h2>

    <form action="" method="POST" class="space-y-4">
      <input type="text" name="nama_mata_kuliah" placeholder="Nama Mata Kuliah" required
        class="w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-indigo-500"
        value="<?= htmlspecialchars($jadwal['nama_mata_kuliah']) ?>">

      <select name="hari" required
        class="w-full px-4 py-2 rounded-md border border-gray-300 bg-white focus:ring-2 focus:ring-indigo-500">
        <option value="">-- Pilih Hari --</option>
        <?php foreach ($hariList as $hari): ?>
          <option value="<?= $hari ?>" <?= $jadwal['hari'] === $hari ? 'selected' : '' ?>><?= $hari ?></option>
        <?php endforeach; ?>
      </select>

      <div class="flex flex-col sm:flex-row gap-4">
        <label class="flex-1">
          <span class="text-sm font-medium text-gray-700">Jam Mulai:</span>
          <input type="time" name="jam_mulai" required
            class="w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-indigo-500"
            value="<?= htmlspecialchars($jadwal['jam_mulai']) ?>">
        </label>
        <label class="flex-1">
          <span class="text-sm font-medium text-gray-700">Jam Selesai:</span>
          <input type="time" name="jam_selesai" required
            class="w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-indigo-500"
            value="<?= htmlspecialchars($jadwal['jam_selesai']) ?>">
        </label>
      </div>

      <button type="submit"
        class="bg-pink-500 hover:bg-pink-600 text-white font-semibold py-2 w-full rounded-md transition duration-300 shadow-md">
        Simpan Perubahan
      </button>
    </form>
  </main>
</body>
</html>
