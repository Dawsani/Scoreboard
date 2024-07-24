<!DOCTYPE html>
<html>
<head>
<title>Record Game</title>
</head>
<body>

<h1>Record Game</h1>
<button onclick="window.location.href='index.php'">Home</button><br><br>

<form action="add_game.php" method="post">
<table>
<tr>
	<th></th>
	<th>Player Name</th>
	<th>Deck Name</th>
</tr>
	<div id='input-container'>
		<div id='input-group'>
			<tr>
			<th><label>Player 1:</label></th>
			<th><input type="text" id="player-1-name" name="players[]" required></th>
			<th><input type="text" id="player-1-deck" name="decks[]"</th> 
			</tr>
		</div>
	</div>
</table>
	<button onclick="addPlayerField()">Add Player</button><br><br>

	<label for "winner">Winner:</label>
	<input type="text" id="winner" name="winner"><br><br>

	<button type="Submit">Submit</button>
</form>

<script>

let inputContainer = document.getElementById('input-container');
let inputCount = 1;

function addPlayerField() {
	inputCount++;
	let newInputGroup = document.createElement('div');
	newInputGroup.className = 'input-group';
	newInputGroup.innerHTML = `
	<tr>	
	<th><label>Player ${inputCount}</label></th>
	<th><input type="text" id="input-${inputCount}-name" name="players[]" required></th>
	<th><input type="text" id="input-${inputCount}-deck" name="decks[]"></th>
	</tr>`;
	inputContainer.appendChild(newInputGroup);
}
</script>

</body>
</html>


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	include 'db_connect.php';	

	$playerInputs = $_POST['players'];
	$winner = $_POST['winner'];
	
	// Add each new player to the player database
	foreach ($playerInputs as $player) {
		$sql = "SELECT * FROM player WHERE name LIKE '$player';";
		$result = $conn->query($sql);
		
		// If the player does not exist, create it.
		if ($result->num_rows === 0) {
			$sql = "INSERT INTO player (name) VALUES ('$player')";
			$conn->query($sql);
		}
	}

	// Create the game record
	$sql = "INSERT INTO game (winner_id) VALUES (
			(SELECT id FROM player WHERE name LIKE '$winner')
		)";
	$conn->query($sql);

	// Add players to the game record
	foreach ($playerInputs as $player) {
		$sql = "INSERT INTO game_player (game_id, player_id) VALUES (
				(SELECT id FROM game ORDER BY id DESC LIMIT 1),
				(SELECT id FROM player WHERE name LIKE '$player')
			);";
		$conn->query($sql);
	}

	echo "Game recorded succesfully.<br>";
}
?>
