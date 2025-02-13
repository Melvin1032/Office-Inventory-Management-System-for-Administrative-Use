<?php
require 'config/config.php';
session_start();
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit(); 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = $_POST["item_name"];
    $category = $_POST["category"];
    $quantity = $_POST["quantity"];

    $stmt = $pdo->prepare("INSERT INTO inventory (item_name, category, quantity) VALUES (?, ?, ?)");
    if ($stmt->execute([$item_name, $category, $quantity])) {
        echo "Item added successfully. <a href='inventory.php'>View Inventory</a>";
    } else {
        echo "Error adding item.";
    }
}
?>

<form method="post">
    <input type="text" name="item_name" placeholder="Item Name" required>
    
    <!-- Dropdown for category selection -->
    <select name="category" required>
        <option value="">Select Category</option>
        <option value="Office Supplies">Office Supplies</option>
        <option value="Janitorial Supplies">Janitorial Supplies</option>
        <option value="Electrical Supplies">Electrical Supplies</option>
    </select>

    <input type="number" name="quantity" placeholder="Quantity" required>
    <button type="submit">Add Item</button>
</form>
