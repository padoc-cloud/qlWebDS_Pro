<?php

$dataFile = "users_online.txt";

$sessionTime = USERS_ONLINE_SESSION_TIME; 

error_reporting(E_ERROR | E_PARSE);

if(!file_exists($dataFile)) {
	$fp = fopen($dataFile, "w+");
	fclose($fp);
}

$ip = $_SERVER['REMOTE_ADDR'];
$users = array();
$onusers = array();

// getting
$fp = fopen($dataFile, "r");
flock($fp, LOCK_SH);
while(!feof($fp)) {
	$users[] = rtrim(fgets($fp, 32));
}
flock($fp, LOCK_UN);
fclose($fp);

// cleaning
$x = 0;
$alreadyIn = FALSE;
foreach($users as $key => $data) {
	list( , $lastvisit) = explode("|", $data);
	if(time() - $lastvisit >= $sessionTime * 60) {
		$users[$x] = "";
	} else {
		if(strpos($data, $ip) !== FALSE) {
			$alreadyIn = TRUE;
			$users[$x] = "$ip|" . time(); // updating
		}
	}
	$x++;
}

if($alreadyIn == FALSE) {
	$users[] = "$ip|" . time();
}

// writing
$fp = fopen($dataFile, "w+");
flock($fp, LOCK_EX);
$i = 0;
foreach($users as $single) {
	if($single != "") {
		fwrite($fp, $single . "\r\n");
		$i++;
	}
}
flock($fp, LOCK_UN);
fclose($fp);

$users_online = $i;

?>
