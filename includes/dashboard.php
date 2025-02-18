<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .container { max-width: 800px; margin: auto; text-align: center; }
        .card { padding: 15px; margin: 10px; background: #f4f4f4; display: inline-block; width: 30%; }
        a { text-decoration: none; color: #333; font-weight: bold; }
    </style>
</head>
<body>
<?php 
include '../includes/header.php';
?>
<div class="container">
    <h1>Admin Dashboard</h1>
    <p>Welcome, Admin!</p>

    <div class="card">
        <h3>Manage Inventory</h3>
        <a href="inventory.php">View Inventory</a>
    </div>

    <div class="card">
        <h3>Manage Requests</h3>
        <a href="approve_requests.php">View Requests</a>
    </div>

    <div class="card">
        <h3>View Inventory Reports</h3>
        <a href="generate_pdf.php" target="_blank">Download PDF Report</a>
    </div>

    <div class="card">
        <h3>View Logs</h3>
        <a href="generatelogs.php" target="_blank">Download PDF Report</a>
    </div>

    <br><br>
    <a href="../logout.php">Logout</a>
</div>

</body>
</html>
