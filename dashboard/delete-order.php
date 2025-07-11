<?php
require_once '../includes/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];

    $stmt = $conn->prepare("DELETE FROM orders WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Pesanan berhasil dihapus.";
    } else {
        $_SESSION['error_message'] = "Gagal menghapus pesanan.";
    }

    $stmt->close();
    header("Location: ../dashboard.php");
    exit();
} else {
    header("Location: ../dashboard.php");
    exit();
}
