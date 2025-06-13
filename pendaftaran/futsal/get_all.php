<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 0);
// ini_set('log_errors', 1);
// ini_set('error_log', __DIR__.'/error.log');

header('Content-Type: application/json');

include_once '../../config/database.php'; // 1 folder ke atas, lalu ke config

$query = "SELECT id, nama, nim, email, angkatan, kelas, kontak, prodi, motivasi, foto_diri, ktm, status, jadwal_wawancara FROM futsal ORDER BY created_at DESC";

$result = $conn->query($query);

$data = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['foto_diri'] = 'http://10.0.2.2/api_ormawa/uploads/futsal/foto_diri/' . $row['foto_diri']; // sesuaikan URL sesuai folder foto kamu
        $row['ktm'] = 'http://10.0.2.2/api_ormawa/uploads/futsal/ktm/' . $row['ktm']; // sesuaikan URL sesuai folder foto kamu
        $data[] = $row;
    }
}

echo json_encode($data);
?>
