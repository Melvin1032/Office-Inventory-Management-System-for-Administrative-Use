<?php
    require '../function/function.php';
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Manage Inventory</title>
        <style>
            body { font-family: Arial, sans-serif; padding: 20px; }
            .container { max-width: 1200px; margin: auto; text-align: center; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
            th { background: #f4f4f4; }
            .out-of-stock { color: red; font-weight: bold; }
            a { text-decoration: none; color: #007bff; margin: 5px; }
            a:hover { text-decoration: underline; }
            .actions a { margin: 0 5px; }
        </style>
    </head>
    <body>

    <div class="container">
        <h1>Manage Inventory</h1>
        <a href="logout.php">Logout</a>
        <table>
            <tr>
                <th>ID</th>
                <th>Item Name</th>
                <th>Category</th>
                <th>Supplier</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Acquisition Date</th>
                <!-- <th>Approve by</th> -->
                <th>Actions</th>
            </tr>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item["id"]) ?></td>
                <td><?= htmlspecialchars($item["item_name"]) ?></td>
                <td><?= htmlspecialchars($item["category"]) ?></td>
                <td><?= htmlspecialchars($item["supplier"]) ?></td>
                <td class="<?= $item["quantity"] == 0 ? 'out-of-stock' : '' ?>">
                <?= htmlspecialchars($item["quantity"]) ?>
                </td>
                <td><?= htmlspecialchars($item["stock_status"]) ?></td>
                <td><?= htmlspecialchars($item["last_updated"]) ?></td>
                <!-- <td><?= htmlspecialchars($item["user"]) ?></td> -->

                <td class="actions">
                <!-- Edit form -->
                <form action="edit_inventory.php" method="GET" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                    <button type="submit">Edit</button>
                </form>
                
                <!-- Delete form with confirmation before submission -->
                <form action="inventory.php" method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                    <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this item?')">Delete</button>
                </form>
            </td>


            </tr>
            <?php endforeach; ?>
        </table>
        <a href="add_inventory.php">Add New Item</a>
    </div>

    </body>
    </html>

