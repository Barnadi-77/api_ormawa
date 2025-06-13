<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include_once '../../config/database.php';

// URL dasar untuk gambar—ubah sesuai path server-mu
$base_url = 'http://10.0.2.2/api_ormawa/uploads/icgd/foto_diri/'; 

$sql = "
  SELECT 
    id, nama, nim, kelas, prodi, angkatan, foto_diri, status, jadwal_wawancara, status_wawancara 
  FROM icgd
  WHERE LOWER(status) = 'diterima' OR LOWER(status) = 'ditolak'
  ORDER BY nama ASC
";

$result = $conn->query($sql);

$data = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'id'                => $row['id'],
            'nama'              => $row['nama'],
            'nim'               => $row['nim'],
            'kelas'             => $row['kelas'],
            'prodi'             => $row['prodi'],
            'angkatan'          => $row['angkatan'],
            'foto_diri'         => $base_url . $row['foto_diri'],
            'status'        => $row['status'],  
            'status_wawancara'        => $row['status_wawancara'],  
            'jadwal_wawancara'  => $row['jadwal_wawancara'] ?? '',
        ];
    }
}

echo json_encode($data, JSON_UNESCAPED_SLASHES);
$conn->close();
?>
