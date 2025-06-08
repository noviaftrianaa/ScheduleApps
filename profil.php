<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'api/db.php';

$user_id = $_SESSION['user_id'];

// Ambil data pengguna dari database
$stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "Pengguna tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna | WebSchedule</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-tr from-indigo-700 via-purple-700 to-pink-600 flex items-center justify-center">

    <div class="bg-white bg-opacity-90 backdrop-blur-md p-8 rounded-2xl shadow-lg w-full max-w-md">
        <h2 class="text-3xl font-bold text-indigo-700 text-center mb-6">Profil Pengguna</h2>

        <div class="flex flex-col items-center space-y-4">
            <div class="w-24 h-24 rounded-full bg-gradient-to-tr from-pink-500 to-purple-600 text-white flex items-center justify-center text-2xl font-bold shadow-lg">
                <?= strtoupper(substr($user['username'], 0, 1)) ?>
            </div>

            <div class="text-center">
                <h3 class="text-xl font-semibold text-gray-800"><?= htmlspecialchars($user['username']) ?></h3>
                <p class="text-gray-600"><?= htmlspecialchars($user['email']) ?></p>
            </div>

            <a href="dashboard.php" class="mt-6 inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2 rounded-md shadow transition">
                Kembali ke Dashboard
            </a>
        </div>
    </div>

</body>
</html>
