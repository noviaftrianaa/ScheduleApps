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
    $result = $conn->query("SELECT * FROM catatan WHERE user_id = $user_id ORDER BY id DESC");
    $catatans = [];
    while ($row = $result->fetch_assoc()) {
        $catatans[] = $row;
    }
    echo json_encode($catatans);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $conn->real_escape_string($_POST['judul'] ?? '');
    $deskripsi = $conn->real_escape_string($_POST['deskripsi'] ?? '');
    $isi_catatan = $conn->real_escape_string($_POST['isi_catatan'] ?? '');

    if (!$judul) {
        echo json_encode(['success' => false, 'message' => 'Judul wajib diisi']);
        exit;
    }

    $sql = "INSERT INTO catatan (user_id, judul, deskripsi, isi_catatan) VALUES ($user_id, '$judul', '$deskripsi', '$isi_catatan')";
    if ($conn->query($sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request']);
