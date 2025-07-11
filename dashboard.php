<?php
session_start();
include_once 'includes/connection.php';
require('fpdf/fpdf.php'); 


if (!isset($_SESSION['username'])) {
    header("Location: new-login.php");
    exit();
}
require "includes/connection.php";
if (isset($_SESSION['success_message'])) {
    echo "<script>alert('" . $_SESSION['success_message'] . "');</script>";
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    echo "<script>alert('" . $_SESSION['error_message'] . "');</script>";
    unset($_SESSION['error_message']);
}
$userId = $_SESSION['username'];
$selectUserQuery = "SELECT * FROM registered_users WHERE email = ?";
$selectUserStmt = $conn->prepare($selectUserQuery);
if ($selectUserStmt) {
    $selectUserStmt->bind_param("s", $userId);
    $selectUserStmt->execute();
    $result = $selectUserStmt->get_result();
    $userDetails = $result->fetch_assoc();
    $selectUserStmt->close();
} else {
    die("Error preparing statement: " . $conn->error);
}

require "includes/header.php";

?>

<style>
  .button {
    background-image: linear-gradient(to right, #0d5215, green);
    color: white;
    width: 100%;
    font-size: var(--fs-7);
    text-transform: uppercase;
    padding: 20px 30px;
    text-align: center;
    border-radius: 7px;
  }

  .form-field {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
  }

  body {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding-top: 100px;
    margin: 0;
  }

  .profile-container {
    display: flex;
    width: 100%;
    padding: 20px;
    border: 1px solid #ccc;
    box-sizing: border-box;
    margin-top: 10em;
  }
  .left-section {
    width:60%;
    padding: 20px;
  }
    .right-section {
    padding: 20px;
    border-left: 2px;
  }


  h2 {
    color: #0a5e10;
  }
  .form-field:disabled {
    background-color: #e9e9ed; /* Set the desired gray background color */
    color: #666; /* Set a color that provides sufficient contrast */
}
.order-container {
        display: flex;
        flex-wrap: wrap;
        gap: 17px;
    }

    .order-item {
        background-color: #f4f4f4;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 300px; /* Adjust the width as needed */
    }

    .order-info {
        margin: 10px 0;
    }

    .label {
  display: block;
  font-weight: bold;
  margin-bottom: 5px;
  color: #787878;
    }

    .button-delete {
    background-color: #dc3545;
    margin-top: 10px;
    }

    #your-order {
  background-color: #f9f9f9;
  padding: 20px;
  margin-top: 30px;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    #order-list {
    list-style-type: none;
    padding: 0;
    }
    #order-list li {
    margin-bottom: 8px;
    }



</style>

<?php
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
  header("Location: dashboard.php"); 
  exit;
}

$userId = $_SESSION['username'];
$selectUserQuery = "SELECT * FROM registered_users WHERE email = ?";
$selectUserStmt = $conn->prepare($selectUserQuery);
if ($selectUserStmt) {
  $selectUserStmt->bind_param("s", $userId);
  $selectUserStmt->execute();
  $result = $selectUserStmt->get_result();
  $userDetails = $result->fetch_assoc();
  $selectUserStmt->close();
} else {
  // Handle the error
  die("Error preparing statement: " . $conn->error);
}
?>

<?php require "includes/header.php"; ?>

<section class="profile-container" id="home">
  <!-- Left section starts here -->
  <div class="left-section">
    <h2>Welcome to Your Profile,</h2>
    <br>
    <div>
        <form action="dashboard/profile-update.php" method="POST">
            <label class="label" for="editEmail">Email:</label>
            <input class="form-field" type="email" id="editEmail" name="editEmail" value= ajitato@gmail.com required>
            <br>
            <label class="label" for="editName">Name:</label>
            <input class="form-field" type="text" id="editName" name="editName" value= aji required>
            <br>
            <label class="label" for="editGender">Gender:</label>
            <input class="form-field" type="text" id="editGender" name="editGender" value= lakilaki required>
            <br>
            <label class="label" for="editState">State:</label>
            <input class="form-field" type="text" id="editState" name="editState" value= sleman required>
            <br>
            <label class="label" for="editDistrict">District:</label>
            <input class="form-field" type="text" id="editDistrict" name="editDistrict" value= sleman required>
            <br>
            <label class="label" for="vipStatus">VIP Status:</label>
            <input class="form-field" type="text" id="vipStatus" name="vipStatus" 
                   value=......
                   style="color: <?php echo ($userDetails['is_vip'] == 1) ? 'green' : '#bf9900'; ?>" 
                   required>
            <br>

            <button class="button" type="submit">Update Profile</button>
        </form>
        <br><br><hr><br><br>

        <!-- Password Reset Form -->
        <h2>Update your password</h2>
        <br>
<form action="dashboard/password-update.php" method="POST" id="passwordResetForm">
    <label class="label" for="oldPassword">Old Password:</label>
    <input class="form-field" type="password" id="oldPassword" name="oldPassword" required>
    <br>

    <label class="label" for="newPassword">New Password:</label>
    <input class="form-field" type="password" id="newPassword" name="newPassword" required>
    <br>

    <label class="label" for="confirmNewPassword">Confirm New Password:</label>
    <input class="form-field" type="password" id="confirmNewPassword" name="confirmNewPassword" required>
    <br>

    <button class="button" type="submit" name="reset">Reset Password</button>
</form>

    </div>
</div>


<!-- Right section starts here -->
<div class="right-section">
  <h2>Daftar Menu Yang Sudah Disimpan</h2>
<div class="order-container">
<?php
$menuQuery = "SELECT * FROM menus WHERE active = 1 ORDER BY id DESC";
$menuResult = $conn->query($menuQuery);

if ($menuResult && $menuResult->num_rows > 0) {
    while ($menu = $menuResult->fetch_assoc()) {
echo "<div class='menu-item' data-name='" . htmlspecialchars($menu['name']) . "' data-price='" . $menu['price'] . "'>";
echo "<p class='order-info'><strong>Nama:</strong> " . htmlspecialchars($menu['name']) . "</p>";
echo "<p class='order-info'><strong>Deskripsi:</strong> " . htmlspecialchars($menu['description']) . "</p>";
echo "<p class='order-info'><strong>Harga:</strong> Rp " . number_format($menu['price'], 0, ',', '.') . "</p>";
echo "<label for='quantity'>Qty:</label>";
echo "<input type='number' class='qty' min='1' value='1'>";
echo "<br><br>";
echo "<button class='button pesan-btn' type='button'>PESAN</button>";
echo "<button type='submit' class='button button-delete'>Hapus</button>";
echo "</div>";


    }
} else {
    echo "<p>Tidak ada menu tersedia.</p>";
}
?>
</div>

<div id="your-order">
  <h3>Your order.</h3>
  <ul id="order-list"></ul>
  <p>Total: Rp <span id="total">0</span></p>
</div>


 <div class="feedback-container">
    <h2>Feedback</h2>
    <form action="dashboard/feedback.php" method="POST">
      <input type="hidden" name="item" value="<?= $menu['name'] ?>">
  <input type="hidden" name="price" value="<?= $menu['price'] ?>">
        <textarea class="form-field" id="feedbackText" name="feedbackText" rows="4" required placeholder="Type your feedback here and submit"></textarea>
        <br>

        <button class="button" type="submit">Submit Feedback</button>
    </form>
</div>


</div>


</section>
<script>
  document.addEventListener("DOMContentLoaded", function () {
  const orderList = document.getElementById("order-list");
  const totalElement = document.getElementById("total");
  let total = 0;

  document.querySelectorAll(".pesan-btn").forEach(button => {
    button.addEventListener("click", function () {
      const parent = button.closest(".menu-item");
      const name = parent.dataset.name;
      const price = parseInt(parent.dataset.price);
      const qty = parseInt(parent.querySelector(".qty").value);
      const subtotal = price * qty;

      // Tambahkan item ke daftar
      const li = document.createElement("li");
      li.textContent = `${qty} x ${name} = Rp ${subtotal.toLocaleString()}`;
      li.setAttribute("data-subtotal", subtotal); // untuk pelacakan saat dihapus
      li.setAttribute("data-name", name);         // agar bisa cocokkan untuk hapus
      orderList.appendChild(li);

      // Update total
      total += subtotal;
      totalElement.textContent = total.toLocaleString();
    });
  });

  // 👇 Tambahkan handler untuk tombol HAPUS
  document.querySelectorAll(".button-delete").forEach(button => {
    button.addEventListener("click", function () {
      const parent = button.closest(".menu-item");
      const name = parent.dataset.name;

      // Cari item di daftar order yang cocok namanya
      const items = orderList.querySelectorAll("li");
      items.forEach(item => {
        if (item.getAttribute("data-name") === name) {
          const subtotal = parseInt(item.getAttribute("data-subtotal"));
          total -= subtotal;
          item.remove();
        }
      });

      // Update total setelah penghapusan
      totalElement.textContent = total.toLocaleString();
    });
  });
});
</script>

<br>

<?php require "includes/footer.php"; ?>

<script>
    function generateBillPDF(order) {
        window.location.href = 'dashboard/generate-bill.php?order=' + JSON.stringify(order);
    }
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const newPasswordInput = document.getElementById("newPassword");
        const confirmNewPasswordInput = document.getElementById("confirmNewPassword");
        const passwordResetForm = document.getElementById("passwordResetForm");
        const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

        passwordResetForm.addEventListener("submit", function(event) {
            const newPassword = newPasswordInput.value;
            const confirmNewPassword = confirmNewPasswordInput.value;

            if (!passwordPattern.test(newPassword)) {
                alert("Password must contain at least 8 characters, including at least one uppercase letter, one lowercase letter, one number, and one special character.");
                event.preventDefault();
            } else if (newPassword !== confirmNewPassword) {
                alert("Passwords do not match.");
                event.preventDefault();
            }
        });
    });

</script>
