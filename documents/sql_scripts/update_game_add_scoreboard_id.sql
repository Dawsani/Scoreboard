ALTER TABLE game ADD scoreboard_id INT NOT NULL;
update game set scoreboard_id = (select scoreboard_id from scoreboard_game where game_id = id);
ALTER TABLE game ADD CONSTRAINT FOREIGN KEY (scoreboard_id) REFERENCES scoreboard(id);

DROP TABLE scoreboard_game;
