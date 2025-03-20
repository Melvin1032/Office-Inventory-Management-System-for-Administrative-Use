<?php
session_start();
require 'config/config.php';

$error = ''; // Initialize the error variable

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
        $_SESSION["username"] = $user["username"];  // Store the username in the session

        // Redirect based on role
        if ($user["role"] === "admin") {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: user/staff_dashboard.php");
        }
        exit();
    } else {
        $error = "Invalid username or password."; // Set the error message
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log-in</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<video autoplay muted loop id="bg-video">
        <source src="assets/warehouse.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

<div class="container" id="container">
	<div class="form-container sign-up-container">
        <form method="post">
			<h1>Create Account</h1>

			<span>or use your email for registration</span>
			<input type="text" placeholder="Name" />
			<input type="email" placeholder="Email" />
			<input type="password" placeholder="Password" />
			<button>Sign Up</button>
		</form>
	</div>
    <div class="form-container sign-in-container">
    <form method="post">
        <h1>Sign in</h1>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <?php if ($error): ?>
            <p class="error-message"><?php echo $error; ?></p> <!-- Display error if any -->
        <?php endif; ?>
        <a href="#">Forgot your password?</a>
        <button>Sign In</button>
    </form>
</div>
	<div class="overlay-container">
		<div class="overlay">
			<div class="overlay-panel overlay-left">
				<h1>Welcome Back!</h1>
				<p>To keep connected with us please login with your personal info</p>
				<button class="ghost" id="signIn">Sign In</button>
			</div>
			<div class="overlay-panel overlay-right">
            <img src="assets/logo_white.png" alt="">
				<p>Don't have an account? Click the Sign Up button.</p>
				<button class="ghost" id="signUp">Sign Up</button>
			</div>
		</div>
	</div>
</div>
</body>

<script>
    const signUpButton = document.getElementById('signUp');
const signInButton = document.getElementById('signIn');
const container = document.getElementById('container');

signUpButton.addEventListener('click', () => {
	container.classList.add("right-panel-active");
});

signInButton.addEventListener('click', () => {
	container.classList.remove("right-panel-active");
});
</script>

</html>