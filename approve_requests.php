<?php
session_start();
require 'config/config.php';

// Ensure admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Process Approve/Reject action if parameters exist
if (isset($_GET['id']) && isset($_GET['action'])) {
    $request_id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($request_id <= 0) {
        die("Invalid request: Invalid ID.");
    }

    // Fetch the request details
    $stmt = $pdo->prepare("SELECT user_id, item_name, quantity FROM requests WHERE id = ?");
    $stmt->execute([$request_id]);
    $request = $stmt->fetch();

    if (!$request) {
        die("Action failed: Request not found.");
    }

    $approved_by = $_SESSION['user_id']; // Admin ID

    if ($action === 'approve') {
        // Check if the item exists in inventory
        $stmt = $pdo->prepare("SELECT quantity FROM inventory WHERE item_name = ?");
        $stmt->execute([$request['item_name']]);
        $inventory = $stmt->fetch();

        if (!$inventory || $inventory['quantity'] < $request['quantity']) {
            die("Approval failed: Not enough stock in inventory.");
        }

        // Deduct the requested quantity from inventory
        $stmt = $pdo->prepare("UPDATE inventory SET quantity = quantity - ? WHERE item_name = ?");
        $stmt->execute([$request['quantity'], $request['item_name']]);

        // Approve the request
        $stmt = $pdo->prepare("UPDATE requests SET status = 'approved' WHERE id = ?");
        $stmt->execute([$request_id]);

        // Insert approval log
        $stmt = $pdo->prepare("INSERT INTO logs (operation, user_id, requested_by, approved_by, log_date) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute(["Request Approved", $approved_by, $request['user_id'], $approved_by]);

        header("Location: approve_requests.php?success=1");
        exit();
    } elseif ($action === 'reject') {
        // Reject the request
        $stmt = $pdo->prepare("UPDATE requests SET status = 'rejected' WHERE id = ?");
        $stmt->execute([$request_id]);

        // Insert rejection log
        $stmt = $pdo->prepare("INSERT INTO logs (operation, user_id, requested_by, approved_by, log_date) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute(["Request Rejected", $approved_by, $request['user_id'], $approved_by]);

        header("Location: approve_requests.php?success=1");
        exit();
    }
}

// Fetch all pending requests
$stmt = $pdo->query("SELECT * FROM requests WHERE status = 'pending'");
$requests = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Requests</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .container { max-width: 800px; margin: auto; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background: #f4f4f4; }
        a { text-decoration: none; color: #333; font-weight: bold; padding: 5px 10px; }
    </style>
</head>
<body>

<div class="container">
    <h1>Manage Requests</h1>

    <?php if (isset($_GET['success'])): ?>
        <p style="color: green;">Request updated successfully!</p>
    <?php endif; ?>

    <table>
        <tr>
            <th>Request ID</th>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php foreach ($requests as $request): ?>
            <tr>
                <td><?= htmlspecialchars($request['id']) ?></td>
                <td><?= htmlspecialchars($request['item_name']) ?></td>
                <td><?= htmlspecialchars($request['quantity']) ?></td>
                <td><?= htmlspecialchars($request['status']) ?></td>
                <td>
                    <a href="approve_requests.php?id=<?= $request['id'] ?>&action=approve">Approve</a> |
                    <a href="approve_requests.php?id=<?= $request['id'] ?>&action=reject">Reject</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

</div>

</body>
</html>
