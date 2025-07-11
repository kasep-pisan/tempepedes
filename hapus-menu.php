<?php
include 'includes/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    
    $query = mysqli_query($conn, "DELETE FROM menus WHERE id = $id");

    if ($query) {
        header("Location: menu.php"); // Redirect kembali ke halaman daftar menu
        exit();
    } else {
        echo "Gagal menghapus menu!";
    }
}
?>
