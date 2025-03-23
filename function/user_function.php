<!-- FOR USERS -->

<!-- STAFF REQUESTS -->
<?php
require '../config/config.php';
session_start();

// Ensure the user is logged in and is a staff member
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: login.php");
    exit();
}

if (isset($_POST['request'])) {
    $user_id = $_SESSION['user_id'];
    $item_id = intval($_POST['item_id']);
    $quantity = intval($_POST['quantity']);

    // Validate input
    if ($item_id <= 0 || $quantity <= 0) {
        echo "Invalid request. Please provide valid item ID and quantity.";
        exit();
    }

    // Check if item exists and has enough stock
    $stmt = $pdo->prepare("SELECT stock_num, item_name, quantity, unit FROM inventory WHERE id = ?");
    $stmt->execute([$item_id]);
    $item = $stmt->fetch();

    if (!$item) {
        echo "Error: Item does not exist.";
        exit();
    }

    if ($item['quantity'] < $quantity) {
        echo "Error: Not enough stock available. Current stock: " . $item['quantity'];
        exit();
    }

    // Insert request into database
    $stmt = $pdo->prepare("INSERT INTO requests (stock_num, user_id, item_id, item_name, quantity, unit, status) 
                           VALUES (?, ?, ?, ?, ?, ?, 'Pending')");
    if ($stmt->execute([$item['stock_num'], $user_id, $item_id, $item['item_name'], $quantity, $item['unit']])) {
        echo "Request submitted successfully!";
    } else {
        echo "Error submitting request. Please try again.";
    }
}

// Fetch available items from inventory
$stmt = $pdo->query("SELECT id, item_name, quantity, unit FROM inventory WHERE quantity > 0");
$items = $stmt->fetchAll();
?>
