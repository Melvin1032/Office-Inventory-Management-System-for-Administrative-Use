<!-- ADD INVENTORY -->

<?php
require '../config/config.php';
session_start();
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit(); 
}

if (isset($_POST['add'])) {
    $item_name = $_POST["item_name"];
    $category = $_POST["category"];
    $supplier = $_POST["supplier"];
    $quantity = $_POST["quantity"];

    $stmt = $pdo->prepare("INSERT INTO inventory (item_name, category, supplier, quantity) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$item_name, $category, $supplier, $quantity])) {
        echo "Item added successfully. <a href='inventory.php'>View Inventory</a>";
    } else {
        echo "Error adding item.";
    }
}
?>

<!-- VIEW INVENTORY -->

<?php
require '../config/config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$stmt = $pdo->query("SELECT id, item_name, category, quantity, supplier, last_updated,
    CASE 
        WHEN quantity = 0 THEN 'Out of Stock' 
        ELSE 'In Stock' 
    END AS stock_status
FROM inventory");
$items = $stmt->fetchAll();
?>

<!-- Delete Inventory -->

<?php
require '../config/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['delete'])) {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        $stmt = $pdo->prepare("DELETE FROM inventory WHERE id = ?");
        if ($stmt->execute([$id])) {
            header("Location: inventory.php?message=Item deleted successfully");
            exit();
        } else {
            header("Location: inventory.php?error=Failed to delete item");
            exit();
        }
    } else {
        header("Location: inventory.php?error=No item ID provided");
        exit();
    }
}
?>


<!-- APPROVE REQUESTS -->

<?php
require '../config/config.php';

// Ensure admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Process Approve/Reject action if parameters exist
if (isset($_GET['id']) && isset($_GET['action'])) {
    $request_id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($request_id <= 0 || !in_array($action, ['approve', 'reject'])) {
        die("Invalid request: Invalid ID or action.");
    }

    // Fetch the request details, including the username from the users table
    $stmt = $pdo->prepare("SELECT r.id, u.username, r.item_name, r.quantity, r.status 
                           FROM requests r
                           JOIN users u ON r.user_id = u.id
                           WHERE r.id = ?");
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
        $stmt = $pdo->prepare("INSERT INTO logs (operation, user_id, requested_by, approved_by, item_name, quantity, created_at) 
                               VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute(["Request Approved", $approved_by, $request['user_id'], $approved_by, $request['item_name'], $request['quantity']]);

        header("Location: approve_requests.php?success=1");
        exit();
    } elseif ($action === 'reject') {
        // Reject the request
        $stmt = $pdo->prepare("UPDATE requests SET status = 'rejected' WHERE id = ?");
        $stmt->execute([$request_id]);

        // Insert rejection log
        $stmt = $pdo->prepare("INSERT INTO logs (operation, user_id, requested_by, approved_by, item_name, quantity, created_at) 
                               VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute(["Request Rejected", $approved_by, $request['user_id'], $approved_by, $request['item_name'], $request['quantity']]);

        header("Location: approve_requests.php?success=1");
        exit();
    }
}

// Fetch all pending requests along with the username
$stmt = $pdo->query("SELECT r.id, u.username, r.item_name, r.quantity, r.status 
                     FROM requests r
                     JOIN users u ON r.user_id = u.id
                     WHERE r.status = 'pending'");
$requests = $stmt->fetchAll();
?>



