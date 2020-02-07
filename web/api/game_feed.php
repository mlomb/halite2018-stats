<?php
include_once("../../common.php");

startAPIRequest();

$users = arrayFromParameter(isset($_GET["users"]) ? $_GET["users"] : "");
$sizes = arrayFromParameter(isset($_GET["sizes"]) ? $_GET["sizes"] : "");
$num_players = arrayFromParameter(isset($_GET["num_players"]) ? $_GET["num_players"] : "");
$from_game_id = 0;
$limit = 25;

if(isset($_GET["from_game_id"])) {
	if(ctype_digit($_GET["from_game_id"])) {
		$from_game_id = intval($_GET["from_game_id"]);
		if($from_game_id < 0) $from_game_id = 0;
	}
}

if(isset($_GET["limit"])) {
	if(ctype_digit($_GET["limit"])) {
		$limit = intval($_GET["limit"]);
		if($limit < 0) $limit = 0;
		if($limit > 500) $limit = 500;
	}
}

if(count($users) > 4) {
	dieAPIError("Max 4 users");
}
if(count($sizes) > 5 || count($num_players) > 3) {
	dieAPIError("What are you trying to do?");
}


$games = array();

if(count($sizes) > 0 && count($num_players) > 0) {
	$query = "
	SELECT game_id, map_width, num_players, time_played, turns
	FROM games
	WHERE game_id > ?
	".(count($users) > 0 ? "AND games.game_id IN (SELECT game_id FROM games_users WHERE games_users.user_id=".$users[0].")" : "")."
	AND games.map_width IN (".implode(', ', $sizes).")
	AND games.num_players IN (".implode(', ', $num_players).")
	ORDER BY game_id DESC
	LIMIT 1000";
	//$data["query"] = $query;

	DB::get()->query($query, array($from_game_id));
	$games_results = DB::get()->results();
	foreach($games_results as $row_game){
		if(count($games) >= $limit) break;
		
		$players = array();
		
		$players_found = 0;
		
		$query = "SELECT * FROM games_users WHERE game_id=? ORDER BY player_index ASC";
		DB::get()->query($query, array($row_game->game_id));
		$games_users_results = DB::get()->results();
		foreach($games_users_results as $row_game_user){
			array_push($players, $row_game_user);
			
			if(in_array(intval($row_game_user->user_id), $users))
				$players_found++;
		}
		
		if($players_found == count($users)) {
			$t = new DateTime($row_game->time_played);
			$t->setTimezone(new DateTimeZone("UTC"));
			
			$game = array(
				"game_id" => intval($row_game->game_id),
				"map_size" => intval($row_game->map_width),
				"num_players" => intval($row_game->num_players),
				"time_played" => $t->format("Y-m-d H:i:s e"),
				"turns" => intval($row_game->turns),
				"players" => $players
			);
			
			array_push($games, $game);
		}
	}
}

foreach($users as $user_id) {
	DB::get()->query("UPDATE players SET feed_hits=feed_hits+1 WHERE user_id=?", array($user_id));
}

$data["games"] = $games;
$data["filters"] = array(
	"users" => $users,
	"sizes" => $sizes,
	"num_players" => $num_players,
	"from_game_id" => $from_game_id
);

endAPIRequest();
?>