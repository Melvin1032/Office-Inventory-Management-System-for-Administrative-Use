<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: login.php");
    exit();
}

require 'config/config.php';

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT id, item_name, quantity, status FROM requests WHERE user_id = ?");
$stmt->execute([$user_id]);
$requests = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .container { max-width: 800px; margin: auto; text-align: center; }
        .card { padding: 15px; margin: 10px; background: #f4f4f4; display: inline-block; width: 45%; }
        a { text-decoration: none; color: #333; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background: #f4f4f4; }
    </style>
</head>
<body>

<div class="container">
    <h1>Staff Dashboard</h1>
    <p>Welcome, Staff Member!</p>

    <div class="card">
        <h3>Request Inventory</h3>
        <a href="staff_requests.php">Make a Request</a>
    </div>

    <div class="card">
        <h3>View Inventory</h3>
        <a href="view_inventory.php">Check Available Items</a>
    </div>

    <h3>Your Requests</h3>
    <table>
        <tr>
            <th>Item</th>
            <th>Quantity</th>
            <th>Status</th>
        </tr>
        <?php foreach ($requests as $request): ?>
            <tr>
                <td><?= htmlspecialchars($request['item_name']) ?></td>
                <td><?= htmlspecialchars($request['quantity']) ?></td>
                <td><?= htmlspecialchars($request['status']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <br>
    <a href="logout.php">Logout</a>
</div>

</body>
</html>
