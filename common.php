<?php
error_reporting(0);
include_once("DB.php");

$script_start = microtime(true);
$ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR'];
$country = (isset($_SERVER["HTTP_CF_IPCOUNTRY"]) && strlen($_SERVER["HTTP_CF_IPCOUNTRY"]) == 2) ? $_SERVER["HTTP_CF_IPCOUNTRY"] : "XX";

function timeFromStart() {
	global $script_start; return round((microtime(true) - $script_start) * 1000, 2);
}

function startAPIRequest() {
	global $data;
	$data = array();
	header('Content-Type: application/json;charset=utf-8');	
}

function endAPIRequest() {
	global $data, $script_start;
	
	if(!array_key_exists("success", $data)) {
		$data["success"] = true;
	}
	$data["time_ms"] = round((microtime(true) - $script_start) * 1000, 2);
	$data["queries"] = DB::get()->queriesCount();
	echo json_encode($data);
	die();
}

function dieAPIError($error = "Unknown Error") {
	global $data;
	
	$data = array(
		"success" => false,
		"error" => $error
	);
	endAPIRequest();
}

function formatNumber($n) {
	return number_format($n, 0, '.', ',');
}

function arrayFromParameter($input) {
	$result = array();
	$str = explode("|", $input);

	foreach($str as $item_str) {
		if(ctype_digit($item_str)) {
			$n = intval($item_str);
			if($n <= 0) continue;
			if(!in_array($n, $result))
				array_push($result, $n);
		}
	}
	
	return $result;
}

?>