<?php
    require '../function/function.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/table_styles.css">
    <title>Reports Logs</title>
</head>
<body>
<?php include '../includes/sidebar.php'; ?>

<div class="reports-content">
        <div class="dashboard-header">
            <h1>Report Logs</h1>
            <p>Welcome, Admin</p>
        </div>

                        <!-- Reports Dashboard Section -->
                        <div class="reports-dashboard">
                        <!-- Inventory Report Card -->
                        <div class="report-card">
                            <a href="../admin/generate_pdf.php" target="_blank">
                                <i class='bx bxs-report'></i>
                                <h3>Inventory Report</h3>
                                <p>Download PDF</p>
                            </a>
                        </div>

                        <!-- View Logs Card -->
                        <div class="report-card">
                            <a href="../admin/generatelogs.php" target="_blank">
                                <i class='bx bxs-copy-alt'></i>
                                <h3>View Transaction Logs</h3>
                                <p>Download PDF</p>
                            </a>
                        </div>

                        <!-- Delivery Report Card -->
                        <div class="report-card">
                            <a href="../admin/delivery_report.php" target="_blank">
                                <i class='bx bxs-truck'></i>
                                <h3>Delivery Report</h3>
                                <p>Download PDF</p>
                            </a>
                        </div>
                    </div>
                </ul>
             </div>
        </div>
    </div>
</body>

<script>
    
</script>
</html>
