<!DOCTYPE html>

<?php
include "db_connect.php";
?>

<?php

$scoreboardId = $_GET['scoreboardId'];
$scoreboardName = scoreboardIdToName($conn, $scoreboardId);

?>

<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Invite User</title>
</head>
<body>
<?php include("header.php"); ?>
<h1>Invite User to <?php echo $scoreboardName ?></h1>

<form method="POST" action="invite_user.php?scoreboardId=<?php echo $scoreboardId ?>">
<label for="username">Username</label>
<input type="text" id="username" name="username">
<button type="submit">Submit</button>
</form>
</body>
</html>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$username = $_POST['username'];
	$sql = "SELECT id FROM user WHERE name LIKE '$username';";
	$result = $conn->query($sql);

	if ($result->num_rows === 0) {
		echo "No user found by the username \"$username\".<br>";
	}

	$userId = usernameToId($conn, $username);

	$sql = "SELECT * FROM scoreboard_user WHERE user_id = $userId AND scoreboard_id = $scoreboardId;";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		echo "User \"$username\" has already been invited to this scoreboard.<br>";
		exit();
	}

	$sql = "INSERT INTO scoreboard_user (scoreboard_id, user_id) VALUES 
		($scoreboardId, $userId);";
	$result = $conn->query($sql);

	if ($result) {
		echo "User \"$username\" invited succesfully.<br>";
	} else {
		echo "Error inviting user \"$username\" to scoreboard.<br>";
	}
}
?>
