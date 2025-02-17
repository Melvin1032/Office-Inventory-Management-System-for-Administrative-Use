<?php
    require 'function/function.php';
?>

<form method="post">
    <input type="text" name="item_name" value="<?= $item['item_name'] ?>" required>
    <input type="text" name="category" value="<?= $item['category'] ?>" required>
    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" required>
    <button type="submit">Update</button>
</form>
