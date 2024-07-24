<!DOCTYPE html>
<html>
<head>
<title>Record Game</title>
</head>
<body>

<h1>Record Game</h1>
<button onclick="window.location.href='index.php'">Home</button><br><br>

<form action="add_game.php" method="post">
<table id='input-container'>
<tr>
	<th></th>
	<th>Player Name</th>
	<th>Deck Name</th>
</tr>
<tr id='input-group'>
	<th><label>Player 1:</label></th>
	<th><input type="text" id="player-1-name" name="players[]" required></th>
	<th><input type="text" id="player-1-deck" name="decks[]" required</th> 
</tr>
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
	let newInputGroup = document.createElement('tr');
	newInputGroup.className = 'input-group';
	newInputGroup.innerHTML = `
	<th><label>Player ${inputCount}</label></th>
	<th><input type="text" id="input-${inputCount}-name" name="players[]" required></th>
	<th><input type="text" id="input-${inputCount}-deck" name="decks[]" required></th>`;
	inputContainer.appendChild(newInputGroup);
}
</script>

</body>
</html>


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	include 'db_connect.php';	

	$playerInputs = $_POST['players'];
	$deckInputs = $_POST['decks'];
	$winner = $_POST['winner'];
	
	// Add each new player to the player database
	foreach ($playerInputs as $player) {
		$sql = "SELECT * FROM player WHERE name LIKE '$player';";
		$result = $conn->query($sql);
		
		// If the player does not exist, create it.
		if ($result->num_rows === 0) {
			$sql = "INSERT INTO player (name) VALUES ('$player');";
			$conn->query($sql);
		}
	}

	// Add each noew deck to the deck table
	foreach ($deckInputs as $deck) {
		$sql = "SELECT * FROM deck WHERE name LIKE '$deck';";
		$result = $conn->query($sql);

		// If the deck does not exist, create it
		if ($result->num_rows === 0) {
			$sql = "INSERT INTO deck (name) VALUES ('$deck');";
			$conn->query($sql);
		}
	}

	// Create the game record
	$sql = "INSERT INTO game (winner_id) VALUES (
			(SELECT id FROM player WHERE name LIKE '$winner')
		)";
	$conn->query($sql);

	// Add the records to the game
	for ($i = 0; $i < count($playerInputs); $i++) {
		$player = $playerInputs[$i];
		$deck = $deckInputs[$i];

		$sql = "INSERT INTO game_entry (game_id, player_id, deck_id) VALUES (
				(SELECT id FROM game ORDER BY id DESC LIMIT 1),
				(SELECT id FROM player WHERE name LIKE '$player'),
				(SELECT id FROM deck WHERE name LIKE '$deck')
			);";
		$conn->query($sql);
	}

	echo "Game recorded succesfully.<br>";
}
?>
