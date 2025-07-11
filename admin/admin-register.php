<?php
include_once '../includes/connection.php';
session_start();

if (isset($_POST['register'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Memeriksa apakah password dan konfirmasi password cocok
    if ($password !== $confirm_password) {
        echo "<script>alert('Password tidak cocok!'); window.location.href='admin-register.php';</script>";
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Memeriksa apakah email sudah terdaftar
        $query = "SELECT * FROM admin WHERE email='$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            echo "<script>alert('Email sudah terdaftar!'); window.location.href='admin-register.php';</script>";
        } else {
            // Menyimpan data admin baru ke database
            $insert_query = "INSERT INTO admin (email, password, enable_menu_page, enable_table_booking) 
                            VALUES ('$email', '$hashed_password', 1, 1)";
            if (mysqli_query($conn, $insert_query)) {
                echo "<script>alert('Pendaftaran berhasil! Silakan login.'); window.location.href='admin-login.php';</script>";
            } else {
                echo "<script>alert('Terjadi kesalahan saat registrasi. Silakan coba lagi.'); window.location.href='admin-register.php';</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Admin</title>
    <link rel="stylesheet" href="../assets/css/taaza.css">
</head>

<body>
    <div class="container">
        <header>
            <nav class="navbar">
                <div class="navbar-wrapper">
                    <a href="#">
                        <img src="../assets/images/logo.png" alt="logo" width="130">
                        <a href="admin-login.php" class="nav-link">Login</a>
                    </a>
                </div>
            </nav>
        </header>

        <main>
            <section class="contact-section" id="home">
                <div class="contact-container">
                    <div class="contact-content">
                        <div class="form-container1">
                            <div class="form-title"><b>Registrasi Admin</b></div>
                            <form action="admin-register.php" method="POST">
                                <!-- Formulir Registrasi -->
                                <div class="form-section">
                                    <div class="form-field">
                                        <label for="email">Email Admin</label>
                                        <input type="email" id="email" name="email" required>
                                    </div>

                                    <div class="form-field">
                                        <label for="password">Password</label>
                                        <input type="password" id="password" name="password" required>
                                    </div>

                                    <div class="form-field">
                                        <label for="confirm_password">Konfirmasi Password</label>
                                        <input type="password" id="confirm_password" name="confirm_password" required>
                                    </div>
                                </div>

                                <button type="submit" name="register" class="login-btn">Registrasi</button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer>
            <div class="footer-wrapper">
                <a href="#">
                    <img src="../assets/images/logo.png" alt="logo" class="footer-brand" width="150">
                </a>
                <p class="copyright">&copy; Copyright 2022 Omah Makan. All Rights Reserved.</p>
            </div>
        </footer>
    </div>
</body>

</html>
