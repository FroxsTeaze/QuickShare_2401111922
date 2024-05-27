<?php
    // Inisialisasi session
    session_start();

    // Cek apakah pengguna sudah login
    if (isset($_SESSION['username'])) {
        // Jika sudah login, arahkan ke halaman user
        header("Location: User page/Userpage.php");
        exit();
    }

    // Fungsi untuk memeriksa kredensial
    function checkCredentials($username, $password) {
        // Ganti ini dengan logika autentikasi sesuai kebutuhan
        $validUsers = [
            'Muzakky' => '230303',
            'admin' => 'admin123'
        ];

        // Periksa kredensial
        return isset($validUsers[$username]) && $validUsers[$username] === $password;
    }

    // Proses login jika formulir dikirim
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if (checkCredentials($username, $password)) {
            // Simpan username di session
            $_SESSION['username'] = $username;

            // Arahkan ke halaman user setelah login berhasil
            header("Location: User page/Userpage.php");
            exit();
        } else {
            // Tampilkan pesan kesalahan jika kredensial tidak valid
            $errorMessage = "Username atau Password salah.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="Style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="shortcut icon" href="User page/favicon.png" type="image/x-icon">
    <nav class="navbar fixed - top bg-body-tertiary">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">
            <img src="User page/favicon.png" alt="Logo" width="100%" height="200" class="d-inline-block align-text-top">
          </a>
        </div>
      </nav>
</head>
<body>
    <div class="login-container">
        <a href=""></a>
        <h2>Login</h2>
        <form id="login-form">
            <div class="input-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="input-group">
                <button type="submit">Login</button>
            </div>
        </form>
    </div>
    <script src="Script.js" defer></script>
</body>
</html>
