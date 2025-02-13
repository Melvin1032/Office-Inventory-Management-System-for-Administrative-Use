<?php
require('fpdf186/fpdf.php');
require 'config/config.php';

// Create PDF instance
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Space before logs section
$pdf->Ln(10);

// Logs Section Header
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(190, 10, 'Activity Logs', 0, 1, 'C');
$pdf->Ln(5);

// Add date and time
$currentDateTime = date("F j, Y, g:i A"); // Example: February 13, 2025, 10:30 AM
$pdf->SetFont('Arial', 'I', 12);
$pdf->Cell(190, 10, "Report compiled on: $currentDateTime", 0, 1, 'C');
$pdf->Ln(5);

// Column headers for logs
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Operation', 1);
$pdf->Cell(40, 10, 'Requested By', 1);
$pdf->Cell(40, 10, 'Approved By', 1);
$pdf->Cell(30, 10, 'Date & Time', 1);
$pdf->Ln();

// Fetch log data
$stmt_logs = $pdo->query("
    SELECT logs.operation, req.username AS requested_by, 
           app.username AS approved_by, logs.log_date 
    FROM logs 
    JOIN users req ON logs.requested_by = req.id
    LEFT JOIN users app ON logs.approved_by = app.id
    ORDER BY logs.log_date DESC
");

$pdf->SetFont('Arial', '', 10);

// Output log data
while ($log = $stmt_logs->fetch()) {
    $approvedBy = $log['approved_by'] ? $log['approved_by'] : 'Pending'; // Handle null approvals
    $pdf->Cell(40, 10, $log['operation'], 1);
    $pdf->Cell(40, 10, $log['requested_by'], 1);
    $pdf->Cell(40, 10, $approvedBy, 1);
    $pdf->Cell(30, 10, $log['log_date'], 1);
    $pdf->Ln();
}

// Output the PDF
$pdf->Output();
?>
