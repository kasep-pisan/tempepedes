<?php
session_start();
require "../includes/connection.php"; // Sesuaikan path

if (!isset($_SESSION['username'])) {
    $_SESSION['error_message'] = "Anda harus login untuk mengkonfirmasi pesanan.";
    header("Location: ../new-login.php");
    exit();
}

if (!empty($_SESSION['cart'])) {
    $user_id = $_SESSION['user_id'] ?? null; // Pastikan user_id ada di sesi
    $user_email = $_SESSION['username'];
    $user_name = $_SESSION['user_name'] ?? null;
    $user_address = $_SESSION['user_address'] ?? null;

    if ($user_id === null || $user_name === null || $user_address === null) {
        // Fallback jika data user_id/name/address tidak ada di sesi
        $selectUserQuery = "SELECT user_id, name, address FROM orders WHERE email = ?";
        $stmtUser = $conn->prepare($selectUserQuery);
        if ($stmtUser) {
            $stmtUser->bind_param("s", $user_email);
            $stmtUser->execute();
            $resultUser = $stmtUser->get_result();
            if ($resultUser->num_rows > 0) {
                $userData = $resultUser->fetch_assoc();
                $user_id = $userData['user_id'];
                $user_name = $userData['name'];
                $user_address = $userData['address'];
                // Update sesi
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $user_name;
                $_SESSION['user_address'] = $user_address;
            } else {
                $_SESSION['error_message'] = "Informasi pengguna tidak ditemukan.";
                header("Location: ../dashboard.php");
                exit();
            }
            $stmtUser->close();
        } else {
            $_SESSION['error_message'] = "Error saat menyiapkan query pengguna.";
            header("Location: ../dashboard.php");
            exit();
        }
    }

    $all_items_saved = true;
    foreach ($_SESSION['cart'] as $item) {
        $item_name = $item['name'];
        $quantity = $item['quantity'];
        $total_price_item = $item['total_item_price'];

        // Menggunakan user_id, name, email, dan address dari sesi
        $sql = "INSERT INTO orders (user_id, name, email, address, item, quantity, total_price, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        
        // Sesuaikan bind_param berdasarkan tipe data di DB Anda.
        // Asumsi: user_id (int), name (string), email (string), address (string), item (string), quantity (int), total_price (float/decimal)
        $stmt->bind_param("issssds", $user_id, $user_name, $user_email, $user_address, $item_name, $quantity, $total_price_item);

        if (!$stmt->execute()) {
            $_SESSION['error_message'] = "Gagal menyimpan beberapa item pesanan: " . $stmt->error;
            $all_items_saved = false;
            break; // Hentikan jika ada error
        }
        $stmt->close();
    }

    if ($all_items_saved) {
        unset($_SESSION['cart']); // Kosongkan keranjang setelah berhasil disimpan
        $_SESSION['success_message'] = "Pesanan Anda berhasil dikonfirmasi!";
    }
} else {
    $_SESSION['error_message'] = "Keranjang belanja Anda kosong.";
}

header("Location: ../dashboard.php");
exit();
?>