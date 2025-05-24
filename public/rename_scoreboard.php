<!DOCTYPE html>

<?php include "db_connect.php"; ?>

<?php

$scoreboardId = $_GET["scoreboardId"];
$scoreboardName = scoreboardIdToName($conn, $scoreboardId);

?>

<html>
<body>
<?php include "header.php"; ?>

<h1>Rename Scoreboard</h1>

<form action="rename_scoreboard.php?scoreboardId=<?php echo $scoreboardId; ?>" method="POST">
<p>Current Name: <?php echo $scoreboardName ?></p>
<label for="name">New Name: </label>
<input type="text" name="name" id="name" required>
<button type="submit">Submit</button>
</form>
</body>
</html>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$newName = $_POST["name"];
	$stmt = $conn->prepare("UPDATE scoreboard SET name = ? WHERE id = ?;");
	$stmt->bind_param('si', $newName, $scoreboardId);
	$stmt->execute();

	header("location: scoreboard.php?scoreboardId=$scoreboardId");
	
	exit();
}

?>
