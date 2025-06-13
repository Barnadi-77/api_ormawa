<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 0);
// ini_set('log_errors', 1);
// ini_set('error_log', __DIR__.'/error.log');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require_once '../../config/database.php';

if (!$conn) {
    echo json_encode(['status' => false, 'message' => 'Koneksi database gagal']);
    exit;
}

function uploadFile($inputName, $targetFolder)
{
    if (!isset($_FILES[$inputName])) {
        return ['status' => false, 'message' => "$inputName tidak ditemukan"];
    }

    $file = $_FILES[$inputName];
    $filename = time() . '_' . basename($file['name']);
    $uploadDir = __DIR__ . "/../../uploads/voli/$targetFolder/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $targetPath = $uploadDir . $filename;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['status' => true, 'filename' => $filename];
    } else {
        return ['status' => false, 'message' => "Gagal upload $inputName"];
    }
}


// Ambil data dari form
$nama = $_POST['nama'] ?? '';
$nim = $_POST['nim'] ?? '';
$email = $_POST['email'] ?? '';
$kelas = $_POST['kelas'] ?? '';
$angkatan = $_POST['angkatan'] ?? '';
$kontak = $_POST['kontak'] ?? '';
$prodi = $_POST['prodi'] ?? '';
$motivasi = $_POST['motivasi'] ?? '';

// Upload file satu per satu
$foto = uploadFile('foto_diri', 'foto_diri');
$ktm = uploadFile('ktm', 'ktm');


// Cek apakah semua file berhasil diupload
if (!$foto['status'] || !$ktm['status']) {
    $errorMessage = '';
    if (!$foto['status']) {
        $errorMessage = $foto['message'];
    } elseif (!$ktm['status']) {
        $errorMessage = $ktm['message'];
    } 

    echo json_encode([
        'status' => false,
        'message' => $errorMessage
    ]);
    exit;
}

// Simpan ke database
$stmt = $conn->prepare("INSERT INTO voli (nama, nim, email, kelas, angkatan, kontak, prodi, motivasi, foto_diri, ktm) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

if (!$stmt) {
    echo json_encode(['status' => false, 'message' => 'Prepare statement gagal: ' . $conn->error]);
    exit;
}

$stmt->bind_param(
    "ssssssssss",
    $nama,
    $nim,
    $email,
    $kelas,
    $angkatan,
    $kontak,
    $prodi,
    $motivasi,
    $foto['filename'],
    $ktm['filename']
);

if ($stmt->execute()) {
    echo json_encode(['status' => true, 'message' => 'Data berhasil disimpan']);
} else {
    echo json_encode(['status' => false, 'message' => 'Gagal simpan ke database: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>