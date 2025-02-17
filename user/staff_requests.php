<?php
require '../function/user_function.php';
?>

<form method="post">
    <label>Select Item:</label>
    <select name="item_id" required>
        <option value="">-- Choose an available item --</option>
        <?php foreach ($items as $item): ?>
            <option value="<?= htmlspecialchars($item['id']) ?>">
                <?= htmlspecialchars($item['item_name']) ?> (Available: <?= $item['quantity'] ?>)
            </option>
        <?php endforeach; ?>
    </select>

    <label>Quantity:</label>
    <input type="number" name="quantity" min="1" required>

    <button type="submit" name="request">Request Item</button>
</form>

<?php if (isset($_SESSION['error'])): ?>
    <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
    <p style="color: green;"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
<?php endif; ?>

<a href="staff_dashboard.php">Back to Dashboard</a>

