<?php
    require '../function/function.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Requests</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .container { max-width: 800px; margin: auto; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background: #f4f4f4; }
        a { text-decoration: none; color: #333; font-weight: bold; padding: 5px 10px; }
        .message { color: green; }
    </style>
</head>
<body>

<div class="container">
    <h1>Manage Requests</h1>

    <?php if (isset($_GET['success'])): ?>
        <p class="message">Request updated successfully!</p>
    <?php endif; ?>

    <table>
        <tr>
            <th>Request ID</th>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php foreach ($requests as $request): ?>
            <tr>
                <td><?= htmlspecialchars($request['id']) ?></td>
                <td><?= htmlspecialchars($request['item_name']) ?></td>
                <td><?= htmlspecialchars($request['quantity']) ?></td>
                <td><?= htmlspecialchars($request['status']) ?></td>
                <td>
                    <a href="approve_requests.php?id=<?= $request['id'] ?>&action=approve" onclick="return confirm('Are you sure you want to approve this request?')">Approve</a> |
                    <a href="approve_requests.php?id=<?= $request['id'] ?>&action=reject" onclick="return confirm('Are you sure you want to reject this request?')">Reject</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

</div>

</body>
</html>
