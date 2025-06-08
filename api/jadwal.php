<?php
include 'db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Anda harus login']);
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_GET['action'] ?? '';

if ($action == 'list') {
    $result = $conn->query("SELECT * FROM jadwal WHERE user_id = $user_id ORDER BY FIELD(hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'), jam_mulai");
    $jadwals = [];
    while ($row = $result->fetch_assoc()) {
        $jadwals[] = $row;
    }
    echo json_encode($jadwals);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_mk = $conn->real_escape_string($_POST['nama_mata_kuliah'] ?? '');
    $hari = $conn->real_escape_string($_POST['hari'] ?? '');
    $jam_mulai = $conn->real_escape_string($_POST['jam_mulai'] ?? '');
    $jam_selesai = $conn->real_escape_string($_POST['jam_selesai'] ?? '');

    if (!$nama_mk || !$hari || !$jam_mulai || !$jam_selesai) {
        echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
        exit;
    }

    $sql = "INSERT INTO jadwal (user_id, nama_mata_kuliah, hari, jam_mulai, jam_selesai) VALUES ($user_id, '$nama_mk', '$hari', '$jam_mulai', '$jam_selesai')";
    if ($conn->query($sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request']);
