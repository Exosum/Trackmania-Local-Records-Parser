<?php
	$connection = ftpConnect($ftp_server, $ftp_username, $ftp_password);

	if(!file_exists("timers")) {
		mkdir("timers");
	}

	ftp_chdir($connection, $matchsettings_directory) or die("Couldn't change directory");

	if(file_exists($matchsettings_filename)) {
		if(checkModify($connection, $matchsettings_filename)) {
			ftpDownload($connection, $matchsettings_filename, true);
		}
	} else {
		ftpDownload($connection, $matchsettings_filename, true);
	}
	
	$map_qid = parseMaps($matchsettings_filename);

	ftp_chdir($connection, "~");
	$records_directory = "$fast_directory/data/records";
	ftp_chdir($connection, $records_directory) or die("Couldn't change directory");

	foreach($map_qid as $map_id) {
		$record_file = "rec_$map_id.txt";
		if(file_exists($record_file)) {
		if(checkModify($connection, $record_file)) {
			ftpDownload($connection, $record_file, true);
		}
	} else {
		ftpDownload($connection, $record_file, true);
		}
	}

	ftp_chdir($connection, "~");
	$challenges_directory = "$fast_directory/data/challenges";
	ftp_chdir($connection, $challenges_directory) or die("Couldn't change directory");

	foreach($map_qid as $map_id) {
		$map_file = "$map_id.txt";
		if(!file_exists($map_file)) {
			ftpDownload($connection, $map_file, false);
		}
	}

	ftp_chdir($connection, "~");
	$players_file = "local_players.txt";
	$players_directory = "$fast_directory/data/players";

	ftp_chdir($connection, $players_directory) or die("Couldn't change directory");
	if(file_exists($players_file)) {
		if(checkModify($connection, $players_file)) {
			ftpDownload($connection, $players_file, true);
		}
	} else {
		ftpDownload($connection, $players_file, true);
	}

	ftp_close($connection);
	$getN[] = "";
?>