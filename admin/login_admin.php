<?php
header("Content-Type: application/json");
include_once '../config/database.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? '';

if (!$username || !$password || !$role) {
    echo json_encode(["status" => "error", "message" => "Parameter tidak lengkap"]);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM admin_universal WHERE username = ? AND role = ?");
$stmt->bind_param("ss", $username, $role);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    echo json_encode(["status" => "success", "message" => "Login berhasil", "role" => $user['role']]);
} else {
    echo json_encode(["status" => "error", "message" => "Username/password salah"]);
}
