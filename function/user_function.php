<!-- FOR USERS -->

<!-- SESSION START -->
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: login.php");
    exit();
}
?>


<!-- STAFF HOME DASHBOARD -->


<!-- VIEW DATA FOR HOME DASHBOARD -->

<?php

require '../config/config.php';

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT id, item_name, quantity, status FROM requests WHERE user_id = ?");
$stmt->execute([$user_id]);
$requests = $stmt->fetchAll();
?>




<!-- STAFF REQUESTS -->
 
<?php
require '../config/config.php';
// Ensure the user is logged in and is a staff member
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: login.php");
    exit();
}

if (isset($_POST['request'])) {
    $user_id = $_SESSION['user_id'];
    $item_ids = $_POST['item_id']; // This is now an array
    $quantities = $_POST['quantity']; // This is also an array

    if (!is_array($item_ids) || !is_array($quantities)) {
        echo "Invalid request format.";
        exit();
    }

    foreach ($item_ids as $index => $item_id) {
        $item_id = intval($item_id);
        $quantity = intval($quantities[$index]);

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
            echo "Error: Item with ID $item_id does not exist.";
            exit();
        }

        if ($item['quantity'] < $quantity) {
            echo "Error: Not enough stock available for " . htmlspecialchars($item['item_name']) . ". Current stock: " . $item['quantity'];
            exit();
        }

        // Insert request into database
        $stmt = $pdo->prepare("INSERT INTO requests (stock_num, user_id, item_id, item_name, quantity, unit, status) 
                               VALUES (?, ?, ?, ?, ?, ?, 'Pending')");
        if (!$stmt->execute([$item['stock_num'], $user_id, $item_id, $item['item_name'], $quantity, $item['unit']])) {
            echo "Error submitting request for " . htmlspecialchars($item['item_name']) . ". Please try again.";
            exit();
        }
    }

    echo "All requests submitted successfully!";
}

// Fetch available items from inventory
$stmt = $pdo->query("SELECT id, item_name, quantity, unit FROM inventory WHERE quantity > 0");
$items = $stmt->fetchAll();
?>


<!-- FOR ADMIN DASHBOARD TO VIEW DATA -->

<?php
    // Fetch data from database
    $stmt = $pdo->query("SELECT COUNT(*) FROM inventory");
    $total_inventory = $stmt->fetchColumn() ?? 0;

    $stmt = $pdo->query("SELECT COUNT(*) FROM requests WHERE status = 'Pending'");
    $pending_requests = $stmt->fetchColumn() ?? 0;

    $stmt = $pdo->query("SELECT COUNT(*) FROM requests WHERE status = 'Approved'");
    $approved_requests = $stmt->fetchColumn() ?? 0;

    $stmt = $pdo->query("SELECT COUNT(DISTINCT supplier) FROM inventory");
    $total_suppliers = $stmt->fetchColumn() ?? 0;

?>

