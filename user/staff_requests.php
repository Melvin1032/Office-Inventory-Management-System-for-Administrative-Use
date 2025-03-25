<?php
    require '../function/user_function.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="../css/table_styles.css">
</head>
<body>
<?php include '../includes/user_sidebar.php'; ?>
<div class="requests_user-content">
    
    <div class="dashboard-header">
        <h1>Request Form</h1>
        <p>Welcome, <b><?php 
        echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; 
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {;  
        }?></b></p>
    </div>
        <br>
    <form method="post">
        <div class="item-container">
            <div class="form-group">
                
                <label>Select Item:</label>
                <select name="item_id[]" required>
                    <option value="">Choose an available item</option>
                    <?php foreach ($items as $item): ?>
                        <option value="<?= htmlspecialchars($item['id']) ?>">
                            <?= htmlspecialchars($item['item_name']) ?> (Available: <?= $item['quantity'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Quantity:</label>
                <input type="number" name="quantity[]" min="1" required>

                <button type="button" class="remove-btn" onclick="removeItem(this)">X</button>
            </div>
        </div>

        <button type="button" class="add-item-btn" onclick="addItem()">+ Add More Items</button>
        <button type="submit" name="request" class="submit-btn">Submit Inventory</button>
    </form>

    <a href="staff_dashboard.php">Back to Dashboard</a>
</div>
</body>
<script src="..\assets\addItemToggle_request.js"></script>

</html>

