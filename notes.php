<?php
session_start();
include 'api/db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

// Simpan data jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $isi = $_POST['isi'];

    $stmt = $conn->prepare("INSERT INTO catatan (user_id, judul, deskripsi, isi) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $judul, $deskripsi, $isi);
    $stmt->execute();
    header("Location: notes.php");
    exit();
}

// Ambil data catatan user
$catatan_list = [];
$stmt = $conn->prepare("SELECT * FROM catatan WHERE user_id = ? ORDER BY id DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $catatan_list[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Manajemen Catatan | WebSchedule</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-indigo-700 via-purple-700 to-pink-600 min-h-screen text-gray-800">

  <!-- Header -->
  <header class="flex justify-between items-center px-8 py-4 bg-white bg-opacity-20 backdrop-blur-md shadow-md">
    <h1 class="text-3xl font-extrabold text-white drop-shadow-lg">WebSchedule</h1>
    <nav class="space-x-6">
      <a href="dashboard.php" class="text-white font-semibold hover:text-pink-300 transition">Dashboard</a>
      <a href="logout.php" class="text-white font-semibold hover:text-pink-300 transition">Logout</a>
    </nav>
  </header>

  <!-- Konten Utama -->
  <main class="max-w-4xl mx-auto mt-10 p-6 bg-white bg-opacity-90 backdrop-blur-md rounded-2xl shadow-lg">
    <h2 class="text-3xl font-bold text-center text-indigo-700 mb-6">Manajemen Catatan</h2>

    <!-- Form Tambah Catatan -->
    <form action="" method="POST" class="space-y-4">
      <input type="text" name="judul" placeholder="Judul Catatan" required
        class="w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-indigo-500">

      <input type="text" name="deskripsi" placeholder="Deskripsi Singkat" required
        class="w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-indigo-500">

      <textarea name="isi" rows="5" placeholder="Isi Catatan" required
        class="w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-indigo-500 resize-y"></textarea>

      <button type="submit"
        class="bg-pink-500 hover:bg-pink-600 text-white font-semibold py-2 w-full rounded-md transition duration-300 shadow-md">
        Simpan Catatan
      </button>
    </form>

    <!-- Daftar Catatan -->
    <h3 class="text-xl font-semibold text-indigo-700 mt-10 mb-3">Daftar Catatan</h3>
    <div class="space-y-3">
      <?php if (empty($catatan_list)): ?>
        <p class="text-gray-600">Belum ada catatan yang ditambahkan.</p>
      <?php else: ?>
        <?php foreach ($catatan_list as $catatan): ?>
          <div class="bg-gray-100 rounded-md p-4 shadow-inner border-l-4 border-purple-500 flex justify-between items-start">
            <div>
              <p class="font-semibold text-lg"><?= htmlspecialchars($catatan['judul']) ?></p>
              <p class="italic text-sm text-gray-600 mb-2"><?= htmlspecialchars($catatan['deskripsi']) ?></p>
              <p class="text-gray-800 whitespace-pre-wrap"><?= htmlspecialchars($catatan['isi']) ?></p>
            </div>
            <div class="ml-4 flex flex-col space-y-2">
              <a href="editNotes.php?id=<?= $catatan['id'] ?>"
                class="text-indigo-600 hover:text-indigo-800 font-semibold text-sm">Edit</a>
              <a href="deleteNotes.php?id=<?= $catatan['id'] ?>" 
                onclick="return confirm('Yakin ingin menghapus catatan ini?');"
                class="text-red-600 hover:text-red-800 font-semibold text-sm">Hapus</a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-white bg-opacity-20 backdrop-blur-md text-white text-center py-4 font-semibold mt-12">
    &copy; 2025 WebSchedule. Semua hak cipta dilindungi.
  </footer>

</body>
</html>
