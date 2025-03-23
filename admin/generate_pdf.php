<?php
require('../fpdf186/fpdf.php');
require '../config/config.php';

// Create PDF instance
$pdf = new FPDF('L');
$pdf->AddPage();

// Insert the first logo (left side)
$pdf->Image('../assets/logo_blck.png', 5, 5, 40); // Adjust path and size

// Set font for the title
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetXY(50, 10); // Adjust position to align text properly
$pdf->Cell(0, 5, 'Republic of the Philippines', 0, 1, 'L');

$pdf->SetXY(50, 15);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 5, 'INVENTORY EDGE INC.', 0, 1, 'L');

$pdf->SetXY(50, 20);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 5, 'www.inventoryedge.com | edge@inventory.com', 0, 1, 'L');

// RIGHT TEXT UPPER
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetXY(50, 10);
$pdf->Cell(0, 5, 'STOCK CARD (MATERIAL INVENTORY)', 0, 1, 'R');

$pdf->AliasNbPages();

$pdf->SetXY(50, 15);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(0, 5, 'IE-SOF020', 0, 1, 'R');
$pdf->Cell(0, 5, 'Rev.3-02-07-2025', 0, 1, 'R');
$pdf->Cell(0, 5, 'IE-SOF020 | Page ' . $pdf->PageNo() . ' of {nb}', 0, 1, 'R');

// Set up a line under the title for style
$pdf->SetDrawColor(0, 0, 0);
$pdf->Line(10, $pdf->GetY(), 290, $pdf->GetY());
$pdf->Ln(1);

// Title Section (Header with a larger title)
$pdf->SetFont('Arial', 'B', 15);
$pdf->Cell(0, 15, 'Inventory Stock Report', 0, 1, 'C');
$pdf->Ln(0);

// Subheading: Date/Time of report generation
$currentDateTime = date("F j, Y, g:i A");
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 3, "Report generated on: $currentDateTime", 0, 1, 'C');
$pdf->Ln(5);

// Column headers with background color and bold font
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(200, 220, 255); // Light blue for header background

// Calculate starting X position to center the table
$startX = (297 - 250) / 2; // (page width - table width) / 2

// Set the initial position for the first column
$pdf->SetX($startX);

// Output column headers
$pdf->Cell(30, 10, 'Stock ID No.', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Item Name', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Category', 1, 0, 'C', true);
$pdf->Cell(50, 10, 'Supplier', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Qty', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Unit', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Status', 1, 1, 'C', true);

// Fetch data
$stmt = $pdo->query("SELECT stock_num, item_name, category, quantity, unit, supplier,
    CASE    
        WHEN quantity = 0 THEN 'Out of Stock' 
        ELSE 'In Stock' 
    END AS stock_status
    FROM inventory");

$pdf->SetFont('Arial', '', 10);

// Output data
while ($row = $stmt->fetch()) {
    // Set the starting position for the row
    $pdf->SetX($startX);

    $pdf->Cell(30, 10, $row['stock_num'], 1, 0, 'C');
    $pdf->Cell(40, 10, $row['item_name'], 1, 0, 'C');
    $pdf->Cell(40, 10, $row['category'], 1, 0, 'C');
    $pdf->Cell(50, 10, $row['supplier'], 1, 0, 'C');
    $pdf->Cell(30, 10, $row['quantity'], 1, 0, 'C');
    $pdf->Cell(30, 10, $row['unit'], 1, 0, 'C');

    // Change text color for "Out of Stock"
    if ($row['quantity'] == 0) {
        $pdf->SetTextColor(255, 0, 0); // Red color
    }
    $pdf->Cell(30, 10, $row['stock_status'], 1, 0, 'C');
    $pdf->SetTextColor(0, 0, 0); // Reset color to black

    $pdf->Ln(); 
}

$pdf->Output();
?>
