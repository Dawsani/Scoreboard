SELECT COALESCE(games_won, 0) AS games_won, games_played, COALESCE(games_won, 0) / games_played * 100 AS win_rate
FROM
(
SELECT COUNT(*) AS games_played, deck_id
FROM game_entry
JOIN (
	SELECT game_id, COUNT(*) AS num_players
	FROM game
	JOIN game_entry ON game.id = game_entry.game_id
	WHERE game.scoreboard_id = 1
	GROUP BY game_id
) AS game_players
ON game_entry.game_id = game_players.game_id
JOIN deck ON deck.id = game_entry.deck_id
WHERE num_players = 3
GROUP BY deck_id
) AS games_played

LEFT JOIN

(
SELECT COUNT(*) AS games_won, deck_id
FROM game
JOIN game_entry ON game.id = game_id
JOIN (
	SELECT game_id, COUNT(*) AS num_players
	FROM game
	JOIN game_entry ON game.id = game_entry.game_id
	WHERE game.scoreboard_id = 1
	GROUP BY game_id
) AS game_players
ON game.id = game_players.game_id
WHERE num_players = 3
GROUP BY deck_id
) AS games_won
ON games_played.deck_id = games_won.deck_id
WHERE games_played.deck_id = 14;

SELECT  gamed.created_at, deck_id, deck.name AS deck_name, game_entry.game_id, num_players
FROM game_entry
JOIN (
        SELECT game_id, COUNT(*) AS num_players
        FROM game
        JOIN game_entry ON game.id = game_entry.game_id
        WHERE game.scoreboard_id = 1
        GROUP BY game_id
) AS game_players
ON game_entry.game_id = game_players.game_id
JOIN deck ON deck.id = game_entry.deck_id
ORDER BY game_id;
