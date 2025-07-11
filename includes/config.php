<?php
$host = "localhost";
$user = "root";      // default XAMPP user
$password = "";      // default XAMPP password (kosong)
$database = "taaza_db"; // sesuaikan dengan nama database kamu

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
