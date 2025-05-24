<?php include 'db_connect.php'; ?>

<!DOCTYPE html>
<html>
<head>

<style>
table {
  border:1px solid black;
  border-collapse: collapse;
  padding: 10px
}

td, th {
  border: 1px solid #dddddd;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
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

<?php
	$username = $_SESSION['username'];
	$userId = usernameToId($conn, $username);

	$sql = "SELECT owner_id FROM scoreboard WHERE scoreboard.id = $scoreboardId;";
	$result = $conn->query($sql);

	$ownerId = $result->fetch_assoc()['owner_id'];

	if ($userId == $ownerId) {
		echo "<a href='rename_scoreboard.php?scoreboardId=$scoreboardId'>Rename Scoreboard</a>";
	}
?>

<h2>Recent games</h2>
<?php
// Get the most recent 10 games
$sql = "SELECT created_at, name AS winner_name, winner_id, game.id AS game_id, note
	FROM game JOIN player ON game.winner_id = player.id
	WHERE game.scoreboard_id = $scoreboardId
	ORDER BY game.id DESC
	LIMIT 5;";
$recentGames = $conn->query($sql);

if ($recentGames->num_rows > 0) {
	echo "<a href='all_games.php?scoreboardId=$scoreboardId'>See All Games</a>";
}

if ($recentGames->num_rows > 0) {
	echo "<table>
		<tr>
		<th style='min-width: 75px'>Date</th>
		<th style='min-width: 100px'>Winner</th>
		<th style='min-width: 200px'>Other Players</th>
		<th style='min-width: 400px'>Note</th>
		<th>Edit Game</th>
		</tr>";

	while ($row = $recentGames->fetch_assoc()) {
		$createdAt = $row['created_at'];
		$datetime = convertDatetime($createdAt);
		$winnerId = $row['winner_id'];
		$winnerName = $row['winner_name'];
		$gameId = $row['game_id'];
		$note = $row['note'];

		$sql = "SELECT deck.name as winner_deck_name 
			FROM game 
			JOIN game_entry ON game.id = game_id
			JOIN deck ON deck.id = deck_id
			WHERE game_id = $gameId AND player_id = $winnerId;";

		$winnerDeckNameResult = $conn->query($sql);
		$winnerDeckName = $winnerDeckNameResult->fetch_assoc()['winner_deck_name'];

		// Get a list of every player in the game, except the winner
		$sql = "SELECT player.name as player_name, deck.name AS deck_name
			FROM game_entry join player on game_entry.player_id = player.id
			JOIN deck ON game_entry.deck_id = deck.id
			WHERE game_entry.game_id = $gameId AND player.name NOT LIKE '$winnerName'
			ORDER BY player.name;";
		
		$playerList = $conn->query($sql);
			
		echo "
		<tr>
			<td>$datetime</td>
			<td>$winnerName using $winnerDeckName</td>
			<td>";

			$numPlayers = $playerList->num_rows;
			$playerCount = 1;
			while ($playerRow = $playerList->fetch_assoc()) {
				$player = $playerRow['player_name'];
				$deckName = $playerRow['deck_name'];
				echo "$player using $deckName";
				if ($playerCount < $numPlayers) {
					echo ",<br>";
				}
				$playerCount += 1;
			}	
			echo "</td>
			<td>$note</td>
			<td><a href='edit_game.php?gameId=$gameId'>Edit Game</a></td>
		</tr>";

	}
} else {
	echo "No games recorded.";
}
?>

</table>

<h2>Player Winrates</h2>

<table>

<?php
// Get each number of players listed in games
$sql = "SELECT num_players
	FROM (
	SELECT game_id, COUNT(*) AS num_players
	        FROM game
		JOIN game_entry ON game.id = game_entry.game_id
		WHERE game.scoreboard_id = $scoreboardId
		GROUP BY game_id
	) as game_num_players
	GROUP BY num_players
	ORDER BY num_players;";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
	echo "<p>No data exists</p>";
}
else {
	$gameTypes = array();
	while($row = $result->fetch_assoc()) {
		$numPlayers = $row['num_players'];
		array_push($gameTypes, $numPlayers);
	}

	// Get every player in the scoreboard
	$sql = "SELECT DISTINCT player.id AS player_id, player.name
		FROM game
		JOIN game_entry ON game.id = game_id
		JOIN player ON player_id = player.id
		WHERE game.scoreboard_id = $scoreboardId
		ORDER BY player.name;";
	$result = $conn->query($sql);

	$players = array();
	while ($row = $result->fetch_assoc()) {
		$playerId = $row['player_id'];
		array_push($players, $playerId);
	}

	echo "<tr>
		<th>Player Name</th>";
	foreach ($gameTypes as $numPlayers) {
		echo "<th>$numPlayers Player Games</th>";
	}
	// Heatian value
	echo "<th>Heathian Value (Combined)</th>";
	echo "</tr>";
	foreach ($players as $playerId) {
		echo "<tr>";
		$playerName = playerIdToName($conn, $playerId);
		echo "<td>$playerName</td>";
		foreach ($gameTypes as $numPlayers) {
			$winRate = getPlayerWinRateWithPlayerCount($conn, $playerId, $numPlayers);
			echo "<td>$winRate</td>";
		}
		// Heathian value
		$winRate = getPlayerWinRateWithPlayerCount($conn, $playerId, "0 OR true");
		echo "<td>$winRate</td>";
		echo "</tr>";
	}
}
?>

</table>

<h2>Deck Win Rates</h2>

<table>
<?php
// Get every deck used in the scoreboard
$sql = "SELECT DISTINCT deck.id AS deck_id
	FROM deck
	JOIN game_entry ON deck.id = deck_id
	JOIN game ON game.id = game_id
	JOIN scoreboard on scoreboard.id = deck.scoreboard_id
	WHERE deck.scoreboard_id = $scoreboardId;";
$result = $conn->query($sql);
$decks = array();

if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()) {
		$deck = $row['deck_id'];
		array_push($decks, $deck);
	}

	echo "<tr>
		<th>Deck Name</th>";
	foreach ($gameTypes as $numPlayers) {
		echo "<th>$numPlayers Player Games</th>";
	}
	// Heathian value
	echo "<th>Heathian Value (Combined)</th>";
	echo "</tr>";
	foreach ($decks as $deckId) {
		echo "<tr>";
		$deckName = deckIdToName($conn, $deckId);
		echo "<td>$deckName</td>";
		foreach ($gameTypes as $numPlayers) {
			$winRate = getDeckWinRateWithPlayerCount($conn, $deckId, $numPlayers);
			echo "<td>$winRate</td>";
		}
		// heathian value
		$winRate = getDeckWinRateWithPlayerCount($conn, $deckId, "0 OR TRUE");
		echo "<td>$winRate</td>";
		echo "</tr>";
	}



}
else {
	echo "<p>No deck data exists.</p>";
}
?>
</table>

</body>
</html>


