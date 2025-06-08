<?php
session_start();
include 'api/db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$id = $_GET['id'] ?? null;
if ($id) {
  $stmt = $conn->prepare("DELETE FROM jadwal WHERE id = ? AND user_id = ?");
  $stmt->bind_param("ii", $id, $_SESSION['user_id']);
  $stmt->execute();
}

header("Location: jadwal.php");
exit();
