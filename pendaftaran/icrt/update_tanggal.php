<?php
include '../../config/database.php';

$id = $_POST['id'] ?? '';
$tanggal = $_POST['jadwal_wawancara'] ?? '';

if (!empty($id) && !empty($tanggal)) {
    $query = "UPDATE pendaftar SET jadwal_wawancara = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $tanggal, $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => true, 'message' => 'Tanggal wawancara berhasil diupdate']);
    } else {
        echo json_encode(['status' => false, 'message' => 'Gagal mengupdate tanggal']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => false, 'message' => 'Data tidak lengkap']);
}

$conn->close();
?>
