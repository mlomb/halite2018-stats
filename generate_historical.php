<?php
require_once("DB.php");

DB::get()->query("SELECT games_users.game_id, user_id, mu, version, time_played FROM games_users LEFT JOIN games ON games.game_id=games_users.game_id ORDER BY games_users.game_id ASC");
$results = DB::get()->results();

foreach($results as $row) {
	echo $row->game_id."\n";
	DB::get()->query("INSERT INTO historical (date, hour, user_id, mu_sum, games, version) VALUES (?, ?, ?, ?, 1, ?) ON DUPLICATE KEY UPDATE mu_sum=mu_sum+VALUES(mu_sum), games=games+VALUES(games), version=VALUES(version)", array(date("Y-m-d", strtotime($row->time_played)), date("H", strtotime($row->time_played)), $row->user_id, $row->mu, $row->version));
}
?>