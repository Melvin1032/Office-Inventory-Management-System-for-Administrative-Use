<?php
require('../fpdf186/fpdf.php');
require '../config/config.php';
session_start();



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
$pdf->Cell(0, 5, 'DELIVERY LOGS REPORT', 0, 1, 'R');

$pdf->AliasNbPages();

$pdf->SetXY(50, 15);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(0, 5, 'IE-DEL020', 0, 1, 'R');
$pdf->Cell(0, 5, 'Rev.3-02-07-2025', 0, 1, 'R');
$pdf->Cell(0, 5, 'IE-DEL020 | Page ' . $pdf->PageNo() . ' of {nb}', 0, 1, 'R');

// Set up a line under the title for style
$pdf->SetDrawColor(0, 0, 0);
$pdf->Line(10, $pdf->GetY(), 290, $pdf->GetY());
$pdf->Ln(1);

// Title Section (Header with a larger title)
$pdf->SetFont('Arial', 'B', 15);
$pdf->Cell(0, 15, 'Delivery Logs Report', 0, 1, 'C');
$pdf->Ln(0);

// Subheading: Date/Time of report generation
$currentDateTime = date("F j, Y, g:i A");
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 3, "Report generated on: $currentDateTime", 0, 1, 'C');
$pdf->Ln(5);

// Fetch all logs grouped by batch_id
$stmt_batches = $pdo->query("SELECT DISTINCT batch_id FROM delivery_logs ORDER BY batch_id DESC");
$batches = $stmt_batches->fetchAll(PDO::FETCH_COLUMN);

$pdf->SetFont('Arial', '', 10);

if (count($batches) > 0) {
    foreach ($batches as $batch_id) {
        // Fetch deliveries under this batch
        $stmt_logs = $pdo->prepare("SELECT * FROM delivery_logs WHERE batch_id = ? ORDER BY supplier");
        $stmt_logs->execute([$batch_id]);
        $logs = $stmt_logs->fetchAll();

        if (count($logs) > 0) {
            // Display Batch ID and Supplier (assumes all rows in a batch have the same supplier)
            $supplier = $logs[0]['supplier'];
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 5, "Batch ID: $batch_id", 0, 1, 'L');
            $pdf->Cell(0, 8, "Supplier: $supplier", 0, 1, 'L');
            $pdf->Ln(3);

            $tableStartX = (297 - 240) / 2; // Centering calculation

            // Column headers
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->SetFillColor(200, 220, 255); // Light blue for header background
            $pdf->SetX($tableStartX); // Set starting X position for centering
            $pdf->Cell(50, 10, 'Stock ID No.', 1, 0, 'C', true);
            $pdf->Cell(80, 10, 'Item Name', 1, 0, 'C', true);
            $pdf->Cell(40, 10, 'Category', 1, 0, 'C', true);
            $pdf->Cell(30, 10, 'Quantity', 1, 0, 'C', true);
            $pdf->Cell(40, 10, 'Unit', 1, 1, 'C', true);
            
            // Output rows for this batch
            $pdf->SetFont('Arial', '', 10);
            foreach ($logs as $log) {
                $pdf->SetX($tableStartX); // Ensure each row starts at the centered position
                $pdf->Cell(50, 10, $log['stock_num'], 1, 0, 'C', false);
                $pdf->Cell(80, 10, $log['item_name'], 1, 0, 'C', false);
                $pdf->Cell(40, 10, $log['category'], 1, 0, 'C', false);
                $pdf->Cell(30, 10, $log['quantity'], 1, 0, 'C', false);
                $pdf->Cell(40, 10, $log['unit'], 1, 1, 'C', false);
            }

            // Add spacing between batches
            $pdf->Ln(5);

            
            $loggedInAdmin = isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin';
            
            $pdf->Ln(10);

            $loggedInAdmin = isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin';

            $pdf->Ln(10); // Add spacing before the section
            
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 6, "Received by:", 0, 1, 'C'); // Label Centered
            
            $pdf->SetFont('Arial', 'B', 12); // Bold 
            $pdf->Cell(0, 6, $loggedInAdmin, 0, 1, 'C'); // Name Centered
            
            $pdf->Ln(5); // Add spacing
            
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 6, "Verified by:", 0, 1, 'C'); // Label Centered
            
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 6, "Lebron James - HEAD SUPPLY OFFICER", 0, 1, 'C'); // Name Centered
            
            
            
        }
    }
} else {
    $pdf->Cell(0, 10, 'No delivery logs found.', 0, 1, 'C');
}

// Output the PDF
$pdf->Output();
?>
