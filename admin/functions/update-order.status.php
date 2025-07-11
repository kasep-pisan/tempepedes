<?php
session_start();
include_once '../includes/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id'], $_POST['order_status'])) {
    $orderId = $_POST['order_id'];
    $orderStatus = $_POST['order_status'];

    // Update status pesanan
    $updateQuery = "UPDATE orders SET order_status = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $orderStatus, $orderId);

    if ($stmt->execute()) {
        echo "<script>alert('Order status updated successfully!'); window.location.href='admin.php';</script>";
    } else {
        echo "<script>alert('Error updating order status!'); window.location.href='admin.php';</script>";
    }
}
// Query untuk mengambil semua pesanan
$selectOrdersQuery = "SELECT * FROM orders";
$result = $conn->query($selectOrdersQuery);

if ($result->num_rows > 0) {
    while ($order = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $order['id'] . '</td>';
        echo '<td>' . $order['user_id'] . '</td>';
        echo '<td>' . $order['order_details'] . '</td>';
        echo '<td>' . $order['order_status'] . '</td>';
        echo '<td>' . $order['order_date'] . '</td>';
        echo '<td>
                <form action="update-order-status.php" method="POST">
                    <input type="hidden" name="order_id" value="' . $order['id'] . '">
                    <select name="order_status" class="form-control">
                        <option value="Pending" ' . ($order['order_status'] == 'Pending' ? 'selected' : '') . '>Pending</option>
                        <option value="Completed" ' . ($order['order_status'] == 'Completed' ? 'selected' : '') . '>Completed</option>
                        <option value="Canceled" ' . ($order['order_status'] == 'Canceled' ? 'selected' : '') . '>Canceled</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm mt-2">Update Status</button>
                </form>
            </td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="6">No orders found.</td></tr>';
}

?>
