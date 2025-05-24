<?php include 'db_connect.php'; ?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>
<?php
	$scoreboardId = $_GET['scoreboardId'];
	$sql = "SELECT name FROM scoreboard WHERE id = $scoreboardId;";
	$result = $conn->query($sql);
	$scoreboardName = $result->fetch_assoc()['name'];
	echo $scoreboardName;
?>
</title>
</head>

<body>
<?php include("header.php"); ?>
<h1><?php echo $scoreboardName ?></h1>

<a href="add_game.php?scoreboardId=<?php echo $scoreboardId ?>">Record Game</a>
<a href="invite_user.php?scoreboardId=<?php echo $scoreboardId ?>">Invite User</a>

<h2>Recent games</h2>

<?php
// Get the most recent 10 games
$sql = "SELECT created_at, name AS winner_name, game.id AS game_id
	FROM game JOIN player ON game.winner_id = player.id
	WHERE game.id IN (
		SELECT game_id
		FROM scoreboard_game
		WHERE scoreboard_id = $scoreboardId)
	ORDER BY game.id DESC
	LIMIT 10;";
$recentGames = $conn->query($sql);

if ($recentGames->num_rows > 0) {
	while ($row = $recentGames->fetch_assoc()) {
		$createdAt = $row['created_at'];
		$winnerName = $row['winner_name'];
		$gameId = $row['game_id'];

		// Get a list of every player in the game, except the winner
		$sql = "SELECT name
			FROM game_entry join player on game_entry.player_id = player.id
			WHERE game_entry.game_id = $gameId AND name NOT LIKE '$winnerName'
			ORDER BY name;";
		
		$playerList = $conn->query($sql);
		
		echo $row['created_at'] . " - " . $winnerName . " won against ";
		
		$numPlayers = $playerList->num_rows;
		$playerCounter = 0;

		if ($numPlayers > 0) {
			while ($playerRow = $playerList->fetch_assoc()) {
				$playerName = $playerRow['name'];
				echo $playerName;

				if ($playerCounter === $numPlayers - 1) {
					echo ".";
				} else if ($playerCounter === $numPlayers - 2) {
					echo " and ";
				} else { 
					echo ", ";
				}
				$playerCounter += 1;
			}
		} else {
			echo "nobody.";
		}
		
		echo "<br>";
	}
} else {
	echo "No games recorded.";
}
?>

<h2>Games Won</h2>

<?php
// Show each players games played by number of players in the game
$sql = "
	SELECT player.name AS player_name, num_players, COUNT(*) AS games_played
	FROM (
	        SELECT game_id, COUNT(*) AS num_players
	        FROM game
	        JOIN game_entry ON game.id = game_entry.game_id
	        GROUP BY game_id
	) AS game_num_players
	JOIN game_entry ON game_num_players.game_id = game_entry.game_id
	JOIN player ON player.id = game_entry.player_id
	GROUP BY player_name, num_players
	ORDER BY player_name, num_players;";

$result = $conn->query($sql);
if ($result->num_rows == 0) {
	echo "No data exists.<br>";
	exit();
}


while ($row = $result->fetch_assoc()) {
	$players = array();

	$playerName = $row['player_name'];
	$numPlayers = $row['num_players'];
	$gamesPlayed = $row['gamesPlayed'];

	// If this is a new player, make a new column
	if (!in_array($playerName, $players)) {
		array_push($players, array(gamesPlayed));
		
	}
	// otherwis
	else {



?>
</body>
</html>


