SELECT DISTINCT player.id as player_id, player.name as player_name
FROM scoreboard
JOIN game ON scoreboard.id = scoreboard_id
JOIN game_entry ON game.id = game_id
JOIN player ON player_id = player.id
WHERE scoreboard.id = 1;

CREATE TABLE player_new (
	id INT AUTO_INCREMENT,
	id

INSERT INTO player_new (
