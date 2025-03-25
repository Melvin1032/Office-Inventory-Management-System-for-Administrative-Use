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

<?php if ($error_message): ?>
    <div class="error-message">
        <?= htmlspecialchars($error_message) ?>
    </div>
<?php endif; ?>

<?php if ($success_message): ?>
    <div class="success-message">
        <?= htmlspecialchars($success_message) ?>
    </div>
<?php endif; ?>

<form method="post">
    <div class="item-container">
        <div class="form-group">
            <label>Select Item:</label>
            <select name="item_id[]" required>
                <option value="">Choose an available item</option>
                <?php foreach ($items as $item): ?>
                    <option value="<?= htmlspecialchars($item['id']) ?>">
                        <?= htmlspecialchars($item['item_name']) ?> (Available: <?= $item['quantity'] . ' ' . htmlspecialchars($item['unit']) ?>)
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
</div>

<!-- I PUT HERE THE ADD ITEM TOGGLE JavaScript file BECUASE IT CANNOT FETCH THE DATA FROM THE PHP -->
<script>
    function addItem() {
    const container = document.querySelector(".item-container");
    const newItem = document.createElement("div");
    newItem.classList.add("form-group");
    newItem.innerHTML = `
        <label>Select Item:</label>
        <select name="item_id[]" required>
            <option value="">Choose an available item</option>
            ${itemsOptions()}
        </select>
        
        <label>Quantity:</label>
        <input type="number" name="quantity[]" min="1" required>
        
        <button type="button" class="remove-btn" onclick="removeItem(this)">X</button>
    `;
    container.appendChild(newItem);
}

function removeItem(button) {
    button.parentElement.remove();
}

// Function to generate available item options from PHP data
function itemsOptions() {
    return `<?php foreach ($items as $item): ?>
                <option value="<?= htmlspecialchars($item['id']) ?>">
                    <?= htmlspecialchars($item['item_name']) ?> (Available: <?= $item['quantity'] . ' ' . htmlspecialchars($item['unit']) ?>)
                </option>
            <?php endforeach; ?>`;
}

</script>

</body>


</html>

