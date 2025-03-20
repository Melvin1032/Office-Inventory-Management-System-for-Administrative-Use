<?php
require('../fpdf186/fpdf.php');
require '../config/config.php';

// Create PDF instance
$pdf = new FPDF('L');
$pdf->AddPage();

// Title Section (Header with a larger title)
$pdf->SetFont('Arial', 'B', 20);
$pdf->Cell(0, 15, 'Activity Logs Report', 0, 1, 'C');
$pdf->Ln(5);

// Subheading: Date/Time of report generation
$currentDateTime = date("F j, Y, g:i A");
$pdf->SetFont('Arial', 'I', 14);
$pdf->Cell(0, 3, "Report generated on: $currentDateTime", 0, 1, 'C');
$pdf->Ln(5);

// Set up a line under the title for style
$pdf->SetDrawColor(0, 0, 0);
$pdf->Line(10, $pdf->GetY(), 290, $pdf->GetY());
$pdf->Ln(10);

// Column headers with background color and bold font
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(200, 220, 255); // Light blue for header background
$pdf->Cell(20, 10, 'ID', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Operation', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Requested By', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Approved By', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Item Name', 1, 0, 'C', true);
$pdf->Cell(20, 10, 'Quantity', 1, 0, 'C', true);
$pdf->Cell(50, 10, 'Acquisition Date', 1, 1, 'C', true);
$pdf->Ln(5);

// Fetch log data with all the desired columns
$stmt_logs = $pdo->query("
    SELECT logs.id, logs.operation, req.username AS requested_by, 
           app.username AS approved_by, logs.item_name, logs.quantity, logs.created_at 
    FROM logs 
    JOIN users req ON logs.requested_by = req.id
    LEFT JOIN users app ON logs.approved_by = app.id
    ORDER BY logs.created_at DESC
");

// Check if there are logs, and output data
$pdf->SetFont('Arial', '', 10);

// Style for alternating row colors
$rowColor = [255, 255, 255]; // White for the first row
$altRowColor = [240, 240, 240]; // Light gray for alternating rows

if ($stmt_logs->rowCount() > 0) {
    $isAltRow = false; // Flag for alternating row color
    while ($log = $stmt_logs->fetch()) {
        $approvedBy = $log['approved_by'] ? $log['approved_by'] : 'Pending'; // Handle null approvals
        $created_at = date("F j, Y, g:i A", strtotime($log['created_at'])); // Format the log date

        // Set alternating row color
        $fill = $isAltRow ? $altRowColor : $rowColor;

        // Output the row with alternating colors
        $pdf->SetFillColor($fill[0], $fill[1], $fill[2]);
        $pdf->Cell(20, 10, $log['id'], 1, 0, 'C', true);
        $pdf->Cell(40, 10, $log['operation'], 1, 0, 'C', true);
        $pdf->Cell(40, 10, $log['requested_by'], 1, 0, 'C', true);
        $pdf->Cell(40, 10, $approvedBy, 1, 0, 'C', true);
        $pdf->Cell(30, 10, $log['item_name'], 1, 0, 'C', true);
        $pdf->Cell(20, 10, $log['quantity'], 1, 0, 'C', true);
        $pdf->Cell(50, 10, $created_at, 1, 1, 'C', true);
        $pdf->Ln(1);

        // Alternate row color for next row
        $isAltRow = !$isAltRow;
    }
} else {
    // If no logs found
    $pdf->Cell(0, 10, 'No activity logs found.', 0, 1, 'C');
}

// Output the PDF
$pdf->Output();
?>
