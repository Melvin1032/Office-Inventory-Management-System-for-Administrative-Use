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
$stmt = $pdo->prepare("SELECT id, item_name, request_date, quantity, status 
                       FROM requests 
                       WHERE user_id = ? 
                       ORDER BY request_date DESC");

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

$error_message = "";
$success_message = "";

if (isset($_POST['request'])) {
    $user_id = $_SESSION['user_id'];
    $item_ids = $_POST['item_id']; // This is now an array
    $quantities = $_POST['quantity']; // This is also an array

    if (!is_array($item_ids) || !is_array($quantities)) {
        $error_message = "Invalid request format.";
    } else {
        foreach ($item_ids as $index => $item_id) {
            $item_id = intval($item_id);
            $quantity = intval($quantities[$index]);

            // Validate input
            if ($item_id <= 0 || $quantity <= 0) {
                $error_message = "Invalid request. Please provide valid item ID and quantity.";
                break;
            }

            // Check if item exists and has enough stock
            $stmt = $pdo->prepare("SELECT stock_num, item_name, quantity, unit FROM inventory WHERE id = ?");
            $stmt->execute([$item_id]);
            $item = $stmt->fetch();

            if (!$item) {
                $error_message = "Error: Item with ID $item_id does not exist.";
                break;
            }

            if ($item['quantity'] < $quantity) {
                $error_message = "Error: Not enough stock available for " . htmlspecialchars($item['item_name']) . ". Current stock: " . $item['quantity'];
                break;
            }

            // Insert request into database
            $stmt = $pdo->prepare("INSERT INTO requests (stock_num, user_id, item_id, item_name, quantity, unit, status) 
                                   VALUES (?, ?, ?, ?, ?, ?, 'Pending')");
            if (!$stmt->execute([$item['stock_num'], $user_id, $item_id, $item['item_name'], $quantity, $item['unit']])) {
                $error_message = "Error submitting request for " . htmlspecialchars($item['item_name']) . ". Please try again.";
                break;
            }
        }

        if (!$error_message) {
            $success_message = "All requests submitted successfully!";
        }
    }
}

// Fetch available items from inventory

$stmt = $pdo->query("SELECT id, item_name, quantity, unit FROM inventory WHERE quantity > 0");
$items = $stmt->fetchAll();
?>






<!-- VIEW INVENTORY -->

<?php
$stmt = $pdo->query("SELECT id, stock_num, item_name, category, quantity, unit, supplier, last_updated,
    CASE 
        WHEN quantity = 0 THEN 'Out of Stock' 
        ELSE 'In Stock' 
    END AS stock_status
FROM inventory");
$items = $stmt->fetchAll();
?>




<!-- FOR USER DASHBOARD TO VIEW DATA -->

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



<!-- FOR USER DASHBOARD BASED ON USER ID -->

<?php 
    // Assuming you have the user's ID stored in the session
    $user_id = $_SESSION['user_id'];

    // Fetch pending requests
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM requests WHERE user_id = ? AND status = 'pending'");
    $stmt->execute([$user_id]);
    $pending_requests = $stmt->fetchColumn();
    
    // Fetch approved requests
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM requests WHERE user_id = ? AND status = 'approved'");
    $stmt->execute([$user_id]);
    $approved_requests = $stmt->fetchColumn();
    
    // Fetch all requests for the logged-in user
    $stmt = $pdo->prepare("SELECT * FROM requests WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<!-- VIEW NOTIF -->

<?php
// Assuming the database connection is already available

// Query to fetch notifications with admin's username (sender)
$stmt = $pdo->prepare("SELECT n.*, u.username AS sender_name 
                       FROM notifications n
                       JOIN users u ON n.sender_id = u.id
                       ORDER BY n.created_at DESC"); // Fetch notifications and sender's name
$stmt->execute();
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- NOTIF USER COUNT -->

<?php
// Assuming the session is already started and the user is logged in
$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Query to count unread notifications for the logged-in user
$stmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND status = 'unread'");
$stmt->execute([$user_id]);
$unreadNotificationsCount = $stmt->fetchColumn();
?>



<!-- MARK AS READ -->
<?php
if (isset($_GET['id']) && isset($_GET['action'])) {
    $notification_id = $_GET['id'];
    $action = $_GET['action'];
    
    if ($action === 'approve') {
        // Update the notification status to 'read'
        $query = "UPDATE notifications SET status = 'read' WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$notification_id]);
        
        // Redirect to the notifications page after the update
        header("Location: view_notice.php");
        exit();
    }
    
    if ($action === 'delete') {
        // Delete the notification from the database
        $query = "DELETE FROM notifications WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$notification_id]);
        
        // Redirect to the notifications page after deletion
        header("Location: view_notice.php");
        exit();
    }
}
?>

