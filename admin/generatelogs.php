<?php
require('../fpdf186/fpdf.php');
require '../config/config.php';

// Create PDF instance
$pdf = new FPDF('L');
$pdf->AddPage();

// Set header
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Activity Logs Report', 0, 1, 'C');
$pdf->Ln(10); // Add some space

// Set Date/Time of report generation
$currentDateTime = date("F j, Y, g:i A");
$pdf->SetFont('Arial', 'I', 12);
$pdf->Cell(0, 10, "Report compiled on: $currentDateTime", 0, 1, 'C');
$pdf->Ln(5);

// Set column headers
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(20, 10, 'ID', 1);
$pdf->Cell(40, 10, 'Operation', 1);
$pdf->Cell(40, 10, 'Requested By', 1);
$pdf->Cell(40, 10, 'Approved By', 1);
$pdf->Cell(30, 10, 'Item Name', 1);
$pdf->Cell(20, 10, 'Quantity', 1);
$pdf->Cell(50, 10, 'Acquisition Date', 1);
$pdf->Ln();

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

if ($stmt_logs->rowCount() > 0) {
    while ($log = $stmt_logs->fetch()) {
        $approvedBy = $log['approved_by'] ? $log['approved_by'] : 'Pending'; // Handle null approvals
        $created_at = date("F j, Y, g:i A", strtotime($log['created_at'])); // Format the log date
        $pdf->Cell(20, 10, $log['id'], 1);
        $pdf->Cell(40, 10, $log['operation'], 1);
        $pdf->Cell(40, 10, $log['requested_by'], 1);
        $pdf->Cell(40, 10, $approvedBy, 1);
        $pdf->Cell(30, 10, $log['item_name'], 1);
        $pdf->Cell(20, 10, $log['quantity'], 1);
        $pdf->Cell(50, 10, $created_at, 1);
        $pdf->Ln();
    }
} else {
    // If no logs found
    $pdf->Cell(0, 10, 'No activity logs found.', 0, 1, 'C');
}

// Output the PDF
$pdf->Output();
?>
