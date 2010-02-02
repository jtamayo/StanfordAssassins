<?php
	$MIN_PALYERS_FOR_GAME = 3;

	header('Content-Type: text/plain; charset=utf-8');
	
	require_once('db_login.php');
	$debug = true;
	
	// select all pending games
	$sql = "SELECT gameId, name FROM games WHERE state='PENDING'";
	$result = mysql_query($sql) or sql_error_report($sql);
	if($row = mysql_fetch_assoc($result)) {
		$gameId = $row['gameId'];
		$name = $row['name'];
		trace("Checking $name");
		
		// Find the players that are waiting for a game.
		$sql = "SELECT playerId FROM players WHERE state='WAITING'";
		$result = mysql_query($sql) or sql_error_report($sql);
		$numPlayers = mysql_num_rows($result);
		if($MIN_PALYERS_FOR_GAME <= $numPlayers) {
			trace("Starting game with $numPlayers players");
			$players = array();
			while($row = mysql_fetch_assoc($result)) {
				array_push($players, intval($row['playerId']));
			}
			startGame($gameId, $name, $players);
		} else {
			trace("Not enough players (got $numPlayers players)");
		}
	} else {
		trace("No game");
	}
	
	function startGame($gameId, $gameName, $players) {
		$playerNumber = count($players);
		if($playerNumber < 3) return;
		require_once('emails.php');
		
		$date = gmdate("Y-m-d H:i:s");
		$dateLimit = gmdate("Y-m-d H:i:s", time()+96*60*60);
		$codewords = array('age','air','anger','animal','answer','apple','area','arm','art','atom','baby','back','ball','band','bank','bar','base','bat','bear','beauty','bell','bird','bit','block','blood','blow','board','boat','body','bone','book','bottom','box','boy','branch','bread','break','brother','call','camp','capital','captain','car');
		shuffle($codewords);
		
		// Start the game
		$sql = "UPDATE games SET state='ACTIVE', startDate='$date' WHERE gameId='$gameId' LIMIT 1;";
		mysql_query($sql) or sql_error_report($sql);
		
		// Randomize the players
		shuffle($players);
		$playerNameMap = array();
		$playerEmailMap = array();
		$playerAliasMap = array();
		$playerCodewordMap = array();
		
		$playerList = join(',', $players);
		$participationList = '';
		$sql = "SELECT playerId, name, email, waitingAlias FROM players WHERE state='WAITING' AND playerId IN ($playerList);";
		$result = mysql_query($sql) or sql_error_report($sql);
		while($row = mysql_fetch_assoc($result)) {
			$playerId = $row['playerId'];
			$playerNameMap[$playerId] = addslashes($row['name']);
			$playerEmailMap[$playerId] = addslashes($row['email']);
			$playerAliasMap[$playerId] = addslashes($row['waitingAlias']);
			$playerCodewordMap[$playerId] = addslashes(array_pop($codewords));
			
			if($participationList != '') $participationList .= ', ';
			$participationList .= "('$gameId', '$playerId', '$playerAliasMap[$playerId]', '$playerCodewordMap[$playerId]')";
		}
		
		$sql = "INSERT INTO participations (gameId, playerId, alias, codeword) VALUES $participationList;";
		mysql_query($sql) or sql_error_report($sql);
		
		// Update players in DB
		$sql = "UPDATE players SET state='ACTIVE', waitingAlias='', waitingStart='0000-00-00 00:00:00' WHERE playerId IN ($playerList);";
		mysql_query($sql) or sql_error_report($sql);
		
		$assassinationList = '';
		for($i = 0; $i < $playerNumber; $i++) {
			$playerId = $players[$i];
			$victimId = $players[($i+1)%$playerNumber];
			
			trace("Sending email to $playerNameMap[$playerId]");
			sendGameStarted($playerEmailMap[$playerId], $gameId, $gameName, $playerCodewordMap[$playerId], $playerAliasMap[$victimId], $playerNameMap[$victimId]);
			
			if($assassinationList != '') $assassinationList .= ', ';
			$assassinationList .= "('$gameId', '$playerId', '$victimId', 'PENDING', '$date', '$dateLimit')";
		}
		
		$sql = "INSERT INTO assassinations (gameId, assassinId, victimId, state, startDate, endDate) VALUES $assassinationList;";
		mysql_query($sql) or sql_error_report($sql);
		
		$sql = "UPDATE players SET waitingAlias='', waitingStart='0000-00-00 00:00:00', state='PLAYING' WHERE playerId IN ($playerList);";
		mysql_query($sql) or sql_error_report($sql);
	}
	
	function trace($str) {
		global $debug;
		if($debug) echo "$str\n";
	}
	
	function sql_error_report($sql) {
		require_once('db_login.php');
		
		$date = gmdate("Y-m-d H:i:s");
		$sqlError = mysql_error();
		mysql_query(sprintf("INSERT INTO errors (type, error, extra, date) VALUES ('SQL', '%s', 'make_game_deamon:%s', '%s');", addslashes($sqlError), addslashes($sql), $date));
		die($sqlError . "\n" . $sql);
	}
?>