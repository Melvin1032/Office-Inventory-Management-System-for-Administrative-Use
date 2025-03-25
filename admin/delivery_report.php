<?php
require('../fpdf186/fpdf.php');
require '../config/config.php';
session_start();

class PDF extends FPDF
{
    function HeaderSection($batch_id = null)
    {
        // Logo
        $this->Image('../assets/logo_blck.png', 5, 5, 40);

        // Company Details
        $this->SetFont('Arial', 'B', 12);
        $this->SetXY(50, 10);
        $this->Cell(0, 5, 'Republic of the Philippines', 0, 1, 'L');

        $this->SetXY(50, 15);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 5, 'INVENTORY EDGE INC.', 0, 1, 'L');

        $this->SetXY(50, 20);
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 5, 'www.inventoryedge.com | edge@inventory.com', 0, 1, 'L');

        // Right Section
        $this->SetFont('Arial', 'B', 8);
        $this->SetXY(50, 10);
        $this->Cell(0, 5, 'DELIVERY LOGS REPORT', 0, 1, 'R');

        $this->AliasNbPages();
        $this->SetXY(50, 15);
        $this->Cell(0, 5, 'IE-DEL020', 0, 1, 'R');
        $this->Cell(0, 5, 'Rev.3-02-07-2025', 0, 1, 'R');
        $this->Cell(0, 5, 'IE-DEL020 | Page ' . $this->PageNo() . ' of {nb}', 0, 1, 'R');

        // Underline
        $this->SetDrawColor(0, 0, 0);
        $this->Line(10, $this->GetY(), 290, $this->GetY());
        $this->Ln(3);

        // Report Title
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 15, 'Delivery Logs Report', 0, 1, 'C');

        // Date Generated
        $this->SetFont('Arial', 'I', 10);
        $currentDateTime = date("F j, Y, g:i A");
        $this->Cell(0, 3, "Report generated on: $currentDateTime", 0, 1, 'C');
        $this->Ln(5);

        if ($batch_id) {
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 5, "Batch ID: $batch_id", 0, 1, 'L');
        }
    }
}

// Create PDF instance
$pdf = new PDF('L');

// Fetch all logs grouped by batch_id
$stmt_batches = $pdo->query("SELECT DISTINCT batch_id FROM delivery_logs ORDER BY batch_id DESC");
$batches = $stmt_batches->fetchAll(PDO::FETCH_COLUMN);

$pdf->SetFont('Arial', '', 10);

if (count($batches) > 0) {
    foreach ($batches as $batch_id) {
        // New Page for Each Batch
        $pdf->AddPage();
        $pdf->HeaderSection($batch_id);

        // Fetch deliveries under this batch
        $stmt_logs = $pdo->prepare("SELECT * FROM delivery_logs WHERE batch_id = ? ORDER BY supplier");
        $stmt_logs->execute([$batch_id]);
        $logs = $stmt_logs->fetchAll();

        if (count($logs) > 0) {
            $supplier = $logs[0]['supplier'];
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 8, "Supplier: $supplier", 0, 1, 'L');
            $pdf->Ln(3);

            $tableStartX = (297 - 240) / 2;

            // Column Headers
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->SetFillColor(200, 220, 255);
            $pdf->SetX($tableStartX);
            $pdf->Cell(50, 10, 'Stock ID No.', 1, 0, 'C', true);
            $pdf->Cell(80, 10, 'Item Name', 1, 0, 'C', true);
            $pdf->Cell(40, 10, 'Category', 1, 0, 'C', true);
            $pdf->Cell(30, 10, 'Quantity', 1, 0, 'C', true);
            $pdf->Cell(40, 10, 'Unit', 1, 1, 'C', true);

            // Table Data
            $pdf->SetFont('Arial', '', 10);
            foreach ($logs as $log) {
                $pdf->SetX($tableStartX);
                $pdf->Cell(50, 10, $log['stock_num'], 1, 0, 'C', false);
                $pdf->Cell(80, 10, $log['item_name'], 1, 0, 'C', false);
                $pdf->Cell(40, 10, $log['category'], 1, 0, 'C', false);
                $pdf->Cell(30, 10, $log['quantity'], 1, 0, 'C', false);
                $pdf->Cell(40, 10, $log['unit'], 1, 1, 'C', false);
            }

            // Spacing
            $pdf->Ln(20);

            $loggedInAdmin = isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin';

            $pdf->Ln(10);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 6, "Received by:", 0, 1, 'C');

            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 6, $loggedInAdmin, 0, 1, 'C');

            $pdf->Ln(5);

            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 6, "Verified by:", 0, 1, 'C');

            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 6, "Lebron James - HEAD SUPPLY OFFICER", 0, 1, 'C');
        }
    }
} else {
    $pdf->AddPage();
    $pdf->HeaderSection();
    $pdf->Cell(0, 10, 'No delivery logs found.', 0, 1, 'C');
}

// Output the PDF
$pdf->Output();
?>
