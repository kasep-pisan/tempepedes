<?php
session_start();
include_once '../includes/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['editEmail'];
    $name = $_POST['editName'];
    $gender = $_POST['editGender'];
    $state = $_POST['editState'];
    $district = $_POST['editDistrict'];
    $vipStatus = $_POST['vipStatus'];

    $query = "UPDATE registered_users 
              SET full_name = ?, gender = ?, state = ?, district = ?, vip = ? 
              WHERE email = ?";
    
    $stmt = $conn->prepare($query);
    
    if ($stmt) {
        $stmt->bind_param("ssssss", $name, $gender, $state, $district, $vipStatus, $email);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Profile updated successfully!";
            header("Location: ../dashboard.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Failed to update profile: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $_SESSION['error_message'] = "Query preparation failed: " . $conn->error;
    }

    $conn->close();
    header("Location: ../dashboard.php");
    exit();
}
?>
