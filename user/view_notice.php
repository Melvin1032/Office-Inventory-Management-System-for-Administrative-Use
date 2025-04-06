<?php
    require '../function/user_function.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Inventory</title>
    <link rel="stylesheet" href="../css/table_styles.css">
    <link rel="icon" href="../assets/logo_blck.png" type="image/png">
</head>
<body>
<?php include '../includes/user_sidebar.php'; ?>

<div class="inventory-content">
    <div class="dashboard-header">
        <h1>View Notice from the Admin</h1>
        <p>Welcome, <b><?php 
            echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; 
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {  
            }
        ?></b></p>
    </div>
    <br><br>
    <div class="messages-container">
        <?php foreach ($notifications as $notification): ?>
            <div class="message-card">
                <div class="message-header">
                    <span class="sender"><?= htmlspecialchars($notification['sender_name']) ?></span>
                    <span class="message-date"><?= htmlspecialchars($notification['created_at']) ?></span>
                </div>
                <div class="message-body">
                    <p><?= htmlspecialchars($notification['message']) ?></p>
                </div>
                <a href="view_notice.php?id=<?= $notification['id'] ?>&action=approve"  
                class="mark-as-read" 
                onclick="return confirm('Are you sure you want to approve this request?')">Mark as Read</a>
                
                <a href="view_notice.php?id=<?= $notification['id'] ?>&action=delete"  
                   class="delete-message" 
                   onclick="return confirm('Are you sure you want to delete this message?')">Delete</a>


            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>

</html>

