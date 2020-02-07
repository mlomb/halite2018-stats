<?php
require_once("../common.php");

if(!isset($_GET["user_id"])) {
	die("user_id not provided");
}
$user_id = intval($_GET["user_id"]);
$version = isset($_GET["version"]) ? intval($_GET["version"]) : 0;
$min_nemesis_games = isset($_GET["min_nemesis_games"]) ? intval($_GET["min_nemesis_games"]) : 10;
$finals = isset($_GET["finals"]) ? true : false;

$query = "SELECT * FROM players WHERE user_id=?";
DB::get()->query($query, array($user_id));

if(DB::get()->count() == 0) {
	echo '<center><h2>Player not found</h2></center>';
	die();
}
$player = DB::get()->result();

DB::get()->query("UPDATE players SET analysis_hits=analysis_hits+1 WHERE user_id=?", array($user_id));

if($version == 0) {
	$version = $player->bot_version;
}
$firstFinalsGameID = 4955096;
$where_additional = " AND games_users.game_id ".($finals ? ">=" : "<")." ".$firstFinalsGameID;
$where_additional = "";

$query = "SELECT games_users.*, games.num_players, games.map_width AS size, games.challenge_id FROM games_users LEFT JOIN games ON games.game_id=games_users.game_id WHERE games_users.game_id IN (SELECT games_users.game_id FROM games_users WHERE user_id=? AND version=?".$where_additional.")  LIMIT 30000";
DB::get()->query($query, array($user_id, $version));

$win_data = array(
	"2" => array(
		"32" => array(),
		"40" => array(),
		"48" => array(),
		"56" => array(),
		"64" => array(),
		"ALL" => array()
	),
	"4" => array(
		"32" => array(),
		"40" => array(),
		"48" => array(),
		"56" => array(),
		"64" => array(),
		"ALL" => array()
	),
	"2&4" => array(
		"32" => array(),
		"40" => array(),
		"48" => array(),
		"56" => array(),
		"64" => array(),
		"ALL" => array()
	),
);
$info_data = array(
	"2" => array(
		"32" =>  array("total" => 0, "total_new_stats" => 0, "timeouts" => 0, "mining_efficiency" => 0, "number_dropoffs" => 0, "self_collisions" => 0, "all_collisions" => 0, "coll_diff" => 0, "inspiration" => 0, "carried_at_end" => 0, "ships_peak" => 0, "ships_spawned" => 0, "total_dropped" => 0),
		"40" =>  array("total" => 0, "total_new_stats" => 0, "timeouts" => 0, "mining_efficiency" => 0, "number_dropoffs" => 0, "self_collisions" => 0, "all_collisions" => 0, "coll_diff" => 0, "inspiration" => 0, "carried_at_end" => 0, "ships_peak" => 0, "ships_spawned" => 0, "total_dropped" => 0),
		"48" =>  array("total" => 0, "total_new_stats" => 0, "timeouts" => 0, "mining_efficiency" => 0, "number_dropoffs" => 0, "self_collisions" => 0, "all_collisions" => 0, "coll_diff" => 0, "inspiration" => 0, "carried_at_end" => 0, "ships_peak" => 0, "ships_spawned" => 0, "total_dropped" => 0),
		"56" =>  array("total" => 0, "total_new_stats" => 0, "timeouts" => 0, "mining_efficiency" => 0, "number_dropoffs" => 0, "self_collisions" => 0, "all_collisions" => 0, "coll_diff" => 0, "inspiration" => 0, "carried_at_end" => 0, "ships_peak" => 0, "ships_spawned" => 0, "total_dropped" => 0),
		"64" =>  array("total" => 0, "total_new_stats" => 0, "timeouts" => 0, "mining_efficiency" => 0, "number_dropoffs" => 0, "self_collisions" => 0, "all_collisions" => 0, "coll_diff" => 0, "inspiration" => 0, "carried_at_end" => 0, "ships_peak" => 0, "ships_spawned" => 0, "total_dropped" => 0),
		"ALL" => array("total" => 0, "total_new_stats" => 0, "timeouts" => 0, "mining_efficiency" => 0, "number_dropoffs" => 0, "self_collisions" => 0, "all_collisions" => 0, "coll_diff" => 0, "inspiration" => 0, "carried_at_end" => 0, "ships_peak" => 0, "ships_spawned" => 0, "total_dropped" => 0)
	),
	"4" => array(
		"32" =>  array("total" => 0, "total_new_stats" => 0, "timeouts" => 0, "mining_efficiency" => 0, "number_dropoffs" => 0, "self_collisions" => 0, "all_collisions" => 0, "coll_diff" => 0, "inspiration" => 0, "carried_at_end" => 0, "ships_peak" => 0, "ships_spawned" => 0, "total_dropped" => 0),
		"40" =>  array("total" => 0, "total_new_stats" => 0, "timeouts" => 0, "mining_efficiency" => 0, "number_dropoffs" => 0, "self_collisions" => 0, "all_collisions" => 0, "coll_diff" => 0, "inspiration" => 0, "carried_at_end" => 0, "ships_peak" => 0, "ships_spawned" => 0, "total_dropped" => 0),
		"48" =>  array("total" => 0, "total_new_stats" => 0, "timeouts" => 0, "mining_efficiency" => 0, "number_dropoffs" => 0, "self_collisions" => 0, "all_collisions" => 0, "coll_diff" => 0, "inspiration" => 0, "carried_at_end" => 0, "ships_peak" => 0, "ships_spawned" => 0, "total_dropped" => 0),
		"56" =>  array("total" => 0, "total_new_stats" => 0, "timeouts" => 0, "mining_efficiency" => 0, "number_dropoffs" => 0, "self_collisions" => 0, "all_collisions" => 0, "coll_diff" => 0, "inspiration" => 0, "carried_at_end" => 0, "ships_peak" => 0, "ships_spawned" => 0, "total_dropped" => 0),
		"64" =>  array("total" => 0, "total_new_stats" => 0, "timeouts" => 0, "mining_efficiency" => 0, "number_dropoffs" => 0, "self_collisions" => 0, "all_collisions" => 0, "coll_diff" => 0, "inspiration" => 0, "carried_at_end" => 0, "ships_peak" => 0, "ships_spawned" => 0, "total_dropped" => 0),
		"ALL" => array("total" => 0, "total_new_stats" => 0, "timeouts" => 0, "mining_efficiency" => 0, "number_dropoffs" => 0, "self_collisions" => 0, "all_collisions" => 0, "coll_diff" => 0, "inspiration" => 0, "carried_at_end" => 0, "ships_peak" => 0, "ships_spawned" => 0, "total_dropped" => 0)
	)
);
$game_data = array();
$games_count = 0;
$games_2p_count = 0;
$challenges_count = 0;
$timeouts = 0;
$num_non_stats_games = 0;

foreach(DB::get()->results() as $row){
	$first_game_id = min($first_game_id, $row->game_id);
	if($row->game_id < 2972735) {
		if($row->user_id == $user_id) {
			$num_non_stats_games++;
		}
	}
	if($row->challenge_id == 0) {
		$game_data[$row->game_id]["players"][$row->user_id] = $row->rank;
		$game_data[$row->game_id]["size"] = $row->size;
		$game_data[$row->game_id]["num_players"] = $row->num_players;
		if($row->user_id == $user_id) {
				if($row->timed_out == 1) {
					$timeouts++;
				}
				$games_count++;
				if($row->num_players == 2) {
					$games_2p_count++;
				}
				
				$win_data[$row->num_players][$row->size][$row->rank]++;
				$win_data[$row->num_players]["ALL"][$row->rank]++;
				$win_data["2&4"][$row->size][$row->rank == 1 ? 1 : 2]++;
				$win_data["2&4"]["ALL"][$row->rank == 1 ? 1 : 2]++;
				
				$info_data[$row->num_players][$row->size]["total"]++;
				$info_data[$row->num_players][$row->size]["total_new_stats"] += $row->game_id >= 2972735 ? 1 : 0;
				$info_data[$row->num_players][$row->size]["timeouts"] += $row->timed_out;
				$info_data[$row->num_players][$row->size]["mining_efficiency"] += $row->mining_efficiency;
				$info_data[$row->num_players][$row->size]["number_dropoffs"] += $row->number_dropoffs;
				$info_data[$row->num_players][$row->size]["self_collisions"] += $row->self_collisions;
				$info_data[$row->num_players][$row->size]["all_collisions"] += $row->all_collisions;
				$info_data[$row->num_players][$row->size]["coll_diff"] += $row->all_collisions - $row->self_collisions;
				$info_data[$row->num_players][$row->size]["inspiration"] += $row->total_bonus;
				$info_data[$row->num_players][$row->size]["carried_at_end"] += $row->carried_at_end;
				$info_data[$row->num_players][$row->size]["ships_peak"] += $row->ships_peak;
				$info_data[$row->num_players][$row->size]["ships_spawned"] += $row->ships_spawned;
				$info_data[$row->num_players][$row->size]["total_dropped"] += $row->total_dropped;
				$info_data[$row->num_players][$row->size]["insp_over_mining"] += $row->total_bonus / $row->total_mined;
				$info_data[$row->num_players][$row->size]["final_production"] += $row->final_production;
				
				$info_data[$row->num_players]["ALL"]["total"]++;
				$info_data[$row->num_players]["ALL"]["total_new_stats"] += $row->game_id >= 2972735 ? 1 : 0;
				$info_data[$row->num_players]["ALL"]["timeouts"] += $row->timed_out;
				$info_data[$row->num_players]["ALL"]["mining_efficiency"] += $row->mining_efficiency;
				$info_data[$row->num_players]["ALL"]["number_dropoffs"] += $row->number_dropoffs;
				$info_data[$row->num_players]["ALL"]["self_collisions"] += $row->self_collisions;
				$info_data[$row->num_players]["ALL"]["all_collisions"] += $row->all_collisions;
				$info_data[$row->num_players]["ALL"]["coll_diff"] += $row->all_collisions - $row->self_collisions;
				$info_data[$row->num_players]["ALL"]["inspiration"] += $row->total_bonus;
				$info_data[$row->num_players]["ALL"]["carried_at_end"] += $row->carried_at_end;
				$info_data[$row->num_players]["ALL"]["ships_peak"] += $row->ships_peak;
				$info_data[$row->num_players]["ALL"]["ships_spawned"] += $row->ships_spawned;
				$info_data[$row->num_players]["ALL"]["total_dropped"] += $row->total_dropped;
				$info_data[$row->num_players]["ALL"]["insp_over_mining"] += $row->total_bonus / $row->total_mined;
				$info_data[$row->num_players]["ALL"]["final_production"] += $row->final_production;
			
		} else {
			//$nemesis_data[$row->user_id][$row->size]
		}
	} else {
		$challenges_count++;
	}
}


// Process game data to nemesis data
$nemesis_data = array();
foreach($game_data as $game_id => $game_data) {
	$my_rank = $game_data["players"][$user_id];
	$num_players = $game_data["num_players"];
	foreach($game_data["players"] as $player_user_id => $player_rank) {
		if($player_user_id != $user_id) {
			if(!isset($nemesis_data[$num_players][$player_user_id])) {
				$nemesis_data[$num_players][$player_user_id]["table"] = array(
					"32" => array(),
					"40" => array(),
					"48" => array(),
					"56" => array(),
					"64" => array(),
					"ALL" => array()
				);
			}
			$nemesis_data[$num_players][$player_user_id]["table"][$game_data["size"]][$my_rank]++;
			$nemesis_data[$num_players][$player_user_id]["table"]["ALL"][$my_rank]++;
			$nemesis_data[$num_players][$player_user_id]["total"]++;
			$nemesis_data[$num_players][$player_user_id]["nemesis_user_id"] = $player_user_id;
			
			$nemesis_data[$num_players][$player_user_id]["higher_ranks"] += $my_rank < $player_rank ? 1 : 0;
			
			$nemesis_data[$num_players][$player_user_id]["higher_perc"] = $nemesis_data[$num_players][$player_user_id]["higher_ranks"] / $nemesis_data[$num_players][$player_user_id]["total"] * 100;
			$nemesis_data[$num_players][$player_user_id]["winrate"] = $nemesis_data[$num_players][$player_user_id]["table"]["ALL"]["1"] / $nemesis_data[$num_players][$player_user_id]["total"] * 100;
		}
	}
}

?>
<center>
	<h2>Analysis of <a target="_blank" href="https://2018.halite.io/user/?user_id=<?php echo $user_id; ?>"><?php echo $player->username; ?></a> v<?php echo $version.($finals ? "-finals" : ""); ?><i class="fas fa-sync-alt" style="font-size: 19px;margin-top: -3px;margin-left: 10px;color: #54adee;cursor: pointer;" onclick="$('#analyse-version').trigger('change');"></i></h2>
	<h4>
		Games: <?php echo formatNumber($games_count); ?> (<?php echo formatNumber($challenges_count); ?> challenge games excluded)<br>
		Timeouts: <?php echo formatNumber($timeouts); ?> (<?php echo round($timeouts / $games_count * 100, 2);?>%)<br>
		2P to 4P ratio: <?php echo round($games_2p_count / ($games_count - $games_2p_count) * 100, 2);?>%<br>
		<?php
		if($num_non_stats_games > 0) {
			?>
			<br>
			<span style="color: yellow">
			Seems like this version played <?php echo $num_non_stats_games; ?> games before the version 1.1.6.<br>
			New stats from those games will not be available.
			</span>
			<?php
		}
		?>
	</h4>
</center>
<?php

function formatPercentageTD($percentage, $cut, $greater = true, $bg = null, $more = "", $tooltip = "") {
	return '<td title="'.$tooltip.'" style="color:'.(($greater ? $percentage >= $cut : $percentage <= $cut) ? '#93ffa1' : '#ff9393').';'.($bg != null ? 'background-color:'.$bg : '').'">'.round($percentage,2).'%'.$more.'</td>';
}
function generateRankTable($wd, $num_ranks, $num_players, $loss_enabled = false, $head_to_head_enabled = false) {
	global $win_data;
	
	foreach($wd as $size => $places) {
		echo '
		<tr>
			<td>'.($size == 'ALL' ? 'ALL' : $size.'&times;'.$size).'</td>';
		$sum = 0;
		$points = 0;
		$places_arr = array();
		for($i = 1; $i <= $num_ranks; $i++) {
			$n = (array_key_exists($i, $places) ? $places[$i] : 0);
			array_push($places_arr, $n);
			$sum += $n;
			$points += (4 - $i) * $n;
			echo '<td>'.$n.'</td>';
		}
		
		$winrate = ($places_arr[0] / $sum) * 100;
		$green_th = 100 / $num_ranks;

		if($num_players == "2&4") {
			$nr_2p_games = $win_data["2"][$size][1] + $win_data["2"][$size][2];
			$nr_4p_games = $win_data["4"][$size][1] + $win_data["4"][$size][2] + $win_data["4"][$size][3] + $win_data["4"][$size][4];
			$nr_games = $nr_2p_games + $nr_4p_games;
			$green_th = (0.25 * ($nr_4p_games / $nr_games) + 0.5 * ($nr_2p_games / $nr_games)) * 100;
			//echo $nr_2p_games . "." . $nr_4p_games;
			//echo json_encode($win_data[2]);
		}

		echo '
			<td>'.$sum.'</td>
			'.formatPercentageTD($winrate, $green_th, true, null, "" /* (".round($green_th, 2).")" */).'
			'.($loss_enabled ? formatPercentageTD(100 - $winrate, 100 - (100 / $num_ranks), false, ($size == 'ALL' ? 'rgba(2, 30, 82, 0.23)' : null)) : '').'
			'.($head_to_head_enabled ? formatPercentageTD(($points / (3* $sum)) * 100, 50, true, null, "", $points.'/'.(3* $sum).' points') : '').'
			<td><span class="sparklines" values="'.str_replace(array('[', ']' ), '', json_encode($places_arr)).'"></span></td>
		</tr>';
	}
}
function generateWinTable($num_players) {
	global $user_id, $version;
	global $win_data;
	
	$num_ranks = $num_players == "4" ? 4 : 2;
?>

<table class="table-style">
	<thead>
		<tr>
			<th colspan="<?php echo 4 + $num_ranks + ($num_ranks == 4); ?>"><?php echo $num_players; ?>p</th>
		</tr>
		<tr>
			<th rowspan="2">SIZE</th>
			<th colspan="<?php echo $num_ranks; ?>"><?php echo $num_players == '2&4' ? 'RESULT' : 'RANKS'; ?></th>
			<th rowspan="2">TOTAL</th>
			<th rowspan="2">WIN %</th>
			<?php
			if($num_ranks == 4) {
			?>
			<th rowspan="2" title="Head-to-Head win rate  (sum(4-rank)/(3*num_games))">H2H Win %</th>
			<?php
			}
			?>
			<th rowspan="2"><i class="fas fa-chart-bar"></i></th>
		</tr>
		<tr>
<?php
	for($i = 1; $i <= $num_ranks; $i++) {
		if($num_players == "2&4") {
			echo '<th>'.($i == 1 ? 'WINS' : 'LOSSES').'</th>';
		} else {
			echo '<th>'.$i.'°</th>';
		}
	}
?>
		</tr>
	</thead>
	<tbody>
<?php
	generateRankTable($win_data[$num_players], $num_ranks, $num_players, false, $num_ranks == 4);
?>
	</tbody>
</table>
<?php
}
function generateNemesisTable($num_players) {
	global $user_id, $version;
	global $nemesis_data;
	global $min_nemesis_games;
	
	usort($nemesis_data[$num_players], function($a, $b)
	{
		return $a["winrate"] > $b["winrate"]; // this sorts asc for some reason
	});
	
	//echo json_encode($nemesis_data[$num_players]);
	
	$num_ranks = $num_players;
?>
<table class="table-style">
	<thead>
		<tr>
			<th colspan="<?php echo 6 + $num_players + 2*($num_ranks == 4); ?>">NEMESES <?php echo $num_players; ?>p</th>
		</tr>
		<tr>
			<th rowspan="2">NEMESIS</th>
			<?php if($num_players == 4) { ?><th title="% of games where you have a higher rank" rowspan="2">HIGHER RANK %</th><?php } ?>
			<th rowspan="2">SIZE</th>
			<th colspan="<?php echo $num_ranks; ?>">RANKS</th>
			<th rowspan="2">TOTAL</th>
			<th rowspan="2">WIN %</th>
			<th rowspan="2">LOSS %</th>
			<?php
			if($num_ranks == 4) {
			?>
			<th rowspan="2" title="Head-to-Head win rate  (sum(4-rank)/(3*num_games))">H2H Win %</th>
			<?php
			}
			?>
			<th rowspan="2"><i class="fas fa-chart-bar"></i></th>
		</tr>
		<tr>
		
	<?php
	for($i = 1; $i <= $num_ranks; $i++) {
		echo '<th>'.$i.'°</th>';
	}
	?>
		</tr>
	</thead>
	<tbody>
<?php
	$c = 0;
	foreach($nemesis_data[$num_players] as $nemesis_data_entry){
		$c++;
		if($nemesis_data_entry["total"] < $min_nemesis_games) continue;
		
		$rowspan = count($nemesis_data_entry["table"]) + 1;
		
		?>
		<tr>
			<td data-user-id="<?php echo $nemesis_data_entry["nemesis_user_id"]; ?>" rowspan="<?php echo $rowspan; ?>">
				<span class="rank"></span>
				<img class="profile-pic"></img>
				<span class="username"></span>
			</td>
			<?php if($num_players == 4) { ?>
			<td title="<?php echo $nemesis_data_entry["higher_ranks"].' out of '.$nemesis_data_entry["total"].' games'; ?>" rowspan="<?php echo $rowspan; ?>" style="color: <?php echo $nemesis_data_entry["higher_perc"] >= 50 ? '#93ffa1' : '#ff9393'; ?>"><?php echo round($nemesis_data_entry["higher_perc"],2).'%'; ?></td>
			<?php } ?>
		</tr>
<?php
		generateRankTable($nemesis_data_entry["table"], $num_ranks, $num_players, true, $num_ranks == 4);
		if($c > 10 && $min_nemesis_games == 10) break;
	}
?>
	</tbody>
</table>
<?php
}

function generateInfoTable($num_players) {
	global $user_id, $version;
	global $info_data;
	global $num_non_stats_games;
	
?>

<table class="table-style">
	<thead>
		<tr>
			<th colspan="13">Stats <?php echo $num_players; ?>p</th>
		</tr>
		<tr>
			<th rowspan="3">SIZE</th>
			<th colspan="10">AVGs</th>
			<th rowspan="3">TIMEOUTS</th>
			<th rowspan="3">TOTAL</th>
		</tr>
		<tr>
			<th rowspan="2">MINING EFF.</th>
			<th rowspan="2"># DROPOFFS</th>
			<th rowspan="2">ALL - SELF COLL.</th>
			<th rowspan="2" title="Average of total_bonus / total_mined as %">INSP. / MINING %</th>
			<th rowspan="2" title="Average of halite at the end of the game">FINAL</th>
			<th rowspan="2" title="Average of halite collected from inspiration bonuses">INSPIRATION</th>
			<th <?php if($num_non_stats_games > 0) echo 'style="background-color: #b3b305"'; ?> rowspan="2" title="Average of halite in ships at the end of the game">CARRIED AT END</th>
			<th <?php if($num_non_stats_games > 0) echo 'style="background-color: #b3b305"'; ?> rowspan="2" title="Average of halite lost due collisions">DROPPED</th>
			<th <?php if($num_non_stats_games > 0) echo 'style="background-color: #b3b305"'; ?> colspan="2">SHIPS</th>
		</tr>
		<tr>
			<th <?php if($num_non_stats_games > 0) echo 'style="background-color: #b3b305"'; ?> title="Average of ships spawned" rowspan="2">SPAWNED</th>
			<th <?php if($num_non_stats_games > 0) echo 'style="background-color: #b3b305"'; ?> title="Average of ships alive at the same time" rowspan="2">PEAK</th>
		</tr>
	</thead>
	<tbody>
		<tr>
<?php

/*

			$info_data[$row->num_players]["ALL"]["mining_efficiency"] += $row->mining_efficiency;
			$info_data[$row->num_players]["ALL"]["number_dropoffs"] += $row->number_dropoffs;
			$info_data[$row->num_players]["ALL"]["self_collisions"] += $row->self_collisions;
			$info_data[$row->num_players]["ALL"]["all_collisions"] += $row->all_collisions;
*/

	foreach($info_data[$num_players] as $size => $info) {
		$coll_self = round($info["self_collisions"]/$info["total"],2);
		$coll_all = round($info["all_collisions"]/$info["total"],2);
		echo '
		<tr>
			<td>'.($size == 'ALL' ? 'ALL' : $size.'&times;'.$size).'</td>
			<td>'.round($info["mining_efficiency"]/$info["total"]*100,2).'%</td>
			<td>'.round($info["number_dropoffs"]/$info["total"],2).'</td>
			<td title="'.('Self: '.$coll_self.'  All: '.$coll_all).'">'.round($info["coll_diff"]/$info["total"],2).'</td>
			
			<td>'.round($info["insp_over_mining"]/$info["total"] * 100, 2).' %</td>
			<td>'.number_format(round($info["final_production"]/$info["total"])).'</td>
			<td>'.number_format(round($info["inspiration"]/$info["total"])).'</td>
			<td>'.number_format(round($info["carried_at_end"]/$info["total_new_stats"])).'</td>
			<td>'.number_format(round($info["total_dropped"]/$info["total_new_stats"])).'</td>
			
			<td>'.number_format(round($info["ships_spawned"]/$info["total_new_stats"])).'</td>
			<td>'.number_format(round($info["ships_peak"]/$info["total_new_stats"])).'</td>
			
			<td style="color: '.($info["timeouts"] == 0 ? '#93ffa1' : '#ff9393').'">'.$info["timeouts"].'</td>
			<td>'.$info["total"].'</td>
		</tr>';
	}
?>
		</tr>
	</tbody>
</table>
<?php
}

generateWinTable("2");
generateWinTable("4");
generateWinTable("2&4");

generateInfoTable("2");
generateInfoTable("4");

generateNemesisTable(2);
generateNemesisTable(4);

?>
<script>
$(".sparklines").sparkline('html', {
	type: "bar",
	chartRangeMin: 0,
	zeroAxis: true,
	barColor: "#fff64c"
});

updateRanks();

</script>