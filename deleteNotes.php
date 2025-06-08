<?php
session_start();
include 'api/db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
  header("Location: notes.php");
  exit();
}

$id = intval($_GET['id']);

// Hapus catatan hanya jika milik user
$stmt = $conn->prepare("DELETE FROM catatan WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();

header("Location: notes.php");
exit();
