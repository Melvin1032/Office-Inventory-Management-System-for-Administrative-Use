<?php
    require '../function/function.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/table_styles.css">
    <link rel="icon" href="../assets/logo_blck.png" type="image/png">
    <title>Send Notice</title>
</head>
<body>
<?php include '../includes/sidebar.php'; ?>

<div class="staff-content">
    <div class="dashboard-header">
        <h1>Send Notice</h1>
        <p>Welcome, Admin <b><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin'; ?></b></p>
    </div>

    <!-- Send Notice Form -->
    <form method="POST">
        <label for="user_id">Select User:</label>
        <select name="user_id" required>
            <?php
            // Fetch all users with the 'staff' role
            $stmt = $pdo->query("SELECT id, username FROM users WHERE role = 'staff'");
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($users as $user) {
                echo "<option value=\"{$user['id']}\">{$user['username']}</option>";
            }
            ?>
        </select>

        <label for="message">Message:</label>
        <textarea name="message" required></textarea>

        <button type="submit" name="send_notification">Send Notification</button>
    </form>

    <?php
    // Display success message if notification is sent
    if (isset($_GET['success'])) {
        echo "<p style='color: green;'>" . htmlspecialchars($_GET['success']) . "</p>";
    }
    ?>
</div>

</body>
</html>
