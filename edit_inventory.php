<?php
require 'config/config.php';
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$id = $_GET["id"];
$stmt = $pdo->prepare("SELECT * FROM inventory WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = $_POST["item_name"];
    $category = $_POST["category"];
    $quantity = $_POST["quantity"];

    $updateStmt = $pdo->prepare("UPDATE inventory SET item_name = ?, category = ?, quantity = ? WHERE id = ?");
    if ($updateStmt->execute([$item_name, $category, $quantity, $id])) {
        header("Location: inventory.php");
        exit();
    } else {
        echo "Error updating item.";
    }
}
?>
<form method="post">
    <input type="text" name="item_name" value="<?= $item['item_name'] ?>" required>
    <input type="text" name="category" value="<?= $item['category'] ?>" required>
    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" required>
    <button type="submit">Update</button>
</form>
