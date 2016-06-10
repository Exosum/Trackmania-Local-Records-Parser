<?php

function ftpConnect($ftp_server, $ftp_user, $ftp_pass) {
	$connection = ftp_connect($ftp_server) or die("Couldn't connect to $ftp_server");
	ftp_login($connection, $ftp_user, $ftp_pass) or die("Couldn't connect as $ftp_user");
	return $connection;
}

function ftpDownload($connection, $file, $timer) {
	ftp_get($connection, $file, $file, FTP_BINARY);
	if($timer) {
		file_put_contents("timers/time_$file", ftp_mdtm($connection, $file));
	}
}

function checkModify($connection, $file) {
	$lines = file("timers/time_$file", FILE_IGNORE_NEW_LINES);
	if(ftp_mdtm($connection, $file) == $lines[0]) {
		return false;
	} else {
		return true;
	}
}

function parseMaps($file) {
	$lines = file($file, FILE_IGNORE_NEW_LINES);
	for($i = 47; $i < count($lines); $i = $i + 4) {
		$ident = substr($lines[$i], 0, strlen($lines[$i])-8);
		$ident = substr($ident, 9);
		$map_qid[] = $ident;
	}
	return $map_qid;
}

function findNickname($key) {
	$lines = file("local_players.txt");
	for($i = 3; $i < count($lines); $i++) {
		if(strstr($lines[$i], $key)) { 
			return $lines[$i];
		}
	}
}

function findMapname($UID) {
	$lines = file("$UID.txt");
	$mapname = explode("#", $lines[3]);
	return $mapname[1];
}

function convertTime($seconds) {
	$hours = floor($seconds / 3600);
	$mins = floor($seconds / 60 % 60);
	$secs = floor($seconds % 60);
	
	$format = '%02u:%02u:%02u';
    $time = sprintf($format, $hours, $mins, $secs);
    return rtrim($time, '0');
}

function convertTimems($milliseconds) {
	$seconds = floor($milliseconds / 1000);
    $minutes = floor($seconds / 60);
    $milliseconds = $milliseconds % 1000;
    $seconds = $seconds % 60;
    $minutes = $minutes % 60;

    $format = '%02u:%02u.%03u';
    $time = sprintf($format, $minutes, $seconds, $milliseconds);
    return rtrim($time, '0');
}

?>