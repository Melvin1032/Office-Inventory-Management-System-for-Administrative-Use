<!-- FOR USERS -->


<!-- STAFF REQUESTS -->


<?php
require '../config/config.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: login.php");
    exit();
}

if (isset($_POST['request'])) {
    $user_id = $_SESSION['user_id'];
    $item_id = intval($_POST['item_id']); // Get selected item ID
    $quantity = intval($_POST['quantity']); // Get entered quantity

    // Validate input
    if ($item_id <= 0 || $quantity <= 0) {
        echo "Invalid request.";
        exit();
    }

    // Check if item exists and has enough stock
    $stmt = $pdo->prepare("SELECT item_name, quantity FROM inventory WHERE id = ?");
    $stmt->execute([$item_id]);
    $item = $stmt->fetch();

    if (!$item || $item['quantity'] < $quantity) {
        echo "Error: Not enough stock available.";
        exit();
    }

    // Insert request into database
    $stmt = $pdo->prepare("INSERT INTO requests (user_id, item_id, item_name, quantity, status) VALUES (?, ?, ?, ?, 'pending')");
    if ($stmt->execute([$user_id, $item_id, $item['item_name'], $quantity])) {
        echo "Request submitted successfully!";
    } else {
        echo "Error submitting request.";
    }
}

// Fetch available items from inventory
$stmt = $pdo->query("SELECT id, item_name, quantity FROM inventory WHERE quantity > 0");
$items = $stmt->fetchAll();
?>