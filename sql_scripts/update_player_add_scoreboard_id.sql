-- Add the scoreboard_id col TABLE player ADD COLUMN scoreboard_id INT NOT NULL;
ALTER TABLE player ADD COLUMN scoreboard_id INT;

-- Insert a player for each player in each scoreboard
INSERT INTO player (name, scoreboard_id)
SELECT DISTINCT player.name as player_name, scoreboard.id AS scoreboard_id
FROM player
JOIN game_entry ON player.id = player_id
JOIN game ON game.id = game_id	
JOIN scoreboard ON scoreboard.id = game.scoreboard_id;

-- Change all referneces to players to their new ids
UPDATE game
SET winner_id = 
(
	SELECT id
	FROM player
	WHERE scoreboard_id = game.scoreboard_id
	AND name = (SELECT name FROM player WHERE id = winner_id)
);

UPDATE game_entry
SET player_id = 
(
	SELECT id
	FROM player
	WHERE player.scoreboard_id = (SELECT scoreboard_id FROM game WHERE id = game_id)
	AND name = (SELECT name FROM player WHERE id = player_id)
);

-- Remove all entries in player without a scoreboard_id
DELETE FROM player
WHERE scoreboard_id IS NULL;

-- Make the scoreboard_id not null
ALTER TABLE player
MODIFY scoreboard_id INT NOT NULL;

-- Assign the foreign KEY
ALTER TABLE player ADD CONSTRAINT FOREIGN KEY (scoreboard_id) REFERENCES scoreboard(id);
