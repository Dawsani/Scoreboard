SELECT games_played.player_name, COALESCE(games_won, 0) AS games_won, games_played, COALESCE(games_won, 0) / games_played * 100 AS win_rate
FROM

(
SELECT player.name AS player_name, COUNT(*) AS games_played
FROM game_entry
JOIN scoreboard_game ON game_entry.game_id = scoreboard_game.game_id
JOIN player ON game_entry.player_id = player.id
JOIN deck ON deck.id = game_entry.deck_id
GROUP BY player.name
) AS games_played

LEFT JOIN

(
SELECT player.name AS player_name, COUNT(*) AS games_won
FROM scoreboard_game
JOIN game ON scoreboard_game.game_id = game.id
JOIN player ON game.winner_id = player.id
GROUP BY winner_id
) AS games_won
ON games_played.player_name = games_won.player_name;

-- Get games won by number of players in game
SELECT game_id, COUNT(*) AS num_players
FROM game
JOIN game_entry ON game.id = game_entry.game_id
GROUP BY game_id;

-- Get games played with 3 players
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
WHERE num_players = 4
GROUP BY player_name
ORDER BY player_name;
