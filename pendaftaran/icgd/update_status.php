<?php
include '../../config/database.php';

$id = $_POST['id'] ?? '';
$status = $_POST['status'] ?? '';

if (!empty($id) && !empty($status)) {
    $query = "UPDATE icgd SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => true, 'message' => 'Status berhasil diupdate']);
    } else {
        echo json_encode(['status' => false, 'message' => 'Gagal mengupdate status']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => false, 'message' => 'Data tidak lengkap']);
}

$conn->close();
?>
