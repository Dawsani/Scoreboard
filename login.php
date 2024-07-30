<?php include "db_connect.php" ?>

<html>
<head>
<title>Log In</title>
</head>
<body>

<form action="login.php" method="post">
	<label for="username">Username:</label>
	<input type="text" id="username" name="username" required ><br><br>
	<label for="password">Password:</label>
	<input type="password" id="password" name="password" required><br><br>
	<input type="submit" value="Login">
	
	<p>Don't have an account? <a href="sign_up.php">Sign Up</a></p>

</form>

<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	$username = $_POST["username"];
	$password = $_POST["password"];

	$message = "";

	// Check if user exists
	$sql = "SELECT name, password_hash FROM user WHERE name LIKE '$username';";
	$result = $conn->query($sql);
	if ($result->num_rows === 0) {
		$message = "Username not found.";
	}

	// Check if password is correct
	$passwordHash = $result->fetch_assoc()['password_hash'];
	if (password_verify($password, $passwordHash)) {
		$_SESSION['username'] = $username;
		header("Location: index.php");
		exit();
	} else {
		$message = "Username or password is incorrect.";
	}


echo "<p><?php echo htmlspecialchars($message); ?></p>";

}
?>

</body>
</html>
