<?php
require('../fpdf186/fpdf.php');
require '../config/config.php';

// Create PDF instance
$pdf = new FPDF('L');
$pdf->AddPage();

// Insert the first logo (left side)
$pdf->Image('../assets/logo_blck.png', 5, 5, 40);

// Set font for the title
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetXY(50, 10);
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
$pdf->Cell(0, 15, 'Activity Logs Report', 0, 1, 'C');
$pdf->Ln(0);

// Subheading: Date/Time of report generation
$currentDateTime = date("F j, Y, g:i A");
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 3, "Report generated on: $currentDateTime", 0, 1, 'C');
$pdf->Ln(5);

// Center the table: Set startX for centered table
$pageWidth = 297; // Landscape A4 size width
$tableWidth = 280; // Total width of the table (sum of all column widths)
$startX = ($pageWidth - $tableWidth) / 2; // Calculate starting X for centering
$pdf->SetX($startX);

// Column headers with background color and bold font
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(200, 220, 255); // Light blue for header background
$pdf->Cell(40, 10, 'Operation', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Requested By', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Approved By', 1, 0, 'C', true);
$pdf->Cell(50, 10, 'Item Name', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Quantity', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'Unit', 1, 0, 'C', true);
$pdf->Cell(60, 10, 'Acquisition Date', 1, 1, 'C', true);

// Fetch log data with all the desired columns
$stmt_logs = $pdo->query("
    SELECT logs.id, logs.operation, req.username AS requested_by, 
           app.username AS approved_by, logs.item_name, logs.quantity, logs.unit, logs.created_at 
    FROM logs 
    JOIN users req ON logs.requested_by = req.id
    LEFT JOIN users app ON logs.approved_by = app.id
    ORDER BY logs.created_at DESC
");

// Check if there are logs, and output data
$pdf->SetFont('Arial', '', 10);

// Output the log data
if ($stmt_logs->rowCount() > 0) {
    while ($log = $stmt_logs->fetch()) {
        $approvedBy = $log['approved_by'] ? $log['approved_by'] : 'Pending'; // Handle null approvals
        $created_at = date("F j, Y, g:i A", strtotime($log['created_at'])); // Format the log date

        // Reset X to ensure centering for each row
        $pdf->SetX($startX);

        // Output the row data
        $pdf->Cell(40, 10, $log['operation'], 1, 0, 'C', false);
        $pdf->Cell(40, 10, $log['requested_by'], 1, 0, 'C', false);
        $pdf->Cell(40, 10, $approvedBy, 1, 0, 'C', false);
        $pdf->Cell(50, 10, $log['item_name'], 1, 0, 'C', false);
        $pdf->Cell(30, 10, $log['quantity'], 1, 0, 'C', false);
        $pdf->Cell(20, 10, $log['unit'], 1, 0, 'C', false);
        $pdf->Cell(60, 10, $created_at, 1, 1, 'C', false);
    }
} else {
    // If no logs found
    $pdf->Cell(0, 10, 'No activity logs found.', 0, 1, 'C');
}

// Output the PDF
$pdf->Output();
?>
