<?php
require 'config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $role = $_POST["role"];

    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    if ($stmt->execute([$username, $password, $role])) {
        echo "User registered successfully. <a href='login.php'>Login here</a>";
    } else {
        echo "Error registering user.";
    }
}
?>
<form method="post">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <select name="role">
        <option value="admin">Admin</option>
        <option value="staff">Staff</option>
    </select>
    <button type="submit">Register</button>
</form>
