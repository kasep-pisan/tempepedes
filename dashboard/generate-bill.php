<?php
require_once('../fpdf/fpdf.php'); // Sesuaikan path FPDF

class PDF extends FPDF {
    // Header
    function Header() {
        // Logo (optional)
        // $this->Image('path/to/your/logo.png',10,8,33);
        // Arial bold 15
        $this->SetFont('Arial','B',15);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(30,10,'Invoice / Bill',1,0,'C');
        // Line break
        $this->Ln(20);
    }

    // Footer
    function Footer() {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

if (isset($_GET['order'])) {
    $order_json = $_GET['order'];
    $order = json_decode($order_json, true); // Decode JSON string into associative array

    if ($order && is_array($order)) {
        // Create a new PDF instance
        $pdf = new PDF();
        $pdf->AliasNbPages(); // For {nb} in footer
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('Arial', '', 12);

        // Add order details to the PDF
        $pdf->Cell(0, 10, 'Order ID: ' . ($order['order_id'] ?? 'N/A'), 0, 1);
        $pdf->Cell(0, 10, 'Name: ' . ($order['name'] ?? 'N/A'), 0, 1);
        $pdf->Cell(0, 10, 'Email: ' . ($order['email'] ?? 'N/A'), 0, 1);
        $pdf->Cell(0, 10, 'Address: ' . ($order['address'] ?? 'N/A'), 0, 1);
        $pdf->Cell(0, 10, 'Item: ' . ($order['item'] ?? 'N/A'), 0, 1);
        $pdf->Cell(0, 10, 'Quantity: ' . ($order['quantity'] ?? 'N/A'), 0, 1);
        $pdf->Cell(0, 10, 'Total Price: Rp ' . number_format(($order['total_price'] ?? 0), 0, ',', '.'), 0, 1);
        $pdf->Cell(0, 10, 'Timestamp: ' . ($order['timestamp'] ?? 'N/A'), 0, 1);

        // Output the PDF (you can choose to save or display)
        $filename = 'Order_Bill_' . ($order['order_id'] ?? 'unknown') . '.pdf';
        $pdf->Output($filename, 'D'); // 'D' for download
    } else {
        echo "Invalid order data.";
    }
} else {
    echo "No order data provided.";
}
?>