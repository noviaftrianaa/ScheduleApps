<?php
session_start();
header('Content-Type: application/json');
include 'db.php'; // Pastikan path ini benar dan db.php hanya berisi koneksi tanpa output apapun

// Ambil data JSON dari request body
$data = json_decode(file_get_contents('php://input'), true);

$username = trim($data['username'] ?? '');
$email = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');

if (!$username || !$email || !$password) {
    echo json_encode(['success' => false, 'message' => 'Mohon lengkapi semua kolom.']);
    exit;
}

// Cek apakah email sudah terdaftar
$stmtCheck = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmtCheck->bind_param("s", $email);
$stmtCheck->execute();
$stmtCheck->store_result();

if ($stmtCheck->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Email sudah terdaftar.']);
    exit;
}

// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Simpan data user baru ke database
$stmtInsert = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
$stmtInsert->bind_param("sss", $username, $email, $hashedPassword);

if ($stmtInsert->execute()) {
    echo json_encode(['success' => true, 'message' => 'Registrasi berhasil.']);
    exit;
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal mendaftarkan pengguna.']);
    exit;
}
?>
