<?php
session_start();
require 'config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

<form method="post">
    <label>Select Item:</label>
    <select name="item_id" required>
        <option value="">-- Choose an available item --</option>
        <?php foreach ($items as $item): ?>
            <option value="<?= htmlspecialchars($item['id']) ?>">
                <?= htmlspecialchars($item['item_name']) ?> (Available: <?= $item['quantity'] ?>)
            </option>
        <?php endforeach; ?>
    </select>

    <label>Quantity:</label>
    <input type="number" name="quantity" min="1" required>

    <button type="submit">Request Item</button>
</form>

<a href="staff_dashboard.php">Back to Dashboard</a>
