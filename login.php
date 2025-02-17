<?php
session_start();
require 'config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["role"] = $user["role"];

        // Redirect based on role
        if ($user["role"] === "admin") {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: user/staff_dashboard.php");
        }
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<form method="post">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>

<?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>

<a href="register.php">
    <button type="button">Register</button>
</a>
