<?php
require "includes/connection.php";
require "includes/header.php";
session_start();

/*if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
    echo "<pre>";
    var_dump($_POST); // Debug
    echo "</pre>";

    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $gender = $_POST['gender'];
    $state = $_POST['state'];
    $district = $_POST['district'];
    $verification_code = bin2hex(random_bytes(16));

    if ($password !== $confirm_password) {
        echo "<script>alert('Password dan konfirmasi tidak sama!'); window.location.href = 'register.php';</script>";
        exit();
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $check = mysqli_query($conn, "SELECT * FROM registered_users WHERE email = '$email'");
        if (mysqli_num_rows($check) > 0) {
            echo "<script>alert('Email sudah terdaftar');</script>";
        } else {
            $query = "INSERT INTO registered_users (full_name, username, email, password, gender, state, district, verification_code, is_verified, created_at)
                      VALUES ('$full_name', '$username', '$email', '$hashed_password', '$gender', '$state', '$district', '$verification_code', 0, NOW())";

            if (mysqli_query($conn, $query)) {
                $_SESSION['email'] = $email;
                $_SESSION['full_name'] = $full_name;

                header("Location: index.php");
                exit();
            } else {
                echo "<script>alert('Gagal menyimpan ke database: " . mysqli_error($conn) . "');</script>";
            }
        }
    }
}*/
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek apakah user ada
    $query = mysqli_query($conn, "SELECT * FROM registered_users WHERE username='$username'");
    if (mysqli_num_rows($query) == 1) {
        $user = mysqli_fetch_assoc($query);

        // Cocokkan password dan cek verifikasi
        if (password_verify($password, $user['password'])) {
            if ($user['is_verified'] == 1) {
                // Set session dan redirect ke dashboard
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                header("Location: dashboard.php");
                exit();
            } else {
                echo "<script>alert('Account not verified! Please check your email.');</script>";
            }
        } else {
            echo "<script>alert('Password salah!');</script>";
        }
    } else {
        echo "<script>alert('Username tidak ditemukan!');</script>";
    }
}
?>

<?php require "includes/header.php"; ?>

<section class="home" id="home">
  <div class="home-left">
    <div class="container">
      <div class="form-container1">
        <div class="form-title"><b>Login</b></div>
        <form action="#" method="POST">
          <div class="form-section">
            <div class="form-field">
              <label for="username">USERNAME</label>
              <input type="username" id="username" name="username" required>
            </div>

            <div class="form-field">
              <label for="password">PASSWORD</label>
              <input type="password" id="password" name="password" required>
            </div>
          </div>

          <div class="form-field">
            <a href="forgot.php">Forgot password?</a>
          </div>

          <button type="submit" name="login" class="login-btn">Login</button>
        </form>

        <br>
        <center>
          <div class="form-question">
            <h3>New Member? 
              <u><a href="login.php" style="display: inline; color: #216aca;"
                onmouseover="this.style.color='#03d9ff'"
                onmouseout="this.style.color='#216aca'">Register Here</a></u>
            </h3>
          </div>
        </center>
      </div>
    </div>
  </div>

  <div class="home-right" style="margin-top: 1cm;">
    <img src="./assets/images/food1.png" class="food-img food-1" width="200" loading="lazy">
    <img src="./assets/images/food2.png" class="food-img food-2" width="200" loading="lazy">
    <img src="./assets/images/food3.png" class="food-img food-3" width="200" loading="lazy">
    <img src="./assets/images/dialog-1.svg" class="dialog dialog-1" width="230">
    <img src="./assets/images/dialog-2.svg" class="dialog dialog-2" width="230">
    <img src="./assets/images/circle.svg" class="shape shape-1" width="25">
    <img src="./assets/images/circle.svg" class="shape shape-2" width="15">
    <img src="./assets/images/circle.svg" class="shape shape-3" width="30">
    <img src="./assets/images/ring.svg" class="shape shape-4" width="60">
    <img src="./assets/images/ring.svg" class="shape shape-5" width="40">
  </div>
</section>

<?php require "includes/footer.php"; ?>


