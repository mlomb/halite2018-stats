<?php
require_once("common.php");

ob_start();
?>

<table class="table-style">
	<thead>
		<tr>
			<th colspan="4" style="text-transform: none;">Highest μ</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Player</th>
			<th style="text-transform: none;">μ</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i = 1;
	
	DB::get()->query("SELECT user_id, max_mu FROM players ORDER BY max_mu DESC LIMIT 10");
	foreach(DB::get()->results() as $row){
	?>
	<tr>
		<td><?php echo $i++; ?></td>
		<td data-user-id="<?php echo $row->user_id; ?>" style="text-align: left;">
			<span class="rank"></span>
			<img class="profile-pic"></img>
			<span class="username"></span>
		</td>
		<td><?php echo $row->max_mu; ?></td>
	</tr>
	<?php
	}
	?>
	</tbody>
</table>


<table class="table-style">
	<thead>
		<tr>
			<th colspan="4" style="text-transform: none;">Lowest μ</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Player</th>
			<th style="text-transform: none;">μ</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i = 1;
	
	DB::get()->query("SELECT user_id, min_mu FROM players ORDER BY min_mu ASC LIMIT 10");
	$results = DB::get()->results();
	foreach($results as $row){
		//DB::get()->query("UPDATE players SET min_mu=LEAST(max_mu, ?) WHERE user_id=?", array($row->mu, $row->user_id));
	?>
	<tr>
		<td><?php echo $i++; ?></td>
		<td data-user-id="<?php echo $row->user_id; ?>" style="text-align: left;">
			<span class="rank"></span>
			<img class="profile-pic"></img>
			<span class="username"></span>
		</td>
		<td><?php echo $row->min_mu; ?></td>
	</tr>
	<?php
	}
	?>
	</tbody>
</table>






<table class="table-style">
	<thead>
		<tr>
			<th colspan="4" style="text-transform: none;">Halite collected</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Game ID</th>
			<th>Player</th>
			<th>Halite</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i = 1;
	
	DB::get()->query("SELECT games_users.game_id, games_users.user_id, games_users.final_production, games.time_played FROM games_users LEFT JOIN games ON games.game_id=games_users.game_id ORDER BY final_production DESC LIMIT 10");
	foreach(DB::get()->results() as $row){
	?>
	<tr>
		<td><?php echo $i++; ?></td>
		<td><?php echo '<a href="https://halite.io/play?game_id='.$row->game_id.'" target="_blank">'.$row->game_id.'</a>'; ?></td>
		<td data-user-id="<?php echo $row->user_id; ?>" style="text-align: left;">
			<span class="rank"></span>
			<img class="profile-pic"></img>
			<span class="username"></span>
		</td>
		<td><?php echo number_format($row->final_production); ?></td>
	</tr>
	<?php
	}
	?>
	</tbody>
</table>




<table class="table-style">
	<thead>
		<tr>
			<th colspan="4" style="text-transform: none;">Ships peak</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Game ID</th>
			<th>Player</th>
			<th># of ships</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i = 1;
	
	DB::get()->query("SELECT * FROM games_users ORDER BY ships_peak DESC LIMIT 10");
	foreach(DB::get()->results() as $row){
	?>
	<tr>
		<td><?php echo $i++; ?></td>
		<td><?php echo '<a href="https://halite.io/play?game_id='.$row->game_id.'" target="_blank">'.$row->game_id.'</a>'; ?></td>
		<td data-user-id="<?php echo $row->user_id; ?>" style="text-align: left;">
			<span class="rank"></span>
			<img class="profile-pic"></img>
			<span class="username"></span>
		</td>
		<td><?php echo number_format($row->ships_peak); ?></td>
	</tr>
	<?php
	}
	?>
	</tbody>
</table>




<table class="table-style">
	<thead>
		<tr>
			<th colspan="4" style="text-transform: none;"># of dropoffs</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Game ID</th>
			<th>Player</th>
			<th># of dropoffs</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i = 1;
	
	DB::get()->query("SELECT games_users.game_id, games_users.user_id, games_users.number_dropoffs, games.time_played FROM games_users LEFT JOIN games ON games.game_id=games_users.game_id ORDER BY number_dropoffs DESC LIMIT 10");
	foreach(DB::get()->results() as $row){
	?>
	<tr>
		<td><?php echo $i++; ?></td>
		<td><?php echo '<a href="https://halite.io/play?game_id='.$row->game_id.'" target="_blank">'.$row->game_id.'</a>'; ?></td>
		<td data-user-id="<?php echo $row->user_id; ?>" style="text-align: left;">
			<span class="rank"></span>
			<img class="profile-pic"></img>
			<span class="username"></span>
		</td>
		<td><?php echo $row->number_dropoffs; ?></td>
	</tr>
	<?php
	}
	?>
	</tbody>
</table>





<table class="table-style">
	<thead>
		<tr>
			<th colspan="4" style="text-transform: none;"># of collisions (all-self)</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Game ID</th>
			<th>Player</th>
			<th># of collisions</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i = 1;
	
	DB::get()->query("SELECT games_users.game_id, games_users.user_id, (games_users.all_collisions - games_users.self_collisions) AS val, games.time_played FROM games_users LEFT JOIN games ON games.game_id=games_users.game_id ORDER BY val DESC LIMIT 10");
	foreach(DB::get()->results() as $row){
	?>
	<tr>
		<td><?php echo $i++; ?></td>
		<td><?php echo '<a href="https://halite.io/play?game_id='.$row->game_id.'" target="_blank">'.$row->game_id.'</a>'; ?></td>
		<td data-user-id="<?php echo $row->user_id; ?>" style="text-align: left;">
			<span class="rank"></span>
			<img class="profile-pic"></img>
			<span class="username"></span>
		</td>
		<td><?php echo $row->val; ?></td>
	</tr>
	<?php
	}
	?>
	</tbody>
</table>







<table class="table-style">
	<thead>
		<tr>
			<th colspan="5" style="text-transform: none;">Extreme high halite maps</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Game ID</th>
			<th>Size</th>
			<th>Halite</th>
			<th>Density</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i = 1;
	
	DB::get()->query("SELECT * FROM games ORDER BY map_total_halite DESC LIMIT 10");
	foreach(DB::get()->results() as $row){
	?>
	<tr>
		<td><?php echo $i++; ?></td>
		<td><?php echo '<a href="https://halite.io/play?game_id='.$row->game_id.'" target="_blank">'.$row->game_id.'</a>'; ?></td>
		<td><?php echo $row->map_width."x".$row->map_height; ?></td>
		<td><?php echo number_format($row->map_total_halite); ?></td>
		<td><?php echo number_format($row->map_total_halite / ($row->map_width * $row->map_height), 2, '.', ''); ?></td>
	</tr>
	<?php
	}
	?>
	</tbody>
</table>


<table class="table-style">
	<thead>
		<tr>
			<th colspan="5" style="text-transform: none;">Extreme high density maps</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Game ID</th>
			<th>Size</th>
			<th>Halite</th>
			<th>Density</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i = 1;
	
	DB::get()->query("SELECT game_id, map_total_halite, map_width, map_height, (map_total_halite / (map_width * map_height)) AS density FROM games ORDER BY density DESC LIMIT 10");
	foreach(DB::get()->results() as $row){
	?>
	<tr>
		<td><?php echo $i++; ?></td>
		<td><?php echo '<a href="https://halite.io/play?game_id='.$row->game_id.'" target="_blank">'.$row->game_id.'</a>'; ?></td>
		<td><?php echo $row->map_width."x".$row->map_height; ?></td>
		<td><?php echo number_format($row->map_total_halite); ?></td>
		<td><?php echo number_format($row->map_total_halite / ($row->map_width * $row->map_height), 2, '.', ''); ?></td>
	</tr>
	<?php
	}
	?>
	</tbody>
</table>



<table class="table-style">
	<thead>
		<tr>
			<th colspan="5" style="text-transform: none;">Extreme low halite maps</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Game ID</th>
			<th>Size</th>
			<th>Halite</th>
			<th>Density</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i = 1;
	
	DB::get()->query("SELECT * FROM games WHERE map_total_halite > 0 ORDER BY map_total_halite ASC LIMIT 10");
	foreach(DB::get()->results() as $row){
	?>
	<tr>
		<td><?php echo $i++; ?></td>
		<td><?php echo '<a href="https://halite.io/play?game_id='.$row->game_id.'" target="_blank">'.$row->game_id.'</a>'; ?></td>
		<td><?php echo $row->map_width."x".$row->map_height; ?></td>
		<td><?php echo number_format($row->map_total_halite); ?></td>
		<td><?php echo number_format($row->map_total_halite / ($row->map_width * $row->map_height), 2, '.', ''); ?></td>
	</tr>
	<?php
	}
	?>
	</tbody>
</table>


<table class="table-style">
	<thead>
		<tr>
			<th colspan="5" style="text-transform: none;">Extreme low density maps</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Game ID</th>
			<th>Size</th>
			<th>Halite</th>
			<th>Density</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i = 1;
	
	DB::get()->query("SELECT game_id, map_total_halite, map_width, map_height, (map_total_halite / (map_width * map_height)) AS density FROM games WHERE map_total_halite > 0 ORDER BY density ASC LIMIT 10");
	foreach(DB::get()->results() as $row){
	?>
	<tr>
		<td><?php echo $i++; ?></td>
		<td><?php echo '<a href="https://halite.io/play?game_id='.$row->game_id.'" target="_blank">'.$row->game_id.'</a>'; ?></td>
		<td><?php echo $row->map_width."x".$row->map_height; ?></td>
		<td><?php echo number_format($row->map_total_halite); ?></td>
		<td><?php echo number_format($row->map_total_halite / ($row->map_width * $row->map_height), 2, '.', ''); ?></td>
	</tr>
	<?php
	}
	?>
	</tbody>
</table>



<table class="table-style">
	<thead>
		<tr>
			<th colspan="4" style="text-transform: none;">Highest μ on v1</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Player</th>
			<th style="text-transform: none;">μ</th>
			<th>Time</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i = 1;
	
	DB::get()->query("SELECT user_id, MAX(mu) AS max_mu, games.time_played FROM games_users LEFT JOIN games ON games.game_id=games_users.game_id WHERE version=1 GROUP BY user_id ORDER BY MAX(mu) DESC LIMIT 10");
	foreach(DB::get()->results() as $row){
	?>
	<tr>
		<td><?php echo $i++; ?></td>
		<td data-user-id="<?php echo $row->user_id; ?>" style="text-align: left;">
			<span class="rank"></span>
			<img class="profile-pic"></img>
			<span class="username"></span>
		</td>
		<td><?php echo $row->max_mu; ?></td>
		<td><?php echo $row->time_played; ?></td>
	</tr>
	<?php
	}
	?>
	</tbody>
</table>


<?php

file_put_contents("/var/www/halite2018.mlomb.me/records.html", ob_get_clean());
?>