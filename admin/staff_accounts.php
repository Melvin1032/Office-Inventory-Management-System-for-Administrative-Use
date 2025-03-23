
<?php
    require '../function/function.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/table_styles.css">
    <title>Staff Accounts</title>

</head>
<body>
<?php include '../includes/header.php';?>

  <h1 class="table-title">Staff Accounts</h1>
    <table class="accounts-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Account Created on</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($user as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user["id"]) ?></td>
                <td><?= htmlspecialchars($user["username"]) ?></td>
                <td><?= htmlspecialchars($user["role"]) ?></td>
                <td><?= htmlspecialchars($user["created_at"]) ?></td>

                <td class="actions">
                    <form method="POST" style="display:inline;">
                     <input type="hidden" name="id" value="<?= $user['id'] ?>">
                     <button type="submit" name="delete_user" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>


                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
