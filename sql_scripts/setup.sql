SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS user;

CREATE TABLE user (
	id INT AUTO_INCREMENT,
	name VARCHAR(64) NOT NULL UNIQUE,
	email VARCHAR(128) NOT NULL UNIQUE,
	password_hash VARCHAR(256) NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (id)
);

DROP TABLE IF EXISTS player;

CREATE TABLE player (
	id INT AUTO_INCREMENT,
	name VARCHAR(64) NOT NULL,
	PRIMARY KEY (id)
);

DROP TABLE IF EXISTS game;

CREATE TABLE game (
	id INT AUTO_INCREMENT,
	scoreboard_id INT,
	winner_id INT,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (id),
	FOREIGN KEY (scoreboard_id) REFERENCES scoreboard(id),
	FOREIGN KEY (winner_id) REFERENCES player(id)
);

DROP TABLE IF EXISTS deck;

CREATE TABLE deck (
	id INT AUTO_INCREMENT,
	name VARCHAR(64) NOT NULL,
	PRIMARY KEY (id)
);

DROP TABLE IF EXISTS game_entry;

CREATE TABLE game_entry (
	game_id INT NOT NULL,
	player_id INT NOT NULL,
	deck_id INT NOT NULL,
	FOREIGN KEY (game_id) REFERENCES game(id),
	FOREIGN KEY (player_id) REFERENCES player(id),
	FOREIGN KEY (deck_id) REFERENCES deck(id)
);

DROP TABLE IF EXISTS scoreboard;

CREATE TABLE scoreboard (
	id INT AUTO_INCREMENT,
	name VARCHAR(128) NOT NULL,
	owner_id INT NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (owner_id) REFERENCES user(id)
);

DROP TABLE IF EXISTS scoreboard_user;

CREATE TABLE scoreboard_user (
	scoreboard_id INT NOT NULL,
	user_id INT NOT NULL,
	PRIMARY KEY (scoreboard_id, user_id),
	FOREIGN KEY (scoreboard_id) REFERENCES scoreboard(id),
	FOREIGN KEY (user_id) REFERENCES user(id)
);

DROP TABLE IF EXISTS scoreboard_game;

CREATE TABLE scoreboard_game (
	scoreboard_id INT NOT NULL,
	game_id INT NOT NULL,
	FOREIGN KEY (scoreboard_id) REFERENCES scoreboard(id),
	FOREIGN KEY (game_id) REFERENCES game(id)
);
