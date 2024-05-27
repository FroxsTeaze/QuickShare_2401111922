<?php
$host = "127.0.0.1"; // Sesuaikan dengan host database Anda
$username = "root"; // Sesuaikan dengan username database Anda
$password = ""; // Sesuaikan dengan password database Anda
$database = "uas"; // Sesuaikan dengan nama database Anda

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

function unlockLocker($lockerId) {
    $ipAddress = "192.168.1.100"; // Ganti dengan IP ESP32
    $url = "http://$ipAddress/unlock?id=$lockerId";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch); // Tampilkan pesan error jika ada
    }
    curl_close($ch);

    if ($response) {
        echo "Loker $lockerId telah dibuka!";
    } else {
        echo "Gagal membuka loker $lockerId.";
    }
}

// Tangani permintaan AJAX untuk membuka loker
if (isset($_GET['lockerId'])) {
    $lockerId = intval($_GET['lockerId']);
    unlockLocker($lockerId);
    exit;
}

$status_persetujuan = ""; // Inisialisasi status persetujuan

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $nim = isset($_POST['nim']) ? $_POST['nim'] : '';
    $nama = isset($_POST['nama']) ? $_POST['nama'] : '';
    $prodi = isset($_POST['prodi']) ? $_POST['prodi'] : '';
    $alamat = isset($_POST['alamat']) ? $_POST['alamat'] : '';
    $nomor_wa = isset($_POST['nomor_wa']) ? $_POST['nomor_wa'] : '';
    $Keterangan = isset($_POST['Keterangan'])? $_POST['Keterangan']: '';

    // Simpan data ke database
    $sql = "INSERT INTO peminjam (nim, nama, prodi, alamat, nomor_wa, Keterangan, status_persetujuan) 
    VALUES ('$nim', '$nama', '$prodi', '$alamat', '$nomor_wa', '$Keterangan', 'Menunggu persetujuan admin')";

    if ($conn->query($sql) === TRUE) {
        $status_persetujuan = "success"; // Set status menjadi success jika data berhasil disimpan
    } else {
        $status_persetujuan = "error"; // Set status menjadi error jika terjadi kesalahan
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Peminjaman - Secure Locker HUB</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
    <!--css!-->
    <style>
         /* Gaya tambahan */
         body {
            background: linear-gradient(135deg, #85f51c, rgba(4, 222, 238, 0.6));
        }

        .container {
            max-width: 700px;
            width: 80%;
            background-color: #fff;
            padding: 25px 30px;
            border-radius: 5px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.15);
            margin: 50px auto;
            flex-direction: top;
        }

        .title {
            font-size: 25px;
            font-weight: 500;
            position: relative;
            margin-bottom: 20px;
            color: #333;
        }

        .title::before {
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            height: 3px;
            width: 30%;
            border-radius: 5px;
            background: linear-gradient(135deg, #333, rgba(228, 243, 19, 0.6));
        }

        .user-details {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin: 20px;
            margin: 20px 0 12px 0;
            color: #333;
        }

        .input-box {
            margin-bottom: 15px;
            width: calc(100% / 2 - 20px);
        }

        .input-box span.details {
            display: block;
            font-weight: 500;
            margin-bottom: 5px;
            color: #333;
        }

        .input-box input {
            height: 45px;
            width: 100%;
            outline: none;
            font-size: 16px;
            border-radius: 5px;
            padding-left: 15px;
            border: 1px solid #ccc;
            border-bottom-width: 2px;
            transition: all 0.3s ease;
        }

        .input-box input:focus,
        .input-box input:valid {
            border-color: #71b7e1;
        }

        .button {
            width: 50%;
            margin-top: 15px;
            justify-content: top;
        }

        .button button {
            height: 100%;
            width: 100%;
            border-radius: 500px;
            border: 200%;
            color: #fff;
            font-size: 18px;
            font-weight: 500;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #71b7e1, #9b59);
        }

        .button button:hover {
            background: linear-gradient(-135deg, #71b7e1, #9b59);
            height: 100%;
            width: 100%;
        }

        .warning {
            color: #e44d26;
            font-size: 14px;
            margin-top: 10px;
        }

        /* Style untuk pop-up */
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            z-index: 9999;
        }

        .popup-content {
            text-align: center;
        }

        .popup-button {
            margin-top: 20px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 300px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
    <!--css!-->
    </head>
<body>
    <div class="container">
        <div class="title">Form Peminjaman</div>
        <div class="content-flex">
            <?php if ($status_persetujuan === "success") : ?>
                <div class="user-details">
                    <?php
                    // Ambil data peminjam yang baru saja disimpan
                    $sql = "SELECT * FROM peminjam WHERE nim='$nim'";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // Tampilkan informasi peminjam
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="input-box"><span class="details">Nama Lengkap: </span>' . $row['nama'] . '</div>';
                            echo '<div class="input-box"><span class="details">PRODI: </span>' . $row['prodi'] . '</div>';
                            echo '<div class="input-box"><span class="details">Alamat (KTP): </span>' . $row['alamat'] . '</div>';
                            echo '<div class="input-box"><span class="details">Nomor(WA): </span>' . $row['nomor_wa'] . '</div>';
                            echo '<div class="input-box"><span class="details">Status: </span>' . $row['status_persetujuan'] . '</div>';
                            echo '<div class="input-box"><span class="details">Keterangan: </span>' .$row['Keterangan'] . '</div>';
                            // Tampilkan tombol "Buka" jika nomor loker telah ditetapkan
                            if (isset($row['nomor_loker']) && $row['nomor_loker'] !== null) {
                                echo '<div class="button" id="openLockerButtonContainer"><button class="btn btn-success" onclick="showModal(' . $row['nomor_loker'] . ')">Buka</button></div>';
                                // Modal
                                echo '<div id="myModal' . $row['nomor_loker'] . '" class="modal">';
                                echo '  <div class="modal-content">';
                                echo '    <span class="close">&times;</span>';
                                echo '    <p>Apakah Anda yakin ingin membuka loker ' . $row['nomor_loker'] . '?</p>';
                                echo '    <button onclick="unlockLocker(' . $row['nomor_loker'] . ')">Buka Loker</button>';
                                echo '  </div>';
                                echo '</div>';
                            }
                        }
                    } else {
                        echo "Data peminjam tidak ditemukan.";
                    }
                    ?>
                </div>
            <?php else : ?>
                <form action="Userpage.php" method="POST">
                    <div class="user-details">
                        <!-- Formulir peminjaman -->
                        <div class="input-box"><span class="details">NIM</span><input type="text" name="nim" placeholder="Masukkan NIM" required></div>
                        <div class="input-box"><span class="details">Nama Lengkap</span><input type="text" name="nama" placeholder="Masukkan nama lengkap" required></div>
                        <div class="input-box"><span class="details">PRODI</span><input type="text" name="prodi" placeholder="Masukkan Prodi" required></div>
                        <div class="input-box"><span class="details">Alamat (KTP)</span><input type="text" name="alamat" placeholder="Masukkan Alamat (ktp)" required></div>
                        <div class="input-box"><span class="details">Nomor(WA)</span><input type="text" name="nomor_wa" placeholder="Masukkan Nomor(WA)" required></div>
                        <div class="input-box"><span class="details">Keterangan</span><input type="text" name="Keterangan" placeholder="Masukkan Keterangan" required></div>
                    </div>
            </div>
                    <div class="button">
                        <button type="submit">Pinjam</button>
                    </div>
                    <div class="button" id="openLockerButtonContainer" style="display: none;">
                <button class="btn btn-success" onclick="openLocker()">Buka</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
    

    <script>
    function showModal(lockerId) {
        var modal = document.getElementById('myModal' + lockerId);
        var span = modal.getElementsByClassName("close")[0];

        modal.style.display = "block";

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    }

    function unlockLocker(lockerId) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            alert(this.responseText);
        }
    };
    xhttp.open("GET", "Userpage.php?lockerId=" + lockerId, true);
    xhttp.send();
}
    </script>
</body>
</html>
</html>