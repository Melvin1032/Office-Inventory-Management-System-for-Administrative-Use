<?php
    require '../function/function.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requests</title>
    <link rel="stylesheet" href="../assets/table_styles.css">
</head>
<body>
<?php include '../includes/sidebar.php'; ?>


<div class="inventory-content">
        <div class="dashboard-header">
            <h1>Approve Requests</h1>
            <p>Welcome, Admin</p>
        </div>

    <?php if (isset($_GET['success'])): ?>
        <p class="message">Request updated successfully!</p>
    <?php endif; ?>

    <table class="request-table">
    <thead>
        <tr>
            <th>Request ID</th>
            <th>Requested By </th>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Unit</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <?php foreach ($requests as $request): ?>
            <tr>
                <td><?= htmlspecialchars($request['id']) ?></td>
                <td><?= htmlspecialchars($request['username']) ?></td>
                <td><?= htmlspecialchars($request['item_name']) ?></td>
                <td><?= htmlspecialchars($request['quantity']) ?></td>
                <td><?= htmlspecialchars($request['unit']) ?></td>
                <td><?= htmlspecialchars($request['status']) ?></td>
                <td class="action-links">
    <a href="approve_requests.php?id=<?= $request['id'] ?>&action=approve" 
       class="approve-link" 
       onclick="return confirm('Are you sure you want to approve this request?')">Approve</a> |
    <a href="approve_requests.php?id=<?= $request['id'] ?>&action=reject" 
       class="reject-link" 
       onclick="return confirm('Are you sure you want to reject this request?')">Reject</a>
</td>
            </tr>
        <?php endforeach; ?>
    </table>
    </div>
</body>
</html>
