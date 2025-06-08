<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$user_id = $_SESSION['user_id'];

require_once 'api/db.php';

// Tambah data baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nama_kegiatan'])) {
    $nama_kegiatan = $_POST['nama_kegiatan'] ?? '';
    $deadline = $_POST['deadline'] ?? '';

    if ($nama_kegiatan && $deadline) {
        $stmt = $conn->prepare("INSERT INTO todolist (user_id, nama_kegiatan, deadline, ceklis) VALUES (?, ?, ?, 0)");
        $stmt->bind_param("iss", $user_id, $nama_kegiatan, $deadline);
        $stmt->execute();
        $stmt->close();
        header('Location: todolist.php');
        exit;
    }
}

// Update status ceklis (AJAX)
if (isset($_POST['toggle_id'])) {
    $id = intval($_POST['toggle_id']);
    $cek_status = intval($_POST['cek_status']);

    $stmt = $conn->prepare("UPDATE todolist SET ceklis = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("iii", $cek_status, $id, $user_id);
    $stmt->execute();
    $stmt->close();
    echo json_encode(['success' => true]);
    exit;
}

// Ambil daftar todo user
$stmt = $conn->prepare("SELECT id, nama_kegiatan, deadline, ceklis FROM todolist WHERE user_id = ? ORDER BY deadline ASC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>To-Do List | WebSchedule</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-tr from-indigo-700 via-purple-700 to-pink-600 flex flex-col">

    <header class="flex justify-between items-center px-8 py-4 bg-white bg-opacity-20 backdrop-blur-md shadow-md">
        <h1 class="text-3xl font-extrabold text-white drop-shadow-lg">WebSchedule - To-Do List</h1>
        <nav>
            <a href="dashboard.php" class="text-white font-semibold hover:text-pink-300 transition mr-6">Dashboard</a>
            <a href="logout.php" class="text-white font-semibold hover:text-pink-300 transition">Logout</a>
        </nav>
    </header>

    <main class="flex-grow max-w-3xl mx-auto bg-white bg-opacity-90 backdrop-blur-md rounded-2xl shadow-xl p-8 mt-10 mb-10">
        <h2 class="text-3xl font-bold text-indigo-700 mb-6 text-center">Daftar Kegiatan</h2>

        <!-- Form tambah tugas -->
        <form method="POST" class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <input type="text" name="nama_kegiatan" placeholder="Nama Kegiatan"
                   class="px-4 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-indigo-500" required />
            <input type="date" name="deadline"
                   class="px-4 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-indigo-500" required />
            <button type="submit"
                    class="bg-pink-500 hover:bg-pink-600 text-white font-semibold py-2 rounded-md transition duration-300 shadow-md">
                Tambah
            </button>
        </form>

        <!-- List tugas -->
        <div class="space-y-4 max-h-96 overflow-y-auto">
            <?php while ($row = $result->fetch_assoc()) : ?>
                <div class="p-4 rounded shadow flex justify-between items-center <?= $row['ceklis'] ? 'bg-green-100' : 'bg-white' ?>">
                    <div>
                        <p class="font-semibold <?= $row['ceklis'] ? 'line-through text-gray-500' : '' ?>">
                            <?= htmlspecialchars($row['nama_kegiatan']) ?>
                        </p>
                        <p class="text-sm text-gray-600">Deadline: <?= htmlspecialchars($row['deadline']) ?></p>
                        <div class="mt-2 space-x-2">
                            <a href="editTodolist.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:underline text-sm">Edit</a>
                            <a href="deleteTodolist.php?id=<?= $row['id'] ?>" class="text-red-600 hover:underline text-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                        </div>
                    </div>
                    <input type="checkbox" class="ceklist-todo" data-id="<?= $row['id'] ?>" <?= $row['ceklis'] ? 'checked' : '' ?> />
                </div>
            <?php endwhile; ?>
        </div>
    </main>

    <script>
        document.querySelectorAll('.ceklist-todo').forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                const id = checkbox.getAttribute('data-id');
                const cek_status = checkbox.checked ? 1 : 0;

                fetch('todolist.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        toggle_id: id,
                        cek_status: cek_status
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) {
                        alert('Gagal mengubah status');
                        checkbox.checked = !checkbox.checked;
                    } else {
                        window.location.reload(); // Refresh halaman biar warna update
                    }
                })
                .catch(() => {
                    alert('Terjadi kesalahan jaringan');
                    checkbox.checked = !checkbox.checked;
                });
            });
        });
    </script>
 <!-- Footer -->
  <footer class="bg-white bg-opacity-20 backdrop-blur-md text-white text-center py-4 font-semibold mt-12">
    &copy; 2025 WebSchedule. Semua hak cipta dilindungi.
  </footer>
</body>
</html>
