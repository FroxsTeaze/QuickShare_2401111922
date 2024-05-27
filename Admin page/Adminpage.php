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


if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $peminjamData[] = $row;
    }
}


    // Perbarui nomor loker dalam database
    $updateSql = "UPDATE peminjam SET nomor_loker = ? WHERE NO = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("ii", $nomorLoker, $no); 


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
                    <th>Nomor Loker</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($peminjamData as $peminjam) : ?>
                    <tr>
                        <td><?= $peminjam['NO']; ?></td>
                        <td><?= $peminjam['nim']; ?></td>
                        <td><?= $peminjam['nama']; ?></td>
                        <td><?= $peminjam['nomor_loker']; ?></td>
                        <td>
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