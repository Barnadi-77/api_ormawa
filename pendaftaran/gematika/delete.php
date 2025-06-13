<?php
include '../../config/database.php';

$id = $_POST['id'] ?? '';

if (!empty($id)) {
    // Ambil nama semua file dari database
    $querySelect = "SELECT foto_diri, ktm FROM gematika WHERE id = ?";
    $stmtSelect = $conn->prepare($querySelect);
    $stmtSelect->bind_param("i", $id);
    $stmtSelect->execute();
    $result = $stmtSelect->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Daftar file dan path folder masing-masing
        $files = [
            ['filename' => $row['foto_diri'], 'folder' => '../../uploads/gematika/foto_diri/'],
            ['filename' => $row['ktm'], 'folder' => '../../uploads/gematika/ktm/'],
        ];

        // Hapus semua file jika ada
        foreach ($files as $file) {
            $filePath = $file['folder'] . $file['filename'];
            if (!empty($file['filename']) && file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Hapus data dari database
        $queryDelete = "DELETE FROM gematika WHERE id = ?";
        $stmtDelete = $conn->prepare($queryDelete);
        $stmtDelete->bind_param("i", $id);

        if ($stmtDelete->execute()) {
            echo json_encode(['status' => true, 'message' => 'Data dan semua file berhasil dihapus']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal menghapus data']);
        }

        $stmtDelete->close();
    } else {
        echo json_encode(['status' => false, 'message' => 'Data tidak ditemukan']);
    }

    $stmtSelect->close();
} else {
    echo json_encode(['status' => false, 'message' => 'ID tidak ditemukan']);
}

$conn->close();
?>
