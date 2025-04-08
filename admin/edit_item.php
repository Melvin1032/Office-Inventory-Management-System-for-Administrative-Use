<?php
require '../config/config.php';
session_start();

if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit(); 
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Fetch the current details of the inventory item
    $stmt = $pdo->prepare("SELECT * FROM inventory WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$item) {
        die("Item not found.");
    }
} else {
    die("Invalid item ID.");
}

// Handle form submission for updating inventory
if (isset($_POST['update'])) {
    $category = $_POST['category'];
    $item_name = $_POST['item_name'];
    $quantity = $_POST['quantity'];
    $unit = $_POST['unit'];
    $supplier = $_POST['supplier'];

    // Update the item details in the inventory
    try {
        $stmt = $pdo->prepare("UPDATE inventory SET category = ?, item_name = ?, quantity = ?, unit = ?, supplier = ?, last_updated = NOW() WHERE id = ?");
        $stmt->execute([$category, $item_name, $quantity, $unit, $supplier, $id]);

        // Redirect to inventory page with success message
        header("Location: inventory.php?message=Item updated successfully");
        exit();

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Inventory</title>
    <link rel="icon" href="../assets/icon.png" type="image/png">
</head>
<?php include '../includes/sidebar.php'; ?>
<body>
    <div class="container">
        <div class="form-wrapper">
            <h2>Edit Inventory Item</h2>
            <form method="POST" action="edit_item.php?id=<?= $item['id'] ?>">
                <div class="form-group">
                    <label for="item_name">Item Name</label>
                    <input type="text" id="item_name" name="item_name" value="<?= htmlspecialchars($item['item_name']) ?>" required class="form-input">
                </div>
                <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category" name="category" class="form-input" required>
                        <option value="Office Supplies" <?= $item['category'] == 'Office Supplies' ? 'selected' : '' ?>>Office Supplies</option>
                        <option value="Janitorial Supplies" <?= $item['category'] == 'Janitorial Supplies' ? 'selected' : '' ?>>Janitorial Supplies</option>
                        <option value="Electrical Supplies" <?= $item['category'] == 'Electrical Supplies' ? 'selected' : '' ?>>Electrical Supplies</option>
                        <option value="Computer Supplies" <?= $item['category'] == 'Computer Supplies' ? 'selected' : '' ?>>Computer Supplies</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" id="quantity" name="quantity" value="<?= $item['quantity'] ?>" required class="form-input">
                </div>
                <div class="form-group">
                    <label for="unit">Unit</label>
                    <input type="text" id="unit" name="unit" value="<?= htmlspecialchars($item['unit']) ?>" required class="form-input" readonly>
                </div>
                <div class="form-group">
                    <label for="supplier">Supplier</label>
                    <input type="text" id="supplier" name="supplier" value="<?= htmlspecialchars($item['supplier']) ?>" required class="form-input">
                </div>
                <button type="submit" name="update" class="submit-btn">Update Item</button>
            </form>
        </div>
    </div>
</body>
</html>

<style>
/* Basic reset and body styling */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Arial', sans-serif;
    background-color:rgb(255, 255, 255);  /* Subtle background color */
    height: 100vh;
}

/* Container wrapper for centering the form */
.container {
    width: 100%;
    max-width: 900px;  /* Max width for the form container */
    padding: 40px;
    display: flex;
    justify-content: center;
    margin: auto;
}

/* Form wrapper styling */
.form-wrapper {
    background-color: white;
    padding: 30px;
    border-radius: 12px;
    width: 100%;
    max-width: 600px;
    display: flex;
    flex-direction: column;
}

h2 {
    text-align: center;
    color: #333;
    font-size: 28px;
    margin-bottom: 30px;
}

/* Form group styling */
.form-group {
    margin-bottom: 20px;
}

/* Labels for inputs */
.form-group label {
    font-weight: bold;
    color: #495057;
    display: block;
    margin-bottom: 5px;
}

/* Input and select styling */
.form-input {
    width: 100%;
    padding: 14px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 8px;
    outline: none;
    transition: border-color 0.3s ease;
}

.form-input:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

/* Submit button styling */
.submit-btn {
    padding: 12px;
    font-size: 18px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.submit-btn:hover {
    background-color: #0056b3;
}

.submit-btn:active {
    background-color: #003d7a;
}

.form-input[readonly] {
    background-color: #f1f1f1;  /* Light grey background to indicate it's non-editable */
    border-color: #ccc;         /* Lighter border color */
    cursor: not-allowed;        /* Change the cursor to indicate it's not editable */
}


/* Add responsive design */
@media (max-width: 768px) {
    .form-wrapper {
        padding: 20px;
        max-width: 100%;
    }

    h2 {
        font-size: 24px;
    }
}


</style>