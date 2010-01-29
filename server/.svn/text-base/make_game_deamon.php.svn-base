<?php
	header('Content-Type: text/plain; charset=utf-8');
	
	require_once('db_login.php');
	$debug = true;
	
	// select all pending games
	$sql = "SELECT gameId, name FROM games WHERE state='PENDING'";
	$result = mysql_query($sql) or sql_error_report($sql);
	while($row = mysql_fetch_assoc($result)) {
		$gameId = $row['gameId'];
		$name = $row['name'];
		if($debug) echo "Checking $name\n";
		
		
	}
?>