<?php
session_start();
include 'api/db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

// Tambah Jadwal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah'])) {
  $nama_mata_kuliah = $_POST['nama_mata_kuliah'];
  $hari = $_POST['hari'];
  $jam_mulai = $_POST['jam_mulai'];
  $jam_selesai = $_POST['jam_selesai'];

  $stmt = $conn->prepare("INSERT INTO jadwal (user_id, nama_mata_kuliah, hari, jam_mulai, jam_selesai) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("issss", $user_id, $nama_mata_kuliah, $hari, $jam_mulai, $jam_selesai);
  $stmt->execute();
}

// Ambil data jadwal
$jadwal_hari_ini = [];
$stmt = $conn->prepare("SELECT * FROM jadwal WHERE user_id = ? ORDER BY FIELD(hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'), jam_mulai ASC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
  $jadwal_hari_ini[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Manajemen Jadwal Kuliah | WebSchedule</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex flex-col min-h-screen bg-gradient-to-br from-indigo-700 via-purple-700 to-pink-600 text-gray-800">

  <header class="flex justify-between items-center px-8 py-4 bg-white bg-opacity-20 backdrop-blur-md shadow-md">
    <h1 class="text-3xl font-extrabold text-white drop-shadow-lg">WebSchedule</h1>
    <nav class="space-x-6">
      <a href="dashboard.php" class="text-white font-semibold hover:text-pink-300 transition">Dashboard</a>
      <a href="logout.php" class="text-white font-semibold hover:text-pink-300 transition">Logout</a>
    </nav>
  </header>

  <main class="flex-grow max-w-6xl mx-auto mt-10 p-6 bg-white bg-opacity-90 backdrop-blur-md rounded-2xl shadow-lg w-full">
    <h2 class="text-3xl font-bold text-center text-indigo-700 mb-6">Manajemen Jadwal Kuliah</h2>

    <!-- Form Tambah Jadwal -->
    <form action="" method="POST" class="space-y-4 mb-10">
      <input type="hidden" name="tambah" value="1">
      <input type="text" name="nama_mata_kuliah" placeholder="Nama Mata Kuliah" required
        class="w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-indigo-500">

      <select name="hari" required
        class="w-full px-4 py-2 rounded-md border border-gray-300 bg-white focus:ring-2 focus:ring-indigo-500">
        <option value="">-- Pilih Hari --</option>
        <?php
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        foreach ($hariList as $hari) {
          echo "<option value=\"$hari\">$hari</option>";
        }
        ?>
      </select>

      <div class="flex flex-col sm:flex-row gap-4">
        <label class="flex-1">
          <span class="text-sm font-medium text-gray-700">Jam Mulai:</span>
          <input type="time" name="jam_mulai" required
            class="w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-indigo-500">
        </label>
        <label class="flex-1">
          <span class="text-sm font-medium text-gray-700">Jam Selesai:</span>
          <input type="time" name="jam_selesai" required
            class="w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-indigo-500">
        </label>
      </div>

      <button type="submit"
        class="bg-pink-500 hover:bg-pink-600 text-white font-semibold py-2 w-full rounded-md transition duration-300 shadow-md">
        Simpan Jadwal
      </button>
    </form>

    <!-- Daftar Jadwal -->
    <h3 class="text-xl font-semibold text-indigo-700 mb-3">Daftar Jadwal</h3>

    <?php if (empty($jadwal_hari_ini)): ?>
      <p class="text-gray-600">Belum ada jadwal yang ditambahkan.</p>
    <?php else: ?>
      <div class="overflow-x-auto">
        <table class="w-full table-auto border-collapse border border-gray-300 rounded-md">
 <thead>
  <tr class="bg-purple-200 bg-opacity-30">
    <th class="border border-gray-300 px-4 py-2 text-left text-purple-800 font-semibold">Mata Kuliah</th>
    <th class="border border-gray-300 px-4 py-2 text-left text-purple-800 font-semibold">Hari</th>
    <th class="border border-gray-300 px-4 py-2 text-left text-purple-800 font-semibold">Jam</th>
    <th class="border border-gray-300 px-4 py-2 text-center text-purple-800 font-semibold">Aksi</th>
  </tr>
</thead>

          <tbody>
            <?php foreach ($jadwal_hari_ini as $jadwal): ?>
              <tr class="bg-white hover:bg-indigo-50">
                <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($jadwal['nama_mata_kuliah']) ?></td>
                <td class="border border-gray-300 px-4 py-2"><?= $jadwal['hari'] ?></td>
                <td class="border border-gray-300 px-4 py-2"><?= $jadwal['jam_mulai'] ?> - <?= $jadwal['jam_selesai'] ?></td>
                <td class="border border-gray-300 px-4 py-2 text-center space-x-2">
                  <a href="editJadwal.php?id=<?= $jadwal['id'] ?>" class="text-indigo-600 font-semibold hover:underline">Edit</a>
                  <a href="deleteJadwal.php?id=<?= $jadwal['id'] ?>" onclick="return confirm('Yakin ingin menghapus jadwal ini?')" class="text-red-600 font-semibold hover:underline">Hapus</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>

  </main>

  <footer class="bg-white bg-opacity-20 backdrop-blur-md text-white text-center py-4 font-semibold mt-12">
    &copy; 2025 WebSchedule. Semua hak cipta dilindungi.
  </footer>

</body>
</html>
