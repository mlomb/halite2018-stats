<?php
include_once("../../common.php");

startAPIRequest();

if(!isset($_GET["users"])) {
	dieAPIError("Users not provided");
}

$users = arrayFromParameter(isset($_GET["users"]) ? $_GET["users"] : "");
$limit = 0;

if(isset($_GET["limit"])) {
	if(ctype_digit($_GET["limit"])) {
		$limit = intval($_GET["limit"]);
		if($limit < 0) $limit = 0;
	}
}

if(count($users) > 10) {
	dieAPIError("Max 10 users per request");
}

$mu_data = array();
$events = array();

$dt = strtotime('2018-10-15 00:00:00');
$now = time();

while($dt < $now) {
	$mu_data[date('Y-m-d H:i:s', $dt)] = array();
	$dt += 3600;
}

foreach($users as $user_id) {
	DB::get()->query("UPDATE players SET historical_hits=historical_hits+1 WHERE user_id=?", array($user_id));
	
	$query = "SELECT * FROM historical WHERE user_id=? ORDER BY date ASC, hour ASC";
	//$query = "SELECT mu, games.time_played FROM games_users LEFT JOIN games ON games.game_id=games_users.game_id WHERE user_id=?";
	//$query = "SELECT time, mu, sigma, rank FROM snapshots WHERE user_id=?";
	//$query = "SELECT time_played AS time, AVG(mu) AS mu FROM games_users LEFT JOIN games ON games.game_id=games_users.game_id WHERE user_id=? GROUP BY DATE(time_played), HOUR(time_played)";
	DB::get()->query($query, array($user_id));

	if(DB::get()->count() != 0){
		foreach(DB::get()->results() as $row){
			$mu = doubleval($row->mu_sum) / doubleval($row->games);
			$mu = round($mu, 2);

			$time = $row->date." ".sprintf('%02d', $row->hour).":00:00";
			$mu_data[$time][$user_id] = $mu;
		}
	}
	
	/*
	$query = "SELECT MAX(date) AS mdate, MAX(hour) AS mhour, version FROM historical WHERE user_id=? GROUP BY version";
	DB::get()->query($query, array($user_id));
	
	foreach(DB::get()->results() as $row){
		array_push($events, array(
			"date" => $row->mdate." ".sprintf('%02d', $row->mhour).":00:00",
			"user_id" => $user_id,
			"version" => intval($row->version)
		));
	}
	*/
}



$data["graph"] = array_filter($mu_data);
$data["events"] = $events;

$data["users"] = $users;
$data["limit"] = $limit;

endAPIRequest();
?>