<?php include 'db_connect.php' ?>

<!DOCTYPE html>
<html>
<head>
	<title>Sign Up</title>
</head>

<body>
<form action="sign_up.php" method="post">
	<label for="email">Email:</label>
	<input type="email" id="email" name="email" required><br><br>
	<label for="confirm_email">Confirm Email:</label>
	<input type="email" id="confirm_email" name="confirm_email" required><br><br>
	<label for="username">Username:</label>
	<input type="text" id="username" name="username" minlength=3 maxlength=20 required ><br><br>
	<label for="password">Password:</label>
	<input type="password" id="password" name="password" minlength=6 maxlength=128 required><br><br>
	<input type="submit" value="Sign Up">
</form><br>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// User inputs
	$email = $_POST['email'];
	$confirm_email = $_POST['confirm_email'];
	$username = $_POST['username'];
	$password = $_POST['password'];

	$valid = 1;

	// Check if the emails were the same
	if ($email !== $confirm_email) {
		echo "Emails do not match.<br>";
		$valid = 0;
	}	

	// Chech email is not already used
	$sql = "SELECT email FROM user WHERE email LIKE '$email';";
	$result = $conn->query($sql);
	if ($result->num_rows !== 0) {
		echo "This email is already in use.<br>";
		$valid = 0;
	}

	// Check the username is not already in use
	$sql = "SELECT name FROM user WHERE name LIKE '$username';";
	$result = $conn->query($sql);
	if ($result->num_rows !== 0) {
		echo "This username is taken.<br>";
		$valid = 0;
	}	

	if ($valid === 1) {
		// Hash the password
		$passwordHash = password_hash($password, PASSWORD_BCRYPT);

		// Insert user into the database
		$sql = "INSERT INTO user (name, email, password_hash) VALUES ('$username', '$email', '$passwordHash');";
		if ($conn->query($sql) === TRUE) {
			echo "User registered succesfully.";
		}
			
		$_SESSION['username'] = $username;

		Header("Location: index.php");
	}

}
?>

<p>Already have an account? <a href="login.php">Log In</a>

</body>
</html>


