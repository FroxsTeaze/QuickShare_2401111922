<?php
$host = "127.0.0.1";
$username = "root";
$password = "";
$database = "uas";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data peminjam dari database
$sql = "SELECT * FROM peminjam";
$result = $conn->query($sql);

$peminjamData = [];

if (isset($_POST['Keterangan'])) {
    $Keterangan = $_POST['Keterangan'];
} else {
    $Keterangan = "1, 2, 3, 4"; // Atau nilai default lain yang sesuai
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $peminjamData[] = $row;
    }
}

// Bagian dari kode yang menangani aksi tombol
if (isset($_GET['action']) && isset($_GET['no'])) {
    $action = $_GET['action'];
    $no = $_GET['no'];
    $nomorLoker = substr($action, -1);  // Mengambil angka dari nama aksi, misal "tombol1" menjadi 1

    // Perbarui nomor loker dalam database
    $updateSql = "UPDATE peminjam SET nomor_loker = ? WHERE NO = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("ii", $nomorLoker, $no);
    if ($stmt->execute()) {
        // Redirect kembali ke halaman admin setelah melakukan tindakan
        header('Location: Adminpage.php');
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="Adminpagestyle.css">
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <h1 class="mb-4">Welcome, Admin!</h1>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Daftar admin:</h5>
                <ul>
                    <li>Achmad Risky Muzakky</li>
                </ul>
            </div>
        </div>


        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Prodi</th>
                    <th>Alamat</th>
                    <th>Nomor WA</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($peminjamData as $peminjam) : ?>
                    <tr>
                        <td><?= $peminjam['NO']; ?></td>
                        <td><?= $peminjam['nim']; ?></td>
                        <td><?= $peminjam['nama']; ?></td>
                        <td><?= $peminjam['prodi']; ?></td>
                        <td><?= $peminjam['alamat']; ?></td>
                        <td><?= $peminjam['nomor_wa'] ?></td>
                        <td><?= $peminjam['Keterangan']; ?></td>
                        <td>
                        <!-- Tombol Proses -->
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#prosesModal<?= $peminjam['NO']; ?>">
                                Proses
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="prosesModal<?= $peminjam['NO']; ?>" tabindex="-1" role="dialog" aria-labelledby="prosesModalLabel" aria-hidden="true">
                                <!-- (kode modal yang ada sebelumnya) -->
                                <div class="modal-body">
                                    <!-- Isi modal disini, contoh 4 tombol -->
                                    <a href="Adminpage.php?action=tombol1&no=<?= $peminjam['NO']; ?>" class="btn btn-primary">Tombol 1</a>
                                    <a href="Adminpage.php?action=tombol2&no=<?= $peminjam['NO']; ?>" class="btn btn-primary">Tombol 2</a>
                                    <a href="Adminpage.php?action=tombol3&no=<?= $peminjam['NO']; ?>" class="btn btn-primary">Tombol 3</a>
                                    <a href="Adminpage.php?action=tombol4&no=<?= $peminjam['NO']; ?>" class="btn btn-primary">Tombol 4</a>
                                </div>
                            </div>

                        
                        <!-- Tombol Hapus -->
                        <a href="hapusPeminjam.php?no=<?= $peminjam['NO']; ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data?')">Hapus</a>
                        </td>

                        
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    
    
</body>
</html>
</html>