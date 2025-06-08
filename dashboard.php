<?php
require_once('api/db.php');
session_start();

$hariIni = date('l');
$hariIndo = [
  'Sunday' => 'Minggu',
  'Monday' => 'Senin',
  'Tuesday' => 'Selasa',
  'Wednesday' => 'Rabu',
  'Thursday' => 'Kamis',
  'Friday' => 'Jumat',
  'Saturday' => 'Sabtu',
];
$hari = $hariIndo[$hariIni] ?? 'Senin';

$stmt = $conn->prepare("SELECT * FROM jadwal WHERE hari = ? ORDER BY jam_mulai ASC");
$stmt->bind_param("s", $hari);
$stmt->execute();
$result = $stmt->get_result();

$jadwalHariIni = [];
while ($row = $result->fetch_assoc()) {
  $jadwalHariIni[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - WebSchedule</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
</head>
<body class="min-h-screen bg-gradient-to-tr from-indigo-700 via-purple-700 to-pink-600 flex flex-col">

  <!-- Header -->
  <header class="flex justify-between items-center px-8 py-4 bg-white bg-opacity-20 backdrop-blur-md shadow-md">
    <h1 class="text-3xl font-extrabold text-white drop-shadow-lg">WebSchedule</h1>
    <nav class="space-x-6">
      <a href="logout.php" class="text-white font-semibold hover:text-pink-300 transition">Logout</a>
    </nav>
  </header>

  <!-- Main content -->
  <main class="flex-grow flex flex-col items-center justify-start px-4 py-10">
    <div class="w-full max-w-4xl bg-white bg-opacity-20 backdrop-blur-lg p-8 rounded-2xl shadow-lg">
      <h2 class="text-2xl font-bold text-white mb-6 drop-shadow">Jadwal Kuliah Hari Ini (<?= htmlspecialchars($hari) ?>)</h2>

      <?php if (count($jadwalHariIni) > 0): ?>
        <div class="space-y-4">
          <?php foreach ($jadwalHariIni as $jadwal): ?>
            <div class="bg-white bg-opacity-30 backdrop-blur-md p-4 rounded-md shadow-md border-l-4 border-pink-500">
              <p class="text-white text-lg font-semibold"><?= htmlspecialchars($jadwal['mata_kuliah']) ?></p>
              <p class="text-white text-sm"><?= htmlspecialchars($jadwal['jam_mulai']) ?> - <?= htmlspecialchars($jadwal['jam_selesai']) ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p class="text-white text-sm">Tidak ada jadwal kuliah hari ini.</p>
      <?php endif; ?>

      <div class="mt-10 grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
        <a href="jadwal.php" class="bg-pink-500 hover:bg-pink-600 text-white font-semibold py-2 rounded-lg transition">Semua Jadwal</a>
        <a href="todolist.php" class="bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-2 rounded-lg transition">To-Do List</a>
        <a href="notes.php" class="bg-purple-500 hover:bg-purple-600 text-white font-semibold py-2 rounded-lg transition">Catatan</a>
        <a href="profil.php" class="bg-white bg-opacity-20 hover:bg-opacity-40 text-white font-semibold py-2 rounded-lg transition">Profil</a>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-white bg-opacity-20 backdrop-blur-md shadow-inner text-white text-center py-4 font-semibold">
    &copy; 2025 WebSchedule. Semua hak cipta dilindungi.
  </footer>

</body>
</html>
