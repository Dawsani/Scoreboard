<!DOCTYPE html>

<?php
include 'db_connect.php';
?>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$scoreboardName = $_POST['name'];
	$username = $_SESSION['username'];
	$sql = $conn->prepare("INSERT INTO scoreboard (name, owner_id) VALUES (?, ?);");
	$sql->bind_param('si', $scoreboardName, usernameToId($conn, $username));
	$sql->execute();

	// Add the user to scoreboard user to give them access
	$scoreboardId = $conn->insert_id;
	$userId = usernameToId($conn, $username);
	$sql = "INSERT INTO scoreboard_user (scoreboard_id, user_id) VALUES ($scoreboardId, $userId);";
	$result = $conn->query($sql);

	Header("Location: index.php");
}

?>

<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Create New Scoreboard</title>
</head>
<body>
<?php include("header.php"); ?>
<h1>Create New Scoreboard</h1>
<form action="create_scoreboard.php" method="POST">
<label for='name'>Scoreboard Name</label>
<input type='text' id='name' name='name'>
<button type='submit'>Submit</button>
</form>
</body>
</html>
