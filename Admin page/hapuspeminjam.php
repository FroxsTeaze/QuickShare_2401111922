<?php
$host = "127.0.0.1";
$username = "root";
$password = "";
$database = "uas";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil ID dari URL
$no = $_GET['no'];

// Hapus data berdasarkan ID
$sqlDelete = "DELETE FROM peminjam WHERE NO = $no";
$resultDelete = $conn->query($sqlDelete);

if ($resultDelete) {
    echo "Data berhasil dihapus";
} else {
    echo "Error: " . $sqlDelete . "<br>" . $conn->error;
}

// Reset AUTO_INCREMENT setelah penghapusan
$sqlResetAutoIncrement = "ALTER TABLE peminjam AUTO_INCREMENT = 1";
$conn->query($sqlResetAutoIncrement);

$conn->close();

// Redirect kembali ke halaman admin setelah menghapus
header("Location: adminpage.php");
?>