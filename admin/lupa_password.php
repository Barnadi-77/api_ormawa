<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include_once '../config/database.php';

// Cek method request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Metode tidak diizinkan"]);
    exit;
}

// Ambil data dari request
$username = trim($_POST['username'] ?? '');
$newPassword = $_POST['new_password'] ?? '';
$role = $_POST['role'] ?? '';

// Validasi input
if (empty($username) || empty($newPassword) || empty($role)) {
    echo json_encode(["status" => "error", "message" => "Parameter tidak lengkap."]);
    exit;
}

// Hash password baru
$newPasswordHashed = password_hash($newPassword, PASSWORD_DEFAULT);

// Cek apakah user dengan role tersebut ada
$stmt = $conn->prepare("SELECT * FROM admin_universal WHERE username = ? AND role = ?");
$stmt->bind_param("ss", $username, $role);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

if ($admin) {
    // Lakukan update password
    $stmt = $conn->prepare("UPDATE admin_universal SET password = ? WHERE username = ? AND role = ?");
    $stmt->bind_param("sss", $newPasswordHashed, $username, $role);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Password berhasil diperbarui."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal mengubah password."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Username atau role tidak ditemukan."]);
}
exit;
?>
