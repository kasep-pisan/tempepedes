<?php
session_start();
require "../includes/connection.php"; // Sesuaikan path

if (isset($_GET['key']) && isset($_SESSION['cart'])) {
    $key = (int)$_GET['key'];
    if (isset($_SESSION['cart'][$key])) {
        unset($_SESSION['cart'][$key]);
        // Re-index array setelah penghapusan
        $_SESSION['cart'] = array_values($_SESSION['cart']); 
        $_SESSION['success_message'] = "Item berhasil dihapus dari keranjang.";
    } else {
        $_SESSION['error_message'] = "Item tidak ditemukan di keranjang.";
    }
} else {
    $_SESSION['error_message'] = "Permintaan tidak valid.";
}

header("Location: ../dashboard.php");
exit();
?>