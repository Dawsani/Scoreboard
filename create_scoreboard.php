<!DOCTYPE html>

<?php
include 'db_connect.php';
include 'helpers.php';
?>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$scoreboardName = $_POST['name'];
	$username = $_SESSION['username'];
	$sql = "INSERT INTO scoreboard (name, owner_id) VALUES ('$scoreboardName', " . usernameToId($conn, $username) . ");";
	$result = $conn->query($sql);

	Header("Location: index.php");
}

?>

<html>
<head>
<title>Create New Scoreboard</title>
</head>
<body>
<h1>Create New Scoreboard</h1>
<form action="create_scoreboard.php" method="POST">
<label for='name'>Scoreboard Name</label>
<input type='text' id='name' name='name'>
<button type='submit'>Submit</button>
</form>
</body>
</html>
