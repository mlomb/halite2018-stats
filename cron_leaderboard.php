<?php
$f = fopen('halite2018_cron_leaderboard_lock', 'w') or die ('Cannot create lock file');
if (flock($f, LOCK_EX | LOCK_NB)) {

require_once("DB.php");

$leaderboard = json_decode(file_get_contents("https://api.2018.halite.io/v1/api/leaderboard?offset=0&limit=999999"), true);

/*
foreach($leaderboard as $player) {
	DB::get()->query("INSERT INTO players (user_id, username, profile_image_key, lang, country, level, rank, mu, sigma, rating, bot_version) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE username=VALUES(username), profile_image_key=VALUES(profile_image_key), lang=VALUES(lang), country=VALUES(country), level=VALUES(level), rank=VALUES(rank), mu=VALUES(mu), sigma=VALUES(sigma), rating=VALUES(rating), bot_version=VALUES(bot_version)", array(
		$player["user_id"],
		$player["team_name"] == null ? $player["username"] : $player["team_name"],
		$player["profile_image_key"],
		$player["language"],
		$player["country"],
		$player["level"],
		$player["rank"],
		$player["mu"],
		$player["sigma"],
		$player["score"],
		$player["version_number"]
	));
}
*/

$values = "";
$params = array();

foreach($leaderboard as $player) {
	$values .= '(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?),';
	$params = array_merge($params, array(
		$player["user_id"],
		$player["team_name"] == null ? $player["username"] : $player["team_name"],
		$player["profile_image_key"],
		$player["language"],
		$player["country"],
		$player["level"],
		$player["rank"],
		$player["mu"],
		$player["sigma"],
		$player["score"],
		$player["num_games"],
		$player["version_number"]
	));
}

$values = rtrim($values, ',');

DB::get()->query("INSERT INTO players (user_id, username, profile_image_key, lang, country, level, rank, mu, sigma, rating, num_games, bot_version) VALUES ".$values." ON DUPLICATE KEY UPDATE username=VALUES(username), profile_image_key=VALUES(profile_image_key), lang=VALUES(lang), country=VALUES(country), level=VALUES(level), rank=VALUES(rank), mu=VALUES(mu), sigma=VALUES(sigma), rating=VALUES(rating), num_games=VALUES(num_games), bot_version=VALUES(bot_version)", $params);


$query = "
START TRANSACTION;

UPDATE players SET rank_mu=0;

SET @r_mu=0;
UPDATE players SET rank_mu= @r_mu:= (@r_mu+1) ORDER BY mu DESC;

COMMIT;
";
DB::get()->query($query);


}
?>