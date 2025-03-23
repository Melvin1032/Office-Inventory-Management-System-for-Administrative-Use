<!-- SESSION CHECK -->

<?php
require '../config/config.php';
session_start();

if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit(); 
}
?>

<!-- ADD INVENTORY -->

<?php
// Assuming you have already established a PDO connection in $pdo

if (isset($_POST['add'])) {
    $category_prefixes = [
        'Office Supplies' => '(1010)',
        'Janitorial Supplies' => '(2020)',
        'Electrical Supplies' => '(3030)',
    ];
    
    $category = $_POST["category"];
    
    // Get the category prefix or default to '0000'
    $category_prefix = isset($category_prefixes[$category]) ? $category_prefixes[$category] : '0000';
    
    // Generate a unique stock number
    do {
        $random_number = rand(100, 999);
        $stock_num = $category_prefix . $random_number;

        // Check if the stock number already exists
        $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM inventory WHERE stock_num = ?");
        $check_stmt->execute([$stock_num]);
        $exists = $check_stmt->fetchColumn();
    } while ($exists > 0); // Repeat until a unique stock number is found

    // Get the item details from the form
    $item_name = $_POST["item_name"];
    $supplier = $_POST["supplier"];
    $quantity = $_POST["quantity"];
    $unit = $_POST["unit"];

    // Prepare the SQL statement to insert the new inventory item
    $stmt = $pdo->prepare("INSERT INTO inventory (stock_num, item_name, category, supplier, quantity, unit) VALUES (?, ?, ?, ?, ?, ?)");
    
    // Execute the statement and check for success
    if ($stmt->execute([$stock_num, $item_name, $category, $supplier, $quantity, $unit])) {
        echo "Item added successfully. <a href='inventory.php'>View Inventory</a>";
    } else {
        echo "Error adding item.";
    }
}
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



<!-- DELETE INVENTORY -->

<?php

if (isset($_POST['delete'])) {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        // Prepare and execute the DELETE query
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

// Process Approve/Reject action if parameters exist
if (isset($_GET['id']) && isset($_GET['action'])) {
    $request_id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($request_id <= 0 || !in_array($action, ['approve', 'reject'])) {
        die("Invalid request: Invalid ID or action.");
    }

    // Fetch the request details including stock number
    $stmt = $pdo->prepare("SELECT r.id, u.username, r.user_id, r.item_name, r.quantity, r.unit, r.status, i.stock_num 
                           FROM requests r
                           JOIN users u ON r.user_id = u.id
                           LEFT JOIN inventory i ON r.item_name = i.item_name
                           WHERE r.id = ?");
    $stmt->execute([$request_id]);
    $request = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$request) {
        die("Action failed: Request not found.");
    }

    $approved_by = $_SESSION['user_id']; // Admin ID
    $stock_num = $request['stock_num'] ?? 'N/A'; // Default if NULL

    try {
        $pdo->beginTransaction(); // Start transaction

        if ($action === 'approve') {
            // Check if the item exists in inventory and has enough stock
            $stmt = $pdo->prepare("SELECT quantity FROM inventory WHERE item_name = ?");
            $stmt->execute([$request['item_name']]);
            $inventory = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$inventory || $inventory['quantity'] < $request['quantity']) {
                throw new Exception("Approval failed: Not enough stock in inventory.");
            }

            // Deduct the requested quantity from inventory
            $stmt = $pdo->prepare("UPDATE inventory SET quantity = quantity - ? WHERE item_name = ?");
            $stmt->execute([$request['quantity'], $request['item_name']]);

            // Approve the request
            $stmt = $pdo->prepare("UPDATE requests SET status = 'approved' WHERE id = ?");
            $stmt->execute([$request_id]);

            // Insert approval log
            $stmt = $pdo->prepare("INSERT INTO logs (operation, user_id, requested_by, approved_by, stock_num, item_name, quantity, unit, created_at) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([
                "Request Approved", $approved_by, $request['user_id'], $approved_by,
                $stock_num, $request['item_name'], $request['quantity'], $request['unit']
            ]);

        } elseif ($action === 'reject') {
            // Reject the request
            $stmt = $pdo->prepare("UPDATE requests SET status = 'rejected' WHERE id = ?");
            $stmt->execute([$request_id]);
        }

        $pdo->commit(); // Commit transaction
        header("Location: approve_requests.php?success=1");
        exit();

    } catch (Exception $e) {
        $pdo->rollBack(); // Rollback if any error occurs
        die("Error: " . $e->getMessage());
    }
}

// Fetch all pending requests along with the username
$stmt = $pdo->query("SELECT r.id, u.username, r.item_name, r.quantity, r.unit, r.status 
                     FROM requests r
                     JOIN users u ON r.user_id = u.id
                     WHERE r.status = 'Pending'");
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>




<!-- VIEW STAFF ACCOUNTS -->

<?php

$stmt = $pdo->query("SELECT id, username, role, created_at FROM users");
$user = $stmt->fetchAll();
?>




<!-- DELETE STAFF ACCOUNTS -->

<?php

if (isset($_POST['delete_user'])) {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        // Prepare and execute the DELETE query
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        if ($stmt->execute([$id])) {
            header("Location: staff_accounts.php?message=Item deleted successfully");
            exit();
        } else {
            header("Location: staff_accounts.php?error=Failed to delete item");
            exit();
        }
    } else {
        header("Location: staff_accounts.php?error=No item ID provided");
        exit();
    }
}
?>
