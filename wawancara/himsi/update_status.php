<?php
include '../../config/database.php';
header("Content-Type: application/json");

$id = $_POST['id'];
$status = $_POST['status'];

$sql = "UPDATE himsi SET status_wawancara = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $id);

if ($stmt->execute()) {
    echo json_encode(["status" => true, "message" => "Status wawancara berhasil diperbarui"]);
} else {
    echo json_encode(["status" => false, "message" => "Gagal mengubah status wawancara"]);
}

$stmt->close();
$conn->close();
?>
