<?php
    require '../function/function.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Inventory</title>
    <link rel="stylesheet" href="../css/table_styles.css">
</head>
<body>
<?php include '../includes/user_sidebar.php'; ?>

<div class="inventory-content">
        <div class="dashboard-header">
            <h1>View Inventory</h1>
            <p>Welcome, <b><?php 
            echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; 
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {;  
            }
        ?></b></p>
        </div>
    <table class="inventory-table">
        <thead>
            <tr>
                <th>Item Stock No.</th>
                <th>Item Name</th>
                <th>Category</th>
                <th>Supplier</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Status</th>
                <th>Acquisition Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item["stock_num"]) ?></td>
                <td><?= htmlspecialchars($item["item_name"]) ?></td>
                <td><?= htmlspecialchars($item["category"]) ?></td>
                <td><?= htmlspecialchars($item["supplier"]) ?></td>
                <td class="<?= $item["quantity"] == 0 ? 'out-of-stock' : '' ?>"><?= htmlspecialchars($item["quantity"]) ?></td>
                <td><?= htmlspecialchars($item["unit"]) ?></td>
                <td class="stock-status <?= strtolower(str_replace(' ', '-', $item['stock_status'])) ?>">
                <b><?= htmlspecialchars($item["stock_status"]) ?></b>
                </td>
                <td><?= htmlspecialchars($item["last_updated"]) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</body>

</html>

