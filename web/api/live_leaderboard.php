<?php
include_once("../../common.php");

startAPIRequest();

$users = arrayFromParameter(isset($_GET["user"]) ? $_GET["user"] : "");
$use_mu_rank = isset($_GET["use_mu_rank"]) && intval($_GET["use_mu_rank"]) == 1;

if(count($users) > 1) {
	dieAPIError("The number of users must be 1 or less");
}

$rank = 0;
$rank_field = "rank".($use_mu_rank ? '_mu' : '');

if(count($users) == 1) {
	DB::get()->query("SELECT ".$rank_field." AS rank FROM players WHERE user_id=?", array($users[0]));
	$rank = intval(DB::get()->result()->rank);
	
	DB::get()->query("UPDATE players SET leaderboard_hits=leaderboard_hits+1 WHERE user_id=?", array($users[0]));
}

DB::get()->query("SELECT MAX(".$rank_field.") AS max_rank FROM players");
$max_rank = intval(DB::get()->result()->max_rank);

$display = 6;
$lower = max(0, $rank - $display);
$upper = min($max_rank, $rank + $display);

$diff = $upper - $lower;
if($diff < $display * 2)
	$upper += $display * 2 - $diff;

$data["use_mu_rank"] = $use_mu_rank;
$data["rank"] = $rank;
$data["max_rank"] = $max_rank;
$data["lower"] = $lower;
$data["upper"] = $upper;
$data["leaderboard"] = array();

DB::get()->query("SELECT * FROM players WHERE ".$rank_field." >= ? AND ".$rank_field." <= ? ORDER BY ".$rank_field." ASC", array($lower, $upper));
foreach(DB::get()->results() as $row) {
	array_push($data["leaderboard"], array(
		"user_id" => intval($row->user_id),
		"username" => $row->username,
		"rank" => intval($rank_field == 'rank_mu' ? $row->rank_mu : $row->rank),
		"mu" => doubleval($row->mu),
		"sigma" => doubleval($row->sigma),
		"rating" => doubleval($row->rating),
		"games" => intval($row->num_games)
	));
}

endAPIRequest();
?>