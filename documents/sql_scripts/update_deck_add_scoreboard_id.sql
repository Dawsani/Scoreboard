-- Add the scoreboard_id col TABLE deck ADD COLUMN scoreboard_id INT NOT NULL;
ALTER TABLE deck ADD COLUMN scoreboard_id INT;

-- Insert a deck for each player in each scoreboard
INSERT INTO deck (name, scoreboard_id)
SELECT DISTINCT deck.name as deck_name, scoreboard.id AS scoreboard_id
FROM deck
JOIN game_entry ON deck.id = deck_id
JOIN game ON game.id = game_id	
JOIN scoreboard ON scoreboard.id = game.scoreboard_id;

-- Change all referneces to decks to their new ids
UPDATE game_entry
SET deck_id = 
(
	SELECT id
	FROM deck
	WHERE deck.scoreboard_id = (SELECT scoreboard_id FROM game WHERE id = game_id)
	AND name = (SELECT name FROM deck WHERE id = deck_id)
);

-- Remove all entries in deck without a scoreboard_id
DELETE FROM deck
WHERE scoreboard_id IS NULL;

-- Make the scoreboard_id not null
ALTER TABLE deck
MODIFY scoreboard_id INT NOT NULL;

-- Assign the foreign KEY
ALTER TABLE deck ADD CONSTRAINT FOREIGN KEY (scoreboard_id) REFERENCES scoreboard(id);
