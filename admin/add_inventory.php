<?php
    require 'function/function.php';
?>

<form method="post">

    <!-- Dropdown for category selection -->
    <select name="category" required>
        <option value="">Select Category</option>
        <option value="Office Supplies">Office Supplies</option>
        <option value="Janitorial Supplies">Janitorial Supplies</option>
        <option value="Electrical Supplies">Electrical Supplies</option>
    </select>

    <input type="text" name="item_name" placeholder="Item Name" required>
    <input type="text" name="supplier" placeholder="Name of Supplier" required>
    
    <input type="number" name="quantity" placeholder="Quantity" required>
    <button type="submit" name="add">Add Item</button>
</form>