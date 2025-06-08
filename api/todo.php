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
    $result = $conn->query("SELECT * FROM todo WHERE user_id = $user_id ORDER BY deadline ASC");
    $todos = [];
    while ($row = $result->fetch_assoc()) {
        $todos[] = $row;
    }
    echo json_encode($todos);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_kegiatan = $conn->real_escape_string($_POST['nama_kegiatan'] ?? '');
    $deadline = $conn->real_escape_string($_POST['deadline'] ?? '');
    if (!$nama_kegiatan || !$deadline) {
        echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
        exit;
    }
    $sql = "INSERT INTO todo (user_id, nama_kegiatan, deadline) VALUES ($user_id, '$nama_kegiatan', '$deadline')";
    if ($conn->query($sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
    exit;
}

if ($action == 'toggle' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = "UPDATE todo SET selesai = !selesai WHERE id = $id AND user_id = $user_id";
    if ($conn->query($sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request']);
