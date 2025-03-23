<?php
    require '../function/function.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Inventory</title>
    <link rel="stylesheet" href="../includes/includes.css">
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
    <?php 
    include '../includes/header.php';
    ?>
   
   <h1 class="table-title">Manage Inventory</h1>
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
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item["stock_num"]) ?></td>
                <td><?= htmlspecialchars($item["item_name"]) ?></td>
                <td><?= htmlspecialchars($item["category"]) ?></td>
                <td><?= htmlspecialchars($item["supplier"]) ?></td>
                <td class="<?= $item["quantity"] == 0 ? 'out-of-stock' : '' ?>"><?= htmlspecialchars($item["quantity"]) ?>
                </td>
                <td><?= htmlspecialchars($item["unit"]) ?></td>
                <td><?= htmlspecialchars($item["stock_status"]) ?></td>
                <td><?= htmlspecialchars($item["last_updated"]) ?></td>
                <td class="actions">
                    <form method="GET" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                        <button type="submit" class="btn btn-primary">Edit</button>
                    </form>

                    <form method="POST" style="display:inline;">
                     <input type="hidden" name="id" value="<?= $item['id'] ?>">
                     <button type="submit" name="delete" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>

                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="add-container">
        <a href="add_inventory.php" class="btn btn-success">Add New Item</a>
    </div>

</body> 



</html>