<?php
session_reset();
require_once "../includes/connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $item = $_POST['item'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $name = "";
    $address = "Default Address";

    // Ambil nama user berdasarkan email
    $stmtUser = $conn->prepare("SELECT name FROM orders WHERE email = ?");
    $stmtUser->bind_param("s", $email);
    $stmtUser->execute();
    $resultUser = $stmtUser->get_result();

    if ($row = $resultUser->fetch_assoc()) {
        $name = $row['name'];
    }

    $stmtUser->close();

    $total_price = $price * $quantity;

    // Simpan ke tabel orders
    $stmt = $conn->prepare("INSERT INTO orders (name, email, address, item, quantity, total_price) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssid", $name, $email, $address, $item, $quantity, $total_price);
    
    if ($stmt->execute()) {
        header("Location: ../dashboard.php?success=1");
        exit;
    } else {
        echo "Gagal menyimpan pesanan: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Metode tidak valid.";
}
?>
