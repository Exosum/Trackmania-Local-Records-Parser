<?php
	require 'settings.php';
	require 'functions.php';
	require 'main.php';
	require 'tmfcolorparser.php';
	$color_parser = new TMFColorParser('#ffffff');
?>
<!DOCTYPE html>
<html lang="sk">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="css/normalize.css">
		<link rel="stylesheet" href="css/skeleton.css">
		<title>Prehľad serverov</title>
	</head>
	
	<body>
		<div class="container">
		<div class="row">
		<h1 style="text-align:center">Prehľad lokálnych rekordov</h1>		
<?php
		foreach($map_qid as $map_id) {
			$lines = file("rec_$map_id.txt", FILE_IGNORE_NEW_LINES);
			for($i = 3; $i < count($lines); $i++) {
				$lines[$i] = substr($lines[$i], 28, 99);
				$nickname = substr($lines[$i], 0, strpos($lines[$i], "#", 0));
				$lines[$i] = substr($lines[$i], strpos($lines[$i], "#", 0)+1, 99);
				$recinfo[$nickname] = explode("#", $lines[$i]);
				unset($map);
			} 
?>
		<table> 
			<thead>
				<tr>
					<th>Mapa</th>
					<th>Nickname</th>
					<th>PB</th>
					<th>Dátum zájdenia PB</th>
					<th>Počet finishov mapy</th>
					<th>Naposledy na servery</th>
					<th>Čas na servery</th>
				</tr>
			</thead>
			<tbody>
<?php foreach($recinfo as $key => $row) {
?>
				<tr>
<?php
				if(!isset($map)) {
					$map = $color_parser->toHTML(findMapname($map_id));
				}
?>
					<td><?php echo $map; ?></td>
<?php
				if(!array_key_exists($key, $getN)) {
					$get_nickname = findNickname($key);
					$getN[$key] = explode("#", $get_nickname);
				}
?>
					<td><?php echo $color_parser->toHTML($getN[$key][1]); ?></td>
					<td><?php echo convertTimems($row[0]); ?></td>
<?php 
				$format = 'd.m.Y-H:i:s';
				$date = DateTime::createFromFormat($format, $row[1]);
?>
					<td><?php echo $date->format('Y.m.d H:i:s'); ?></td>
					<td><?php echo $row[2];?></td>
<?php 
				$format = 'd.m.Y-H:i:s';
				$date = DateTime::createFromFormat($format, $getN[$key][4]);			
?>
					<td><?php echo $date->format('Y.m.d H:i:s'); ?></td>
					<td><?php echo convertTime($getN[$key][3]);?></td>
				</tr>
<?php
				unset($recinfo);
			}
		} ?>
			</tbody>
		</table></div></div>
	</body>
</html>