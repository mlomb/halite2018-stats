<?php
$f = fopen('halite2018_cron_games_lock', 'w') or die ('Cannot create lock file');
if (flock($f, LOCK_EX | LOCK_NB)) {

require_once("DB.php");

DB::get()->query("SELECT MAX(game_id) AS last_game_id FROM games");
$last_game_id = DB::get()->result()->last_game_id;
if($last_game_id == 0) {
	$last_game_id = 613137;
}

echo "Last game ID: ".$last_game_id."\n";

$safeguard = 0;

while(1) {
	$safeguard++; if($safeguard > 1500) { die("SAFEGUARD FIRED\n"); }
	
	$lim = 250;
	$api = "https://api.2018.halite.io/v1/api/match?order_by=asc,game_id&limit=".$lim."&filter=game_id,%3E,".$last_game_id;
	
	echo "Requesting: ".$api."\n";
	
	$games = json_decode(file_get_contents($api), true);
	$count = count($games);
	
	echo "Got ".$count." games\n";
	
	
	foreach($games as $game){
		$time_played = strtotime($game["time_played"]);
		
		DB::get()->query("INSERT IGNORE INTO games (game_id, challenge_id, map_width, map_height, time_played, time_played_date, time_played_hour, num_players, turns, execution_time, map_total_halite) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array($game["game_id"], $game["challenge_id"] == null ? 0 : $game["challenge_id"], $game["map_width"], $game["map_height"], date("Y-m-d H:i:s", $time_played), date("Y-m-d", $time_played), date("H", $time_played), count($game["players"]), $game["turns_total"], $game["stats"]["execution_time"], $game["stats"]["map_total_halite"]));
		
		foreach ($game["players"] as $user_id => $player) {
			$stats = $game["stats"]["player_statistics"][$player["player_index"]];
			
			DB::get()->query("INSERT IGNORE INTO games_users (game_id, user_id, player_index, version, rank, mu, sigma, leaderboard_rank, timed_out,
			
			all_collisions,
			average_entity_distance,
			final_production,
			interaction_opportunities,
			last_turn_alive,
			max_entity_distance,
			mining_efficiency,
			number_dropoffs,
			self_collisions,
			total_bonus,
			total_mined,
			total_production,
			
			carried_at_end,
			dropoff_collisions,
			last_turn_ship_spawn,
			ships_peak,
			ships_spawned,
			total_dropped
			
			) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,
			
			?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
			
			?, ?, ?, ?, ?, ?
			
			)", array($game["game_id"], $user_id, $player["player_index"], $player["version_number"], $player["rank"], $player["mu"], $player["sigma"], $player["leaderboard_rank"], $player["timed_out"], 
			
			$stats["all_collisions"],
			$stats["average_entity_distance"],
			$stats["final_production"],
			$stats["interaction_opportunities"],
			$stats["last_turn_alive"],
			$stats["max_entity_distance"],
			$stats["mining_efficiency"],
			$stats["number_dropoffs"],
			$stats["self_collisions"],
			$stats["total_bonus"],
			$stats["total_mined"],
			$stats["total_production"],
			
			$stats["carried_at_end"],
			$stats["dropoff_collisions"],
			$stats["last_turn_ship_spawn"],
			$stats["ships_peak"],
			$stats["ships_spawned"],
			$stats["total_dropped"]
			
			));
			
			
			DB::get()->query("INSERT INTO historical (date, hour, user_id, mu_sum, games, version) VALUES (?, ?, ?, ?, 1, ?) ON DUPLICATE KEY UPDATE mu_sum=mu_sum+VALUES(mu_sum), games=games+VALUES(games), version=VALUES(version)", array(date("Y-m-d", $time_played), date("H", $time_played), $user_id, $player["mu"], $player["version_number"]));
			

			DB::get()->query("UPDATE players SET max_mu=GREATEST(max_mu, ?), min_mu=LEAST(min_mu, ?) WHERE user_id=?", array($player["mu"], $player["mu"], $user_id));
		}
		
		$last_game_id = max($last_game_id, $game["game_id"]);
	}
	
	if($count != 250)
		break;
	
	usleep(300 * 1000); // 300ms
}

}
?>