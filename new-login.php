<?php
require_once "includes/connection.php";
session_start();

if (isset($_POST['login'])) {
    // Ambil input dan amankan
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // GANTI kolom email menjadi username di sini
    $query = "SELECT * FROM registered_users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        if ($user['is_verified'] == 1) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $user['username'];

                echo "<script>
                    alert('Login Success');
                    window.location.href='dashboard.php';
                    </script>";
                exit;
            } else {
                echo "<script>
                    alert('Incorrect password!');
                    window.location.href='new-login.php';
                    </script>";
                exit;
            }
        } else {
            echo "<script>
                alert('Account not verified! Please check your email.');
                window.location.href='new-login.php';
                </script>";
            exit;
        }
    } else {
        echo "<script>
            alert('Username not registered! Please register first.');
            window.location.href='new-login.php';
            </script>";
        exit;
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
              <input type="text" id="username" name="username" required>
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
