<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$user_id = $_SESSION['user_id'];

require_once 'api/db.php'; // Koneksi ke database

// Ambil ID dari URL
if (!isset($_GET['id'])) {
    header('Location: todolist.php');
    exit;
}

$id = intval($_GET['id']);

// Proses update saat form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_kegiatan = $_POST['nama_kegiatan'] ?? '';
    $deadline = $_POST['deadline'] ?? '';

    if ($nama_kegiatan && $deadline) {
        $stmt = $conn->prepare("UPDATE todolist SET nama_kegiatan = ?, deadline = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ssii", $nama_kegiatan, $deadline, $id, $user_id);
        $stmt->execute();
        $stmt->close();
        header('Location: todolist.php');
        exit;
    }
}

// Ambil data yang akan diedit
$stmt = $conn->prepare("SELECT nama_kegiatan, deadline FROM todolist WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$todolist = $result->fetch_assoc();

if (!$todolist) {
    echo "Data tidak ditemukan atau Anda tidak memiliki akses.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit To-Do | WebSchedule</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-indigo-700 via-purple-700 to-pink-600 min-h-screen text-gray-800">

  <!-- Header Sama Persis -->
  <header class="flex justify-between items-center px-8 py-4 bg-white bg-opacity-20 backdrop-blur-md shadow-md">
    <h1 class="text-3xl font-extrabold text-white drop-shadow-lg">WebSchedule</h1>
    <nav class="space-x-6">
      <a href="dashboard.php" class="text-white font-semibold hover:text-pink-300 transition">Dashboard</a>
      <a href="todolist.php" class="text-white font-semibold hover:text-pink-300 transition">Kembali ke To-Do List</a>
      <a href="logout.php" class="text-white font-semibold hover:text-pink-300 transition">Logout</a>
    </nav>
  </header>

  <!-- Form Edit To-Do -->
  <div class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white bg-opacity-90 backdrop-blur-md p-8 rounded-2xl shadow-lg w-full max-w-md">
      <h2 class="text-2xl font-bold text-indigo-700 mb-6 text-center">Edit Kegiatan</h2>

      <form method="POST" class="space-y-4">
        <div>
          <label for="nama_kegiatan" class="block text-sm font-medium text-gray-700">Nama Kegiatan</label>
          <input type="text" name="nama_kegiatan" id="nama_kegiatan"
            value="<?= htmlspecialchars($todolist['nama_kegiatan']) ?>"
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500" required>
        </div>

        <div>
          <label for="deadline" class="block text-sm font-medium text-gray-700">Deadline</label>
          <input type="date" name="deadline" id="deadline"
            value="<?= htmlspecialchars($todolist['deadline']) ?>"
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500" required>
        </div>

        <div class="flex justify-between items-center mt-6">
          <a href="todolist.php" class="text-gray-600 hover:text-red-500 transition">Batal</a>
          <button type="submit"
            class="bg-pink-500 hover:bg-pink-600 text-white font-semibold px-4 py-2 rounded-md shadow">
            Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>

</body>
</html>
