<html>
<form action="index.php">
	<label for="username">Username:</label>
	<input type="text" id="username" name="username"><br><br>
	<label for="password">Password:</label>
	<input type="password" id="password" name="password"><br><br>
</form>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Connect to the database
	$conn = new mysqli("localhost", 'root', 'parade');

	// User inputs
	$email = $_POST['email'];
	$username = $_POST['username'];
	$password = $_POST['password'];

	// Hash the password
	$passwordHash = password_hash($password, PASSWORD_BCRYPT);

	// Insert user into the database
	$statement = 'INSERT INTO user (username, email, password_hash) VALUES ($username, $email, $passwordHash);'

	if ($conn->query($statement) === TRUE) {
		echo "User registered succesfully.";
	} else {
		echo "Error registering user.";
	}

	$conn->close();
}
?>


