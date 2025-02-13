<?php
session_start();
require 'config/config.php'; // Include database connection

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: login.php");
    exit();
}

// Fetch inventory items
$stmt = $pdo->query("SELECT item_name, category, quantity, min_stock, supplier, last_updated,
    CASE 
        WHEN quantity = 0 THEN 'Out of Stock' 
        ELSE 'In Stock' 
    END AS stock_status
FROM inventory");

$inventory = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Inventory</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .container { max-width: 800px; margin: auto; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background: #f4f4f4; }
        .out-of-stock { color: red; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <h1>Inventory List</h1>
    <table>
        <tr>
            <th>Item Name</th>
            <th>Category</th>
            <th>Quantity</th>
            <th>Min Stock</th>
            <th>Supplier</th>
            <th>Last Updated</th>
            <th>Status</th>
        </tr>
        <?php foreach ($inventory as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['item_name']) ?></td>
                <td><?= htmlspecialchars($item['category']) ?></td>
                <td><?= htmlspecialchars($item['quantity']) ?></td>
                <td><?= htmlspecialchars($item['min_stock']) ?></td>
                <td><?= htmlspecialchars($item['supplier']) ?></td>
                <td><?= htmlspecialchars($item['last_updated']) ?></td>
                <td class="<?= $item['quantity'] == 0 ? 'out-of-stock' : '' ?>">
                    <?= htmlspecialchars($item['stock_status']) ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>
