<?php
$host = 'localhost';      
$user = 'root';           
$password = '';           
$dbname = 'scheduleapps'; 
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Koneksi database gagal: ' . $conn->connect_error]);
    exit;
}
$conn->set_charset("utf8mb4");
?>
