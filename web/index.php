<?php
require_once("../common.php");

$data = json_decode(file_get_contents("../counts.json"));

DB::get()->query("SELECT * FROM stats");
$r = DB::get()->result();
$games_count = $r->games_count;
$games_users_count = $r->games_users_count;
$players_count = $r->players_count;

$finalsBegin = "2019-01-22 23:59:59 EST";
$finalsEnd = "2019-01-29 11:59:59 EST";


$contest_open = time() < strtotime($finalsBegin);

?>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Halite III Statistics & Analysis</title>
		<meta name="description" content="Analyze your bot. Check statistics, records, and watch the real time game feed and leaderboard">
		<meta name="keywords" content="halite, 2018, analysis, graphs, statistics, records, ai challenge">
		<meta property="og:site_name" content="Halite 2018 Stats">
		<meta property="og:title" content="Halite III Statistics & Analysis">
		<meta property="og:type" content="website">
		<meta property="og:url" content="https://halite2018.mlomb.me">
		<meta property="og:description" content="Analyze your bot. Check statistics, records, and watch the real time game feed and leaderboard">
		<meta name="theme-color" content="#003997">
		<meta name="google-site-verification" content="2bgBvUgwWMeAPgvei9GXgX-wGxLcKCaijMYlNRv4c3A" />
		<link rel="canonical" href="https://halite2018.mlomb.me" />
		<link rel="icon" href="https://halite2018.mlomb.me/favicon.ico" type="image/x-icon">
		<link href="https://fonts.googleapis.com/css?family=Nunito|Nunito+Sans" rel="stylesheet">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" />
		<style>
		* {
			margin: 0;
			padding: 0;
			outline: none;
			box-sizing: border-box;
		}
		body {
			background-attachment: fixed;
			background-size: cover;
			background-color: #091B53;
			background-size: 100% auto;
			background-repeat: repeat-y;
			background-image: linear-gradient(0deg, rgba(0,45,119,0) 4%, #003A99 100%),url(bg-pattern.png);
			color: rgba(255,255,255,0.8);
			font-family: "Nunito Sans","Helvetica Neue","Helvetica","Arial",sans-serif;
			
			min-height: 100%;
			position: relative;
			padding-bottom: 32px; /* footer height */
		}
		a {
			color: #54adee;
			text-decoration: none;
		}
		.title {
			font-size: 30px;
			font-weight: 600;
			text-transform: uppercase;
			color: #B4E6FF;
			text-shadow: 0 1px 8px #00ABFF;
		}
		.logo {
			width: 286px;
			display: block;
			margin: 0 auto;
		}
		.subtitle {
			text-align: center;
			margin: 5px 0 18px 0;
		}
		.tabs {
			display: flex;
			flex-wrap: wrap;
			justify-content: center;
		}
		.tab-button {
			color: #91DBFF;
			text-shadow: 0 -1px 13px #00ABFF;
			text-transform: uppercase;
			border-radius: 2px;
			padding: 8px;
			cursor: pointer;
			order: 1;
		}
		.tab-content {
			width: 100%;
			order: 2;
		}
		.tabs > input[type=radio], .tab-content {
			display: none;
		}
		.tabs > input[type=radio]:checked + label {
			background: hsla(186, 100%, 75%, 0.12);
		}
		.tabs > input[type=radio]:checked + label + div {
			display: block;
		}
		.container {
			padding: 10px;
		}
		.content {
			
		}
		.footer {
			position: absolute;
			right: 0;
			bottom: 0;
			left: 0;
			padding: 5px;
			text-align: center;
		}
		
		.table-style {
			background: #46637f;
			color: white;
			border-collapse: collapse;
    		border-spacing: 0;
			margin: auto;
			margin-top: 25px;
			border-radius: 4px;
		}
		.table-stye thead tr {
			/*background: #013f99;*/
		}
		.table-style th {
			text-transform: uppercase;
			font-weight: 600;
			padding: 10px;
			position: relative;
			border: none;
			background-color: rgba(2, 30, 82, 0.23);
		}
		.table-style td {
			position: relative;
			border: none;
    		padding: 8px;
		}
		.table-style tr {
			padding: 10px;
    		text-align: center;
		}
		.result-table {
			background: rgba(133, 170, 255, 0.21);
    		border: none;
			border-collapse: collapse;
			border-spacing: 0;
			width: 100%;
			margin: 1px 0;
		}
		.result-table tr {
			text-align: left;
		}
		.result-table td {
			padding: 3px 4px;
    		border: none;
		}
		.tier {
			width: 22px;
			height: 25px;
			vertical-align: middle;
		}
		.profile-pic {
			width: 25px;
			height: 25px;
			vertical-align: middle;
		}


		tr>th::before, tr>th::after,
		tr>td::before, tr>td::after {
			content: "";
			position: absolute;
			display: inline-block;
			background-color: rgba(8,27,83,0.1);
		}

		tr>th::before,
		tr>td::before {
			top: 0;
			right: 0;
			width: 2px;
			height: 100%
		}

		tr>th::after,
		tr>td::after {
			bottom: 0;
			left: 0;
			width: 100%;
			height: 2px
		}

		#game_feed_players_chosen, #players_chosen {
			border-radius: 4px;
			border: 1px solid #475F92;
			box-shadow: 6px 17px 29px rgba(23,174,219,0.05);
			background-color: rgba(255,255,255,0.08);
		}
		#game_feed_players_chosen .chosen-choices, #players_chosen .chosen-choices {
			background: transparent!important;
			border: none;
		}
		.chosen-search-input {
			color: silver!important;
		}
		.chosen-container-active .chosen-choices li.search-field input[type=text] {
			color: white!important;
		}
		.historical-picker {
			width: 600px;
			margin: auto;
			margin-top: 15px;
			margin-bottom: 15px;
		}
		.historical-picker .buttons-container {
			text-align: right;
			margin-top: 8px;
		}
		.halite-button {
			color: rgb(255, 255, 255);
			background-image: linear-gradient(0deg, rgb(0, 115, 219) 0%, rgb(0, 68, 164) 100%);
			border: 0;
			font-size: 14px;
			font-weight: 600;
			padding: 7px 15px 7px 15px;
			border-radius: 4px;
			text-align: center;
			text-transform: uppercase;
			background-color: #fff;
			line-height: 1em;
			cursor: pointer;
		}
		#historical-message {
			text-align: center;
			font-size: 24px;
		}
		#historical-message, #historical-graph {
			display: none;
		}
		.game-feed-filter {
			padding: 8px;
		}
		input[type="checkbox"] {
			display:none;
		}
		input[type="checkbox"] + label span {
			display:inline-block;
			width:19px;
			height:19px;
			margin:-1px 4px 0 0;
			vertical-align:middle;
			background:url(https://cdn.tutsplus.com/webdesign/uploads/legacy/tuts/391_checkboxes/check_radio_sheet.png) left top no-repeat;
			cursor:pointer;
		}
		input[type="checkbox"]:checked + label span {
			background:url(https://cdn.tutsplus.com/webdesign/uploads/legacy/tuts/391_checkboxes/check_radio_sheet.png) -19px top no-repeat;
		}
		#leaderboard-interval {
			border: 1px solid #aaa;
			border-radius: 5px;
			background-color: #fff;
			background: -webkit-gradient(linear,left top,left bottom,color-stop(20%,#fff),color-stop(50%,#f6f6f6),color-stop(52%,#eee),to(#f4f4f4));
			background: linear-gradient(#fff 20%,#f6f6f6 50%,#eee 52%,#f4f4f4 100%);
			background-clip: padding-box;
			-webkit-box-shadow: 0 0 3px #fff inset, 0 1px 1px rgba(0,0,0,.1);
			box-shadow: 0 0 3px #fff inset, 0 1px 1px rgba(0,0,0,.1);
			color: #444;
			text-decoration: none;
			white-space: nowrap;
			line-height: 24px;
			padding-left: 3px;
		}
		</style>
	</head>
	<body>
		<div class="container">
			<img class="logo" alt="Halite" src="full_logo.svg">
			<div class="title subtitle">Statistics</div>
			<div class="content">
				<div class="tabs">
					<input id="tab-historical" type="radio" name="grp"/>
					<label class="tab-button" for="tab-historical">Historical</label>
					<div class="tab-content">
						<div class="historical-picker">
							<select data-placeholder="Type your players of interest" id="players" multiple></select>
							<div class="buttons-container">
								<button class="halite-button top10">Check TOP 10</button>
								<button class="halite-button clear">Clear</button>
							</div>
						</div>
						<div style="width: 80%;height: 700px;margin:auto;" id="historical-graph"></div>
						<div id="historical-message">
							<span>...</span>
						</div>
					</div>
					
					<input id="tab-game-feed" type="radio" name="grp"/>
					<label class="tab-button" for="tab-game-feed">Live Game Feed</label>
					<div class="tab-content">
						<div class="historical-picker">
							<select data-placeholder="Filter by players" id="game-feed-players" multiple></select>
							<div class="game-feed-filter">
								<input type="checkbox" id="game-feed-2p" checked/><label for="game-feed-2p"><span></span>2P</label> <input type="checkbox" id="game-feed-4p" checked/><label for="game-feed-4p"><span></span>4P</label> <br>
								<?php
								$sizes = [32, 40, 48, 56, 64];
								foreach($sizes as $size) {
									echo '<input type="checkbox" id="game-feed-'.$size.'" checked><label for="game-feed-'.$size.'"><span></span>'.$size.'x'.$size.' </label>';
								}
								
								?>  <br>
							</div>
						</div>
						<table class="table-style">
							<thead>
								<tr>
									<th>Time</th>
									<th>Result</th>
									<th>Map Size</th>
									<th>Turns</th>
								</tr>
							</thead>
							<tbody id="game-feed"></tbody>
						</table>
					</div>

					<input id="tab-live-leaderboard" type="radio" name="grp" <?php if(!$contest_open) echo 'checked'; ?>/>
					<label class="tab-button" for="tab-live-leaderboard">Live Leaderboard</label>
					<div class="tab-content">
						<div class="historical-picker">
							<select data-placeholder="Player to track" id="leaderboard-player">
								<option value=""></option>
							</select>
							<input id="leaderboard-interval" style="width: 15%" type="number" min="10" title="Refresh time. Minimum 10 seconds">
							<input type="checkbox" id="use-rank-mu"/><label for="use-rank-mu"><span></span>Rank by μ</label>

							<div id="leaderboard-status" style="text-align:center;"></div>
							<br>
							<div style="text-align:center;"><a href="https://2018.halite.io/finals-status" target="_blank">Finals</a>: <span id="finals-status">-</span></div>
						</div>
						<table class="table-style">
							<thead>
								<tr>
									<th>RANK</th>
									<th>Player</th>
									<th>Rating</th>
									<th style="text-transform:none">μ</th>
									<th style="text-transform:none">σ</th>
									<th>Games</th>
									<!--
									<th>Version</th>
									<th>Level</th>
									<th>Language</th>
									<th>Prizes</th>
									-->
								</tr>
							</thead>
							<tbody id="live-leaderboard">
								<tr>
									<td colspan="6">
										Loading...
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					
					<input id="tab-analysis" type="radio" name="grp" <?php if($contest_open) echo 'checked'; ?>/>
					<label class="tab-button" for="tab-analysis">Bot Analysis</label>
					<div class="tab-content">
						<div class="historical-picker">
							<select data-placeholder="Player to analyse" id="analyse-player">
								<option value=""></option>
							</select>
							<select data-placeholder="Version to analyse" id="analyse-version"></select>
						</div>
						<div id="analysis-content"></div>
					</div>
					
					<input id="tab-records" type="radio" name="grp"/>
					<label class="tab-button" for="tab-records">Records</label>
					<div class="tab-content">
						<div style="margin:auto;">
							<?php
							echo file_get_contents("../records.html");
							?>
						</div>
					</div>
					
					<input id="tab-info" type="radio" name="grp"/>
					<label class="tab-button" for="tab-info">Information</label>
					<div class="tab-content">
						<div style="margin:auto;width: 425px">
						<center>Stats</center>
							<?php echo formatNumber($players_count); ?> players<br>
							<?php echo formatNumber($games_count); ?> games<br>
							<?php echo formatNumber($games_users_count); ?> users in games<br>
							Page generated in <span class="generated"></span>ms<br>
							<br>
                            You can find the source code for this website <a href="https://github.com/mlomb/halite2018-stats" target="_blank">here</a>.

                            <br>
                            <br>
                            <a href="https://forums.halite.io/t/collection-of-post-mortems-bot-source-code/1335.html" target="_blank">Collection of Post-mortems & Bot Source Code for Halite III</a>
                            
							<br>
							<br>
							<strike>Games are fetched every 5 seconds. The leaderboard every 10 seconds. Records are computed every hour.</strike> The competition is over!<br>
							For suggestions and improvements <a href="https://discordapp.com/users/129079402548953088/" target="_bank">ping me</a> on Discord!
							<br>
							<br>
						</div>
					</div>
				</div>
			</div>
			<div class="footer">
				<span>by <a href="https://mlomb.me" target="_blank">mlomb</a> (<a href="https://github.com/mlomb" target="_blank">GitHub</a>)<span>
			</div>
		</div>
		<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
		<script src="https://www.amcharts.com/lib/3/serial.js"></script>
		<script src="https://www.amcharts.com/lib/3/amstock.js"></script>
		<script src="https://www.amcharts.com/lib/3/themes/dark.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-sparklines/2.1.2/jquery.sparkline.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timeago/1.6.3/jquery.timeago.min.js"></script>
		<script src="https://d3js.org/d3.v5.min.js"></script>
		<script>
jQuery.timeago.settings.allowFuture = true;

var total_players = <?php echo $players_count; ?>;
var leaderboard = null;
var first_lb_load = true;

var charts = {};
var historical_last_query = null;

function createChart(name, axes, graphs, chartData, misc) {
	var chart = AmCharts.makeChart(name, {
		"type": "serial",
		"theme": "dark",
		"legend": {
			"useGraphSettings": true
		},
		"dataProvider": chartData,
		"synchronizeGrid":true,
		"valueAxes": axes,
		"graphs": graphs,
		"chartScrollbar": {},
		"chartCursor": {
			"cursorPosition": "mouse"
		},
		"titles": [{
			"text": misc.title,
			"size": 15
		}],
		"categoryField": "date",
		"categoryAxis": {
			"minPeriod": "hh",
			"parseDates": true,
			"axisColor": "#DADADA",
			"minorGridEnabled": true
		}
	});
	
	chart.addListener("rendered", function() {
		// start with some zoom
		if(chart.dataProvider.length > 50) {
			chart.zoomToIndexes(chart.dataProvider.length - 40, chart.dataProvider.length - 1);
		}
	});
	
	var lineSeries = chart.series.push(new am4charts.LineSeries());
	lineSeries.dataFields.valueY = "value";
	lineSeries.dataFields.dateX = "date";
	lineSeries.name = "Sales";
	lineSeries.strokeWidth = 3;

	// Add simple bullet
	var circleBullet = lineSeries.bullets.push(new am4charts.CircleBullet());
	var labelBullet = lineSeries.bullets.push(new am4charts.LabelBullet());
	labelBullet.label.text = "{value}";

	charts[name] = chart;
	
	return chart;
}
function updateChart(name, data) {
	charts[name].dataProvider = data;
	charts[name].validateData();
}
function destroyChart(name) {
	if(name in charts) {
		charts[name].clear();
		charts[name] = null;
		delete charts[name];
	}
}
function historicalMessage(msg) {
	$("#historical-message span").html(msg);
	if(msg.length == 0) {
		$("#historical-message").hide();
		$("#historical-graph").show();
	} else {
		$("#historical-message").show();
		$("#historical-graph").hide();
	}
}
var requesting_historical = false;
function requestHistorical(query) {
	if(requesting_historical) {
		alert("Already loading, please wait!");
		return;
	}
	var sel = $("#players").val();
	var query = "";
	for(var i in sel) {
		query += sel[i] + "|";
	}
	
	if(query == historical_last_query) {
		return;
	}
	
	historicalMessage("Loading...<br>(this may take a while)");
	
	console.log("Requesting Historical for " + query);
	requesting_historical = true;
	$.ajax({
		url: "/api/historical?users=" + query,
		success: function(data) {
			destroyChart("historical-graph");
			historical_last_query = query;
			requesting_historical = false;
			
			console.log(data);

			var players_count = data.users.length;
			
			if(players_count == 0) {
				historicalMessage("No data to show.");
				return;
			} else {
				historicalMessage("");
			}
			
			var axes = [{
				"id":"mu",
				"axisColor": "#FF6600",
				"axisThickness": 2,
				"axisAlpha": 1,
				"position": "left",
				"title": "μ (mu)"
			}];
			
			var graphs = [];
			var colors = [
				"#06b37b",
				"#d1655d",
				"#637bb6",
				"#b6b063",
				"#a463b6",
				
				"#ff4747",
				"#7de062",
				"#62cde0",
				
				"#9bff38",
				"#ffce89"
			];
			colors = d3.schemeCategory10;
			
			for(var user_id of data.users) {
				var u = getUserFromId(user_id);
				var profile_image = getProfileImage(u, true);
				
				graphs.push({
					"valueAxis": "mu",
					"bullet": "round",
					"bulletBorderThickness": 1,
					"hideBulletsCount": 30,
					"title":  u.username,
					"valueField": user_id,
					"fillAlphas": 0,
					"balloonText": "<img src='" + profile_image + "' style='vertical-align:bottom; margin-right: 4px; width:16px; height:16px;'><span style='font-size:14px; color:#000000;'>" + u.username +"<b>[[value]]</b></span>",
					"customMarker": profile_image,
					"lineColor": colors[graphs.length]
				});
			}
			
			var chartData = [];
			for(var date in data.graph) {
				var entry = data.graph[date];
				entry.date = date;
				chartData.push(entry);
			}
			
			/*
			chartData.sort(function(a,b){
				return b.date - a.date;
			});
			*/
			

			createChart("historical-graph", axes, graphs, chartData, {
				title: "μ (mu) over time of " + players_count + " player" + (players_count != 1 ? 's' : '')
			});
		}
	});
}
function requestLeaderboard() {
	var api = "";
	console.log("Requesting Leaderboard");
	$.ajax({
		//url: "https://api.2018.halite.io/v1/api/leaderboard?offset=0&limit=999999",
		url: "https://halite2018.mlomb.me/api/leaderboard",
		success: function(data) {
			leaderboard = data.leaderboard;
			
			// on leaderboard updated
			total_players = leaderboard.length;
			updateRanks();
			
			
			if(first_lb_load) {
				first_lb_load = false;
				var selects = $("#players, #analyse-player, #leaderboard-player, #game-feed-players");
				selects.html('<option value=""></option>');
				for(var i in leaderboard) {
					var u = leaderboard[i];
					selects.append('<option value="' + u.user_id + '">' + u.username + '</option>');
				}
				
				resetLiveGameFeed();
				updateLiveGameFeed();
				
				
				// check #
				if(location.hash.startsWith("#analyze")) {
					$('label[for="tab-analysis"]').click();
					$("#analyse-player").val([location.hash.split(",")[1]]);
					$("#analyse-player").trigger('change');
				}
				
				selects.trigger('chosen:updated');
			}
		}
	});
}

$(function() {
	$(".generated").text(requestTime);
	$("#leaderboard-interval").val(live_leaderboard_interval / 1000);
	
	$("#players").chosen({
		disable_search_threshold: 10,
		max_selected_options: 10,
		width: "100%"
	});
	$("#game-feed-players").chosen({
		disable_search_threshold: 4,
		max_selected_options: 4,
		width: "100%"
	});
	$("#analyse-player, #analyse-version").chosen({
		width: "49%"
	});
	$("#leaderboard-player").chosen({
		width: "64%"
	});
	
	$("#leaderboard-player").change(function(a, b) {
		live_leaderboard_last_load = null;
	});
	$("#use-rank-mu").change(function() {
		live_leaderboard_last_load = null;
	});
	$("#players").change(function(a, b) {
		requestHistorical();
	});
	$("#analyse-player").change(function(a, b) {
		var p = getUserFromId($("#analyse-player").val());
		
		$("#analyse-version").html('<option value=""></option>');
               var finalsBegin = new Date("<?php  echo $finalsBegin; ?>");
               var useFinals = new Date() > finalsBegin && false;

              if(useFinals) {
                        $("#analyse-version").append('<option value="' + p.version_number + '&finals">v' + p.version_number  + '-finals</option>');
              }

		for(var i = p.version_number; i >= 0; i--) {
			$("#analyse-version").append('<option value="' + (i ? i : '') + '">' + (i ? 'v'+i : '') + '</option>');
		}

		
		$("#analysis-content").html("<center><h2>Please select a version</h2></center>");
		
		$("#analyse-version").val([p.version_number+(useFinals ? "&finals" : "")]);
		$("#analyse-version").trigger('change');
		$("#analyse-version").trigger('chosen:updated');
	});
	$("#analyse-version").change(function(a, b) {
		$("#analysis-content").html("<center><h2>Loading...<br>(this may take a while)</h2></center>");
		var forward = "";
		function findGetParameter(parameterName) {
			var result = null,
				tmp = [];
			location.search
				.substr(1)
				.split("&")
				.forEach(function (item) {
				  tmp = item.split("=");
				  if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
				});
			return result;
		}
		var mn = findGetParameter("min_nemesis_games");
		if(mn != null) {
			forward += "&min_nemesis_games=" + mn;
		}
		
		$("#analysis-content").load("/analysis" + (location.hash == '#debug' ? '2' : '') + "?user_id=" + $("#analyse-player").val() + "&version=" + $("#analyse-version").val() + forward);
	});
	
	$(".historical-picker .clear").click(function() {
		$("#players").val('');
		$("#players").trigger('chosen:updated');
		requestHistorical();
	});
	
	$(".historical-picker .top10").click(function() {
		var arr = [];
		for(var i in leaderboard) {
			if(arr.length >= 10) break;
			arr.push(leaderboard[i].user_id);
		}
		$("#players").val(arr);
		$("#players").trigger('chosen:updated');
		requestHistorical();
	});
	
	requestHistorical();
	
	// setInterval(requestLeaderboard, 30000); // NOPE NOPE NOPE
	requestLeaderboard();
});


function leaderboardRankToTier(rank) {
	if(rank / total_players <= (1 / 100)) return 5;
	if(rank / total_players <= ((1 + 5) / 100)) return 4;
	if(rank / total_players <= ((1 + 5 + 10) / 100)) return 3;
	if(rank / total_players <= ((1 + 5 + 10 + 25) / 100)) return 2;
	return 1;
}
function tierNameFromIndex(tier_index) {
	switch(tier_index) {
		default:
		case 1: return "Bronze";
		case 2: return "Silver";
		case 3: return "Gold";
		case 4: return "Platinum";
		case 5: return "Diamond";
	}
}
function leaderboardRankToTierImage(rank) {
	var tier_index = leaderboardRankToTier(rank);
	if(tier_index == 0) return "";
	var name = tierNameFromIndex(tier_index);

	return '<img class="tier" src="level-' + tier_index + '.png" alt="' + name + '" title="' + name + '">';
}
function getProfileImage(player, only_url) {
	// TODO Google Fallback
	var url = 'https://github.com/' + (player.profile_image_key == null ? player.username : player.profile_image_key) + '.png';
	if(only_url)
		return url;
	else
		return '<img class="profile-pic" src="' + url +'" alt="' + player.username + '">';
}
function formatNumber(x) {
	return x.toLocaleString();
}
function updateRanks() { // (and users)
	if(leaderboard == null) return;
	
	$(".rankof").each(function() {
		var uid = $(this).data("user-id");
		var p = getUserFromId(uid);
		$(this).html("#" + p.rank + " " + leaderboardRankToTierImage(p.rank));
	});
	
	$("*[data-user-id]").each(function() {
		var uid = $(this).data("user-id");
		var u = getUserFromId(uid);
		
		if(u != null) {
			$(this).children(".rank").html("#" + u.rank + " " + leaderboardRankToTierImage(u.rank));
			$(this).children(".profile-pic").attr('src', getProfileImage(u, true));
			$(this).children(".username").html(u.username);
		} else {
			$(this).children(".username").html("Unknown (" + uid + ")");
		}
	});
}
function getUserFromId(id) {
	id = parseInt(id);
	return leaderboard.filter(u => {
		return u.user_id === id
	})[0];
}






var game_feed_next = 0
var game_feed_interval = 5000 + 10000000; /* stop watching for new games */
var game_feed_last_game = null;
var game_feed = $("#game-feed");

function updateLiveGameFeed() {
	var limit = 30;
	var first = game_feed_last_game == null;
	
	
	
	var sel = $("#game-feed-players").val();
	var users_query = "";
	for(var u of sel)
		users_query += u + "|";
	
	
	var api = "https://halite2018.mlomb.me/api/game_feed?users=" + users_query;
	
	if(!first)
		api += "&from_game_id=" + game_feed_last_game.game_id;
	
	api += "&num_players=" + ($('#game-feed-2p').is(':checked') ? '2|' : '') + ($('#game-feed-4p').is(':checked') ? '4|' : '');
	api += "&sizes=" + ($('#game-feed-32').is(':checked') ? '32|' : '') +
					   ($('#game-feed-40').is(':checked') ? '40|' : '') +
					   ($('#game-feed-48').is(':checked') ? '48|' : '') +
					   ($('#game-feed-56').is(':checked') ? '56|' : '') +
					   ($('#game-feed-64').is(':checked') ? '64|' : '');
	
	if(first) {
		game_feed.html('<tr><td colspan="5">Loading...</tr></td>');
	}
	
	$.ajax({
		url: api,
		success: function(games) {
			games = games.games;
			if(game_feed_last_game == null) {
				// clear table
				game_feed.html("");
			}
			
			// sort because the first request must be done in desc order
			games.sort(function(a, b) {
				return a.game_id - b.game_id;
			});
			
			for(var i in games) {
				var game = games[i];
				//console.log(game);
				
				// render row
				var result_html = "";
				
				var max_production = 0;
				for(var i of game.players)
					max_production = Math.max(max_production, i.final_production);

				for(var rank = 1; rank <= 4; rank++) {
					for(var player_index in game.players) {
						var player = game.players[player_index];
						var player_user_id = player.user_id;
						var player_score = player.final_production;
						
						if(player.rank != rank) continue; // dirty way :(

						var perc = (player_score / max_production) * 100;
						var io = sel.indexOf(player_user_id);
						var col = 'rgba(0, 89, 255, 0.47)';
						if(io != -1) {
							switch(io) {
								case 0: col = 'rgba(255, 141, 0, 0.47)'; break;
								case 1: col = 'rgba(255, 141, 0, 0.43)'; break;
								case 2: col = 'rgba(255, 141, 0, 0.39)'; break;
								case 3: col = 'rgba(255, 141, 0, 0.35)'; break;
							}
						}

						var tr_style = "left, " + col + " "+ perc +"%, transparent 0%";
						tr_style = "background: -webkit-linear-gradient("+tr_style+");background: -moz-linear-gradient("+tr_style+");background: -ms-linear-gradient("+tr_style+");background: linear-gradient("+tr_style+")";
						
						var u = getUserFromId(player_user_id);

						result_html += `
							<tr style="` + tr_style + `">
							<td style="width: 15px;">` + rank + `°</td>
							<td style="min-width: 45px;">#` + player.leaderboard_rank + `</td>
							<td style="width: 100%;">` + leaderboardRankToTierImage(player.leaderboard_rank) + ` ` + getProfileImage(u) + ` ` + u.username + ` v` + player.version + `</td>
							<td style="text-align: right">` + formatNumber(player_score) + `</td>
							</tr>
						`;
						//console.log(player);
					}
				}

				var d = new Date(game.time_played);

				var row = $(`
					<tr data-game-id="` + game.game_id + `">
						<td>
							<a href="https://2018.halite.io/play?game_id=` + game.game_id + `" target="_blank">` + d.toLocaleString() + `</a><br>
							<time class="timeago" datetime="` + d.toISOString() + `"></time>
						</td>
						<td style="padding: 10px 0;"><table class="result-table">` + result_html + `</table></td>
						<td>` + game.map_size + "x" + game.map_size + `</td>
						<td>` + game.turns + `</td>
					</tr>
				`);
				
				// display row
				while(game_feed.children().length > limit)
					game_feed.children().last().remove();
				if(!first) row.hide();
				game_feed.prepend(row);
				if(!first) row.fadeIn(1000);
				game_feed_last_game = game;

				$("time.timeago").timeago();
			}
			
			setTimeout(updateLiveGameFeed, game_feed_interval);
		}
	});
}

function resetLiveGameFeed() {
	game_feed_last_game = null;
	game_feed.html('<tr><td colspan="5">Waiting next load (<5s)...</tr></td>');
}

$(function(){
	resetLiveGameFeed();
	$(".game-feed-filter :checkbox").change(resetLiveGameFeed);
	$("#game-feed-players").change(resetLiveGameFeed);
});


// Live Leaderboard
var live_leaderboard_last_load = null;
var live_leaderboard_loading = false;
var live_leaderboard_interval = 30 * 1000;
var live_leaderboard_last_data = null;
setInterval(function() {
    return; /* stop updating the leaderboard */

	live_leaderboard_interval = Math.max($("#leaderboard-interval").val(), 10) * 1000;

	if(!live_leaderboard_last_load || (new Date()).getTime() - live_leaderboard_last_load > live_leaderboard_interval) {
		if(!live_leaderboard_loading) {
			live_leaderboard_loading = true;
			refreshLiveLeaderboard();
		}
	}

	if(live_leaderboard_loading) {
		$("#leaderboard-status").html("Updating...");
	} else {
		$("#leaderboard-status").html((((live_leaderboard_interval - ((new Date()).getTime() - live_leaderboard_last_load))) / 1000.0).toFixed(2));
	}

	var finalsBegin = new Date("<?php  echo $finalsBegin; ?>");
       var finalsEnd = new Date("<?php  echo $finalsEnd; ?>");

	if(new Date() < finalsBegin) {
		$("#finals-status").html('Begins in <time class="timeago" datetime="' + finalsBegin.toISOString() + '"></time>');
	} else if(new Date() < finalsEnd) {
		$("#finals-status").html('Ends in <time class="timeago" datetime="' + finalsEnd.toISOString() + '"></time>');
	} else {
		$("#finals-status").html('Closed');
	}

	$("time.timeago").timeago();
}, 10);
function refreshLiveLeaderboard() {
	live_leaderboard_loading = true;
	$.ajax({
		url: '/api/live_leaderboard?user=' + $("#leaderboard-player").val() + "&use_mu_rank=" + ($("#use-rank-mu").is(':checked') ? 1 : 0),
		success: function(data) {
			var html = "";

			for(var entry of data.leaderboard) {
				var is_selected = entry.user_id == $("#leaderboard-player").val();
				
				var delta_mu = 0;
				var delta_sigma = 0;
				
				if(live_leaderboard_last_data) {
					var last_entry = live_leaderboard_last_data.leaderboard.filter(e => {
						return e.user_id === entry.user_id
					})[0];
					if(last_entry) {
						delta_mu = entry.mu - last_entry.mu;
						delta_sigma = entry.sigma - last_entry.sigma;
					}
				}

				delta_mu = parseFloat(delta_mu.toFixed(2));
				delta_sigma = parseFloat(delta_sigma.toFixed(2));
				
				html += `
					<tr ${is_selected ? 'style="background: #ffa5004d;"' : ''}>
						<td>#${entry.rank} ${leaderboardRankToTierImage(entry.rank)}</td>
						<td data-user-id="` + entry.user_id + `" style="text-align: left">
							<img class="profile-pic"></img>
							<span class="username"></span>
						</td>
						<td>${entry.rating.toFixed(2)}</td>
						<td><b>${entry.mu.toFixed(2)}</b>${delta_mu != 0 ? ' <span style="color:' + (delta_mu > 0 ? 'lime' : '#ff5050') + '">(' + (delta_mu > 0 ? '+' : '') + delta_mu + ')</span>' : ''}</td>
						<td><b>${entry.sigma.toFixed(2)}</b>${delta_sigma != 0 ? ' <span style="color:#a8a8ff">(' + (delta_sigma > 0 ? '+' : '') + delta_sigma + ')</span>' : ''}</td>
						<td>${entry.games.toLocaleString()}</td>
					</tr>
				`;
			}

			$("#live-leaderboard").html(html);
			updateRanks();

			live_leaderboard_last_data = data;
			live_leaderboard_last_load = new Date();
			live_leaderboard_loading = false;
		}
	});
}
		</script>
		<script>var requestTime = <?php echo timeFromStart(); ?>; // ms</script>
	</body>
</html>