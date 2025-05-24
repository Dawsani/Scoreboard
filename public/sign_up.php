<?php include 'db_connect.php' ?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sign Up</title>
</head>

<body>

<?php

$email="";
$email2="";
$username="";
$password="";
$password2="";

if (isset($_POST['email'])) {
	$email=$_POST['email'];
}
if (isset($_POST['email2'])) {
	$email2 = $_POST['email2'];
}
if (isset($_POST['username'])) {
	$username = $_POST['username'];
}
if (isset($_POST['password'])) {
	$password = $_POST['password'];
}
if (isset($_POST['password2'])) {
	$password2 = $_POST['password2'];
}


?>
<form action="sign_up.php" method="post">
	<label for="email">Email:</label>
	<input type="email" id="email" name="email" required value=<?php echo $email ?>><br><br>
	<label for="email2">Confirm Email:</label>
	<input type="email" id="email2" name="email2" required value=<?php echo $email2 ?>><br><br>
	<label for="username">Username:</label>
	<input type="text" id="username" name="username" minlength=3 maxlength=20 required value=<?php echo $username ?> ><br><br>
	<label for="password">Password:</label>
	<input type="password" id="password" name="password" minlength=6 maxlength=128 required value=<?php echo $password ?>><br><br>
	<label for="password2">Confirm Password:</label>
	<input type="password" id="password2" name="password2" required value=<?php echo $password2 ?>><br><br>
	<input type="submit" value="Sign Up">
</form><br>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$valid = 1;

	// Check if the emails were the same
	if ($email !== $email2) {
		echo "Emails do not match.<br>";
		exit();
	}	

	// Chech email is not already used
	$sql = "SELECT email FROM user WHERE email LIKE '$email';";
	$result = $conn->query($sql);
	if ($result->num_rows !== 0) {
		echo "This email is already in use.<br>";
		exit();
	}

	// Check the username is not already in use
	$sql = "SELECT name FROM user WHERE name LIKE '$username';";
	$result = $conn->query($sql);
	if ($result->num_rows !== 0) {
		echo "This username is taken.<br>";
		exit();
	}	

	// Check passwords are the same
	if ($password !== $password2) {
		echo "Passwords do not match.<br>";
		exit();
	}

	// Hash the password
	$passwordHash = password_hash($password, PASSWORD_BCRYPT);

	// Insert user into the database
	$sql = $conn->prepare("INSERT INTO user (name, email, password_hash) VALUES (?, ?, ?);");
	$sql->bind_param('sss', $username, $email, $passwordHash);
	$sql->execute();

	$_SESSION['username'] = $username;

	Header("Location: index.php");

}
?>

<p>Already have an account? <a href="login.php">Log In</a>

</body>
</html>


