<?php
include '../../config/database.php';

header('Content-Type: application/json');

$sql = "SELECT 
            nama, nim, kelas, prodi, 
            CONCAT('http://10.0.2.2/api_ormawa/uploads/icrt/foto_diri/', foto_diri) AS foto_diri,
            status_wawancara
        FROM 
            pendaftar
        WHERE 
            LOWER(status_wawancara) = 'lulus' OR LOWER(status_wawancara) = 'tidak lulus'
        ORDER BY 
            created_at DESC";

$result = $conn->query($sql);
$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Tambahkan keterangan lulus sebagai 'Lulus' atau 'Tidak Lulus'
        $row['keterangan'] = ($row['status_wawancara'] === 'Lulus') ? 'Lulus' : 'Tidak Lulus';
        unset($row['status_wawancara']);  // hapus kolom status_wawancara jika tidak perlu tampil
        $data[] = $row;
    }
    echo json_encode($data);
} else {
    echo json_encode([]);
}

$conn->close();
?>
