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
  header("Location: notes.php");
  exit();
}

$id = intval($_GET['id']);

// Ambil data catatan yang mau diedit, pastikan milik user
$stmt = $conn->prepare("SELECT * FROM catatan WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  // Catatan tidak ditemukan atau bukan milik user
  header("Location: notes.php");
  exit();
}

$catatan = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $isi = $_POST['isi'];

    $stmt = $conn->prepare("UPDATE catatan SET judul = ?, deskripsi = ?, isi = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("sssii", $judul, $deskripsi, $isi, $id, $user_id);
    $stmt->execute();

    header("Location: notes.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit Catatan | WebSchedule</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-indigo-700 via-purple-700 to-pink-600 min-h-screen text-gray-800">

  <header class="flex justify-between items-center px-8 py-4 bg-white bg-opacity-20 backdrop-blur-md shadow-md">
    <h1 class="text-3xl font-extrabold text-white drop-shadow-lg">WebSchedule</h1>
    <nav class="space-x-6">
      <a href="dashboard.php" class="text-white font-semibold hover:text-pink-300 transition">Dashboard</a>
      <a href="notes.php" class="text-white font-semibold hover:text-pink-300 transition">Kembali ke Catatan</a>
      <a href="logout.php" class="text-white font-semibold hover:text-pink-300 transition">Logout</a>
    </nav>
  </header>

  <main class="max-w-4xl mx-auto mt-10 p-6 bg-white bg-opacity-90 backdrop-blur-md rounded-2xl shadow-lg">
    <h2 class="text-3xl font-bold text-center text-indigo-700 mb-6">Edit Catatan</h2>

    <form action="" method="POST" class="space-y-4">
      <input type="text" name="judul" placeholder="Judul Catatan" required
        class="w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-indigo-500"
        value="<?= htmlspecialchars($catatan['judul']) ?>">

      <input type="text" name="deskripsi" placeholder="Deskripsi Singkat" required
        class="w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-indigo-500"
        value="<?= htmlspecialchars($catatan['deskripsi']) ?>">

      <textarea name="isi" rows="5" placeholder="Isi Catatan" required
        class="w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-indigo-500 resize-y"><?= htmlspecialchars($catatan['isi']) ?></textarea>

      <button type="submit"
        class="bg-pink-500 hover:bg-pink-600 text-white font-semibold py-2 w-full rounded-md transition duration-300 shadow-md">
        Simpan Perubahan
      </button>
    </form>
  </main>
</body>
</html>
