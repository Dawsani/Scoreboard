<?php include "db_connect.php" ?>

<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Log In</title>
</head>
<body>

<form action="login.php" method="post">
	<label for="email">Email:</label>
	<input type="email" id="email" name="email" required ><br><br>

	<label for="password">Password:</label>
	<input type="password" id="password" name="password" required><br><br>

	<input type="checkbox" id="rememberMe" name="rememberMe">
	<label for="rememberMe">Remember Me</label><br><br>

	<input type="submit" value="Login">
	
	<p>Don't have an account? <a href="sign_up.php">Sign Up</a></p>
	<a href="/reset_password.php">Forgot Password</a>

</form>

<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	$email = $_POST["email"];
	$password = $_POST["password"];

	$message = "";

	// Check if user exists
	$stmt = $conn->prepare("SELECT name, password_hash FROM user WHERE email = ?;");
	$stmt->bind_param('s', $email);
	$stmt->execute();
	$stmt->store_result();

	if ($stmt->num_rows === 0) {
		echo "Username not found.";
		exit();
	}
	
	// Check if password is correct
	$stmt->bind_result($name, $passwordHash);
	$stmt->fetch();
	$passwordHash = $passwordHash;
	if (password_verify($password, $passwordHash)) {

		// Check if they want to set a remember me cookie
		if (isset($_POST["rememberMe"])) {
			$rememberMe = $_POST["rememberMe"];
			if ($rememberMe == true) {
				$cookieName = "email";
				$cookieValue = $email;
				setcookie($cookieName, $cookieValue, time() + (60 * 60 * 24 * 30), "/");
			}	
		}
		
		$_SESSION['email'] = $email;
		header("location: index.php");
		exit();
	} else {
		echo "Email or password is incorrect.";
	}

}
?>

</body>
</html>
