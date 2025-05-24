INSERT INTO user (name, email, password_hash) VALUES (
	"Dawsani",
	"dawson.dwm@gmail.com",
	"asjdf;alskjdf;alskjdf;asdhfgwj"
);

INSERT INTO player (name) VALUES ("dawson");

INSERT INTO game (winner_id) VALUES (
	(SELECT id FROM player WHERE name LIKE "dawson")
);

INSERT INTO game_player (game_id, player_id) VALUES (
	(SELECT id FROM game ORDER BY id DESC LIMIT 1),
	(SELECT id FROM player WHERE name LIKE "dawson")
);
