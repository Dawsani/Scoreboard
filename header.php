<header>
<a href="logout.php">Log Out</a>
<a href="index.php">Home</a>
<?php
	if (isset($_GET['scoreboardId'])) {

		$scoreboardId = $_GET['scoreboardId'];
		echo "<a href='scoreboard.php?scoreboardId=$scoreboardId'>Scoreboard</a>";
	}
?>
</header>

