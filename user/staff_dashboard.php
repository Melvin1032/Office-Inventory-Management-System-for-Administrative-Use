<?php
    require '../function/user_function.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="../css/table_styles.css">
    <link rel="icon" href="../assets/logo_blck.png" type="image/png">
</head>
<body>
<?php include '../includes/user_sidebar.php'; ?>

<div class="home-content">
<div class="dashboard-header">
        <h1>Staff Dashboard</h1>
        <p>Welcome, <b><?php 
        echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; 
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {;  
        }?></b></p>
    </div>
        <div class="user_dashboard-content">
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
        </div>
        <table class="inventory-table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Request Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($requests as $request): ?>
            <tr>
                <td><?= htmlspecialchars($request['item_name']) ?></td>
                <td><?= htmlspecialchars($request['quantity']) ?></td>
                <td><?= htmlspecialchars($request['request_date']) ?></td>
                <td class="status <?= strtolower($request['status']) ?>"><b><?= htmlspecialchars($request['status']) ?></b></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</body>
</html>
