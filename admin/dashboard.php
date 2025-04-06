<?php
    require '../function/function.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/table_styles.css">
    <link rel="icon" href="../assets/logo_blck.png" type="image/png">   
</head>
<body>
<?php include '../includes/sidebar.php'; ?>
<div class="home-content">
        <div class="dashboard-header">
            <h1>Admin Dashboard</h1>
            <p>Welcome, Admin</p>
        </div>
        <div class="dashboard-content">
            <div class="card">
                 <i class='bx bxs-box' ></i>
                <h3>Total Inventory Items</h3>
                <p><?php echo $total_inventory; ?></p>
            </div>
            <div class="card">
                <i class='bx bx-book-open' ></i>
                <h3>Pending Requests</h3>
                <p><?php echo $pending_requests; ?></p>
            </div>
            <div class="card">
                <i class='bx bx-list-check' ></i>
                <h3>Approved Requests</h3>
                <p><?php echo $approved_requests; ?></p>
            </div>
            <div class="card">
                <i class='bx bx-package'></i>
                <h3>Suppliers</h3>
                <p><?php echo $total_suppliers; ?></p>
            </div>
            <div class="card">
                 <i class='bx bx-user-voice'></i>
                <h3>Staff Accounts</h3>
                <p><?php echo $total_staff; ?></p>
            </div>
            <div class="card">
                <i class='bx bxs-id-card' ></i>
                <h3>Admin Accounts</h3>
                <p><?php echo $total_admin; ?></p>
            </div>
        </div>
    </div>
</body>
</html>
