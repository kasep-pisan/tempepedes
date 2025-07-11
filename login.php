<?php
require_once "includes/connection.php";
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Tampilkan semua error
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Fungsi Kirim Email (boleh skip testing ini dulu)
function sendMail($email, $verification_code) {
    require ("PHPMailer/PHPMailer.php");
    require ("PHPMailer/SMTP.php");
    require ("PHPMailer/Exception.php");

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'YourEmail@gmail.com';
        $mail->Password   = 'YourAppPassword';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;
        $mail->setFrom('YourEmail@gmail.com', 'Taaza Restaurant');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Email Verification - Taaza';
        $mail->Body    = "
            <b style='color:blue'>Thanks for verification!</b><br>
            Click the link below to verify your email address:<br>
            <a href='http://localhost/taaza/verify.php?email=$email&verification_code=$verification_code'>Verify</a>
            <br><p style='color:red'>Enjoy our services, hearty welcome from Taaza</p>
        ";

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Jika tombol register ditekan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {

    // Ambil data dari form
    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $gender = $_POST['gender'];
    $state = $_POST['state'];
    $district = $_POST['district'];
    $verification_code = bin2hex(random_bytes(16));

    // Cek apakah username atau email sudah ada
    $check = mysqli_query($conn, "SELECT * FROM registered_users WHERE username = '$username' OR email = '$email'");
    
    if (mysqli_num_rows($check) > 0) {
        $row = mysqli_fetch_assoc($check);
        if ($row['username'] == $username) {
            echo "<script>alert('Username sudah digunakan. Silakan pilih username lain.'); window.location.href='login.php';</script>";
            exit();
        }
        if ($row['email'] == $email) {
            echo "<script>alert('Email sudah digunakan. Gunakan email lain.'); window.location.href='login.php';</script>";
            exit();
        }
    }

    // Jalankan query insert
    $insert = mysqli_query($conn, "INSERT INTO registered_users 
        (full_name, username, email, password, gender, state, district, verification_code, is_verified) 
        VALUES 
        ('$full_name', '$username', '$email', '$password', '$gender', '$state', '$district', '$verification_code', 0)");

    // Cek apakah insert berhasil
    if ($insert) {
        // (Opsional) Kirim email verifikasi
        // sendMail($email, $verification_code);

        echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href='new-login.php';</script>";
        exit();
    } else {
        // Tampilkan error query jika gagal insert
        echo "Gagal registrasi: " . mysqli_error($conn);
        exit();
    }
}
?>

<!-- Bagian HTML Form Register -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Register</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f9f9f9;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .register-container {
      background-color: #fff;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
      max-width: 400px;
      width: 100%;
      text-align: center;
    }

    .register-container h2 {
      color: darkgreen;
      font-size: 32px;
      margin-bottom: 30px;
    }

    .form-group {
      text-align: left;
      margin-bottom: 15px;
    }

    .form-group label {
      display: block;
      margin-bottom: 5px;
      color: #444;
      font-weight: bold;
    }

    .form-group input,
    .form-group select {
      width: 100%;
      padding: 10px;
      border: 1px solid #aaa;
      border-radius: 5px;
    }

    .register-btn {
      margin-top: 20px;
      width: 100%;
      background: linear-gradient(to right, #006400, #00cc44);
      color: white;
      padding: 12px;
      font-weight: bold;
      font-size: 16px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }

    .register-btn:hover {
      background: linear-gradient(to right, #008000, #00e673);
    }

    .bottom-text {
      margin-top: 20px;
    }

    .bottom-text a {
      color: #0033cc;
      text-decoration: none;
      font-weight: bold;
    }

    .bottom-text a:hover {
      color: #00aaff;
    }
  </style>
</head>
<body>

  <div class="register-container">
    <h2>Register</h2>
    <form action="register.php" method="POST">
     <div class="form-group">
    <label for="full-name">Full Name</label>
    <input type="text" name="full_name" id="full-name" required />
  </div>

  <div class="form-group">
    <label for="username">Username</label>
    <input type="text" name="username" id="username" required />
  </div>

  <div class="form-group">
    <label for="email">Email</label>
    <input type="email" name="email" id="email" required />
  </div>

  <div class="form-group">
    <label for="password">Password</label>
    <input type="password" name="password" id="password" required />
  </div>

  <div class="form-group">
    <label for="gender">Gender</label>
    <select name="gender" id="gender" required>
      <option value="">Select Gender</option>
      <option value="Male">LANANG</option>
      <option value="Female">BABON</option>
      <option value="Prefer not to say">Prefer not to say</option>
    </select>
  </div>

  <div class="form-group">
    <label for="state">State</label>
    <input type="text" name="state" id="state" required />
  </div>

  <div class="form-group">
    <label for="district">District</label>
    <input type="text" name="district" id="district" required />
  </div>

  <button type="submit" name="register" class="register-btn">REGISTER</button>
</form>


    <div class="bottom-text">
      Already Registered? <a href="new-login.php">Login Here</a>
    </div>
  </div>

</body>
</html>
