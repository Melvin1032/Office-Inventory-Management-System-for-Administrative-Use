<?php
    require '../function/function.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Inventory</title>
    <link rel="stylesheet" href="../css/table_styles.css">
    <link rel="icon" href="../assets/icon.png" type="image/png">
</head>
<body>
<?php include '../includes/sidebar.php'; ?>

<div class="inventory-content">
        <div class="dashboard-header">
            <h1>Manage Inventory</h1>
            <p>Welcome, Admin</p>
        </div>
    <table class="inventory-table">
        <thead>
            <tr>
                <th>Item Stock No.</th>
                <th>Item Name</th>
                <th>Category</th>
                <th>Supplier</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Status</th>
                <th>Acquisition Date</th>
                <th>Actions</th>
            </tr>
        </thead>
       <!-- manage_inventory.php -->

<tbody>
    <?php foreach ($items as $item): ?>
    <tr>
        <td><?= htmlspecialchars($item["stock_num"]) ?></td>
        <td><?= htmlspecialchars($item["item_name"]) ?></td>
        <td><?= htmlspecialchars($item["category"]) ?></td>
        <td><?= htmlspecialchars($item["supplier"]) ?></td>
        <td class="<?= $item["quantity"] == 0 ? 'out-of-stock' : '' ?>"><?= htmlspecialchars($item["quantity"]) ?></td>
        <td><?= htmlspecialchars($item["unit"]) ?></td>
        <td class="stock-status <?= strtolower(str_replace(' ', '-', $item['stock_status'])) ?>">
        <b><?= htmlspecialchars($item["stock_status"]) ?></b>
        </td>
        <td><?= htmlspecialchars($item["last_updated"]) ?></td>
        <td>
            <!-- Edit Button -->
            <form method="GET" action="edit_item.php" style="display:inline;">
                <input type="hidden" name="id" value="<?= $item['id'] ?>">
                <button type="submit" class="btn btn-primary"><i class='bx bx-edit' ></i></button>
            </form>

            <!-- Delete Button -->
            <form method="POST" style="display:inline;">
                <input type="hidden" name="id" value="<?= $item['id'] ?>">
                <button type="submit" name="delete" class="btn btn-danger" onclick="return confirm('Are you sure?')"><i class='bx bx-trash' ></i></button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</tbody>

    </table>
    </div>
    <div class="add-container">
        <button class="btn btn-success" onclick="openModal()">Add New Item</button>
    </div>

    <!-- Add Inventory Modal -->
    <div id="addInventoryModal" class="modal">
        <div class="modal-content">
            <button class="close-btn" onclick="closeModal()">×</button>
            <h2>Add Inventory Items</h2>
            <form method="post">
                
                <label for="supplier">Supplier:</label>
                <input type="text" name="supplier" placeholder="Name of Supplier" required>

                <div id="inventory-items">
                    <div class="item-row">
                        <select name="category[]" required>
                            <option value="">Select Category</option>
                            <option value="Office Supplies">Office Supplies</option>
                            <option value="Janitorial Supplies">Janitorial Supplies</option>
                            <option value="Electrical Supplies">Electrical Supplies</option>
                            <option value="Computer Supplies">Computer Supplies</option>
                        </select>

                        <input type="text" name="item_name[]" placeholder="Item Name" required>
                        <input type="number" name="quantity[]" placeholder="Quantity" required>

                        <select name="unit[]" required>
                            <option value="">Units</option>
                            <option value="Reams">Reams</option>
                            <option value="Piece/s">Piece/s</option>
                        </select>

                        <button type="button" class="remove-btn" onclick="removeItem(this)">X</button>
                    </div>
                </div>

                <button type="button" class="add-btn" onclick="addItem()">+ Add More Items</button>
                <button type="submit" name="add" class="submit-btn">Submit Inventory</button>

            </form>
        </div>
    </div>
    
<!-- JS SCRIPT -->
<script src="../js/modal.js"></script>

</body>
</html>