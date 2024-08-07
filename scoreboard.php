<?php include 'db_connect.php'; ?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
table, th, td {
  border:1px solid black;
}
</style>

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

<?php
// Get each number of players listed in games
$sql = "SELECT num_players
	FROM (
	SELECT game_id, COUNT(*) AS num_players
	        FROM game
	        JOIN game_entry ON game.id = game_entry.game_id
	        GROUP BY game_id
	) as game_num_players
	GROUP BY num_players
	ORDER BY num_players;";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
	echo "<p>No data exists</p>";
	exit();
}

$gameTypes = array();
while($row = $result->fetch_assoc()) {
	$numPlayers = $row['num_players'];
	array_push($gameTypes, $numPlayers);
}

foreach ($gameTypes as $numPlayers) {

	echo "<h2>$numPlayers Player Win Rates</h2>";
	$sql = "SET @numPlayers := $numPlayers;";
	$conn->query($sql);

	// Show each players games played by number of players in the game
	$sql = "
	SELECT games_played.player_name, COALESCE(games_won, 0) AS games_won, games_played, COALESCE(games_won, 0) / games_played * 100 AS win_rate
	FROM
	(
	SELECT player.name AS player_name, COUNT(*) AS games_played
	FROM game_entry
	JOIN (
		SELECT game_id, COUNT(*) AS num_players
		FROM game
		JOIN game_entry ON game.id = game_entry.game_id
		GROUP BY game_id
	) AS game_players
	ON game_entry.game_id = game_players.game_id
	JOIN player ON player.id = game_entry.player_id
	WHERE num_players = @numPlayers
	GROUP BY player_name
	) AS games_played

	LEFT JOIN

	(
	SELECT player.name AS player_name, COUNT(*) AS games_won
	FROM game
	JOIN (
		SELECT game_id, COUNT(*) AS num_players
		FROM game
		JOIN game_entry ON game.id = game_entry.game_id
		GROUP BY game_id
	) AS game_players
	ON game.id = game_players.game_id
	JOIN player ON player.id = game.winner_id
	WHERE num_players = @numPlayers
	GROUP BY player_name

	) AS games_won
	ON games_played.player_name = games_won.player_name;";

	$result = $conn->query($sql);

	if ($result->num_rows == 0) {
		echo "No existing data.<br>";
		exit();
	}

	echo "<table>
		<tr>
		<th>Player</th>	<th>Win Rate</th>
		</tr>";	

	while ($row = $result->fetch_assoc()) {
		$playerName = $row['player_name'];
		$gamesPlayed = $row['games_played'];
		$gamesWon = $row['games_won'];
		$winRate = $row['win_rate'];

		echo "<tr>
			<td>$playerName</td><td>$gamesWon/$gamesPlayed $winRate%</td>
			</tr>";
	}

	echo "</table>";
}
?>
</body>
</html>


