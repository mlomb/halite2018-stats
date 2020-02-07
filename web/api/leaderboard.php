<?php
include_once("../../common.php");

startAPIRequest();

$query = "SELECT user_id, username, bot_version, profile_image_key, rank FROM players ORDER BY rank ASC";
DB::get()->query($query);

$leaderboard = array();

foreach(DB::get()->results() as $row){
	array_push($leaderboard, array(
		"user_id" => intval($row->user_id),
		"username" => $row->username,
		"version_number" => intval($row->bot_version),
		"profile_image_key" => $row->profile_image_key,
		"rank" => intval($row->rank),
	));
}

$data["leaderboard"] = $leaderboard;

endAPIRequest();
?>