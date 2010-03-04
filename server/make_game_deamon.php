#!/usr/bin/php
<?php
	$MIN_PLAYERS_FOR_GAME = 4;

	header('Content-Type: text/plain; charset=utf-8');
	
	require_once('common.php');
	require_once('db_login.php');
	$debug = true;
	
	$date = getDateNow();	
	
	$tokenPlayers = array();
	$playerNameMap = array();
	$playerAliasMap = array();
	
	$sql = "SELECT playerId, name, waitingAlias, tokens FROM players WHERE state='WAITING';";
	$result = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
	while($row = mysql_fetch_assoc($result)) {
		$playerId = $row['playerId'];
		$playerNameMap[$playerId] = addslashes($row['name']);
		$playerAliasMap[$playerId] = addslashes($row['waitingAlias']);
		$tokens = explode(';', $row['tokens']);
		foreach($tokens as $token) {
			if(isset($tokenPlayers[$token])) {
				//trace('+' . $row['playerId']);
				array_push($tokenPlayers[$token], $playerId);
			} else {
				//trace('!' . $row['playerId']);
				$tokenPlayers[$token] = array($playerId);
			}
		}
	}
	
	//print_r($playerAliasMap);
	//exit;
	
	$tokenToStart = '';
	$maxCount = $MIN_PLAYERS_FOR_GAME;
	foreach($tokenPlayers as $token => $players) {
		$count = count($players);
		if($maxCount <= $count) {
			$maxCount = $count;
			$tokenToStart = $token;
		}
	}
	
	if($tokenToStart != '') {		
		// make game
		$gameName = makeGameName();
		
		trace("Pending game $gameName");
		
		$sql  = "INSERT INTO games (name, state, startDate, endDate, winnerId, timeoutHours, wantedHours, token) VALUES ";
		$sql .= "('$gameName', 'PENDING', DATE_ADD('$date', INTERVAL 24 HOUR), '0000-00-00 00:00:00', '0', '144', '72', '$tokenToStart');";
		mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		
		$sql = "SELECT gameId FROM games WHERE name = '$gameName' LIMIT 1;";
		$result = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		$row = mysql_fetch_assoc($result);
		$gameId = $row['gameId'];
		
		$codewords = codewords();
		shuffle($codewords);
		
		$participationList = '';
		$players = $tokenPlayers[$tokenToStart];
		foreach($players as $playerId) {
			if($participationList != '') $participationList .= ', ';
			$codeword = array_pop($codewords);
			$participationList .= "('$gameId', '$playerId', 'ACTIVE', '$playerAliasMap[$playerId]', '$codeword')";
			trace("Adding player $playerNameMap[$playerId]");
		}
		
		$sql = "INSERT INTO participations (gameId, playerId, state, alias, codeword) VALUES $participationList;";
		mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		
		// Update players in DB
		$playerList = join(',', $players);
		$sql = "UPDATE players SET state='PLAYING', waitingAlias='', waitingStart='0000-00-00 00:00:00' WHERE playerId IN ($playerList);";
		mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		
		require_once('handler.php');
		
		handleGamePending($gameId);
	} else {
		trace("Nothing to do.");
	}
	
	trace("Start games that need to be started");
	// select all pending games
	$sql = "SELECT gameId, name FROM games WHERE state='PENDING' AND startDate < '$date';";
	$result = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
	while($row = mysql_fetch_assoc($result)) {
		$gameId = $row['gameId'];
		$name = $row['name'];
		trace("Checking $name");
		
		$players = array();
		
		// Find the players that are waiting for a game.
		$sql = "SELECT playerId FROM participations WHERE gameId='$gameId'";
		$result2 = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		while($row = mysql_fetch_assoc($result2)) {
			array_push($players, intval($row['playerId']));
		}
		startGame($gameId, $name, $players);
	}
	
	function startGame($gameId, $gameName, $players) {
		$playerNumber = count($players);
		if($playerNumber < 3) return;
				
		$date = getDateNow();
		$dateLimit = getDateLimit();
		
		// Start the game
		$sql = "UPDATE games SET state='ACTIVE' WHERE gameId='$gameId' LIMIT 1;";
		mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		
		// Randomize the players
		shuffle($players);
		
		$assassinationList = '';
		for($i = 0; $i < $playerNumber; $i++) {
			$playerId = $players[$i];
			$victimId = $players[($i+1)%$playerNumber];
			
			trace("Starting: $playerId -> $victimId");
			
			if($assassinationList != '') $assassinationList .= ', ';
			$assassinationList .= "('$gameId', '$playerId', '$victimId', 'PENDING', '$date', '$dateLimit')";
		}
		
		$sql = "INSERT INTO assassinations (gameId, assassinId, victimId, state, startDate, endDate) VALUES $assassinationList;";
		mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		
		require_once('handler.php');
		
		handleGameStart($gameId);
	}
	
	function trace($str) {
		global $debug;
		if($debug) echo "$str\n";
	}
	
	function makeGameName() {
		$sql = "Select COUNT(*) AS c from gameNameNoun";
		$result = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		$row = mysql_fetch_assoc($result);
		$numNouns = intval($row['c']);
		
		$sql = "Select COUNT(*) AS c from gameNameAdj";
		$result = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		$row = mysql_fetch_assoc($result);
		$numAdjs = intval($row['c']);
		
		do {
			$random = rand(0, $numNouns-1 ); 
			$sql = "Select word from gameNameNoun LIMIT 1 OFFSET %s";
			$sql = sprintf($sql, $random );
		
			$result = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
			$row = mysql_fetch_assoc($result) ;
			$noun = $row['word'];
			
			$random = rand(0, $numAdjs-1 ); 
			$sql = "Select word from gameNameAdj LIMIT 1 OFFSET %s";
			$sql = sprintf($sql, $random );
		
			$result = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
			$row = mysql_fetch_assoc($result) ;
			$adjective = $row['word'];
			
			$gameName = "Operation $adjective $noun";
			$sql = "SELECT * FROM games WHERE name = '$gameName' LIMIT 1;";
			$result = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		} while(mysql_num_rows($result) > 0);
		 
		return $gameName ;
	}
?>