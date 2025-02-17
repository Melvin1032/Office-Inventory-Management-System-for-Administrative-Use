<?php
require('fpdf186/fpdf.php');
require 'config/config.php';

// Create PDF instance
$pdf = new FPDF('L');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(190, 10, 'Inventory Report', 0, 1, 'C'); // Title centered
$pdf->Ln(5);

// Column headers
$date = date("Y-m-d H:i:s");
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(190, 10, 'Issued on: ' . $date, 0, 1, 'C'); // Centered date
$pdf->Ln(5);

$pdf->Cell(40, 10, 'Item Name', 1);
$pdf->Cell(40, 10, 'Category', 1);
$pdf->Cell(20, 10, 'Qty', 1);
$pdf->Cell(60, 10, 'Supplier', 1);
$pdf->Cell(30, 10, 'Status', 1);
$pdf->Ln();

// Fetch data
$stmt = $pdo->query("SELECT item_name, category, quantity, supplier,
    CASE 
        WHEN quantity = 0 THEN 'Out of Stock' 
        ELSE 'In Stock' 
    END AS stock_status
    FROM inventory");

$pdf->SetFont('Arial', '', 10);

// Output data
while ($row = $stmt->fetch()) {
    $pdf->Cell(40, 10, $row['item_name'], 1);
    $pdf->Cell(40, 10, $row['category'], 1);
    $pdf->Cell(20, 10, $row['quantity'], 1, 0);
    $pdf->Cell(60, 10, $row['supplier'], 1);
    
    // Change text color for "Out of Stock"
    if ($row['quantity'] == 0) {
        $pdf->SetTextColor(255, 0, 0); // Red color
    }
    $pdf->Cell(30, 10, $row['stock_status'], 1, 1);
    $pdf->SetTextColor(0, 0, 0); // Reset color to black
}

$pdf->Output();
?>
