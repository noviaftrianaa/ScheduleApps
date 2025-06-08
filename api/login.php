<?php
session_start();
header('Content-Type: application/json');
include 'db.php'; // Pastikan path ini benar dan db.php hanya berisi koneksi tanpa output apapun

// Ambil data JSON dari request body
$data = json_decode(file_get_contents('php://input'), true);

$username = trim($data['username'] ?? '');
$password = trim($data['password'] ?? '');

if (!$username || !$password) {
    echo json_encode(['success' => false, 'message' => 'Username dan password wajib diisi.']);
    exit;
}

// Cari user berdasarkan username
$stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    // Verifikasi password
    if (password_verify($password, $user['password'])) {
        // Simpan session user
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        echo json_encode(['success' => true, 'message' => 'Login berhasil.']);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Password salah.']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Username tidak ditemukan.']);
    exit;
}
?>
