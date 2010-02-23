#!/usr/bin/php -q
<?php
	header('Content-Type: text/plain; charset=utf-8');
	
	require_once('common.php');
	require_once('db_login.php');
	$debug = isset($_GET['debug']);
	
	$date = getDate();
	$futureDate = getDateLimit();
	trace("Running game manager..."); 
	
	// go through each active game + count number of active players
	$sql  = " SELECT games.gameId, games.name, ";
	$sql .= " (SELECT COUNT(*) FROM participations WHERE participations.gameId = games.gameId AND participations.state = 'ACTIVE') AS numPlayers ";
	$sql .= " FROM games WHERE games.state='ACTIVE';";
	$gameResult = mysql_query($sql) or sql_error_report($sql);
	while($row = mysql_fetch_assoc($gameResult)) {
		$gameId = $row['gameId'];
		$gameName = $row['name'];
		$numPlayers = intval($row['numPlayers']);
		trace("Managing $gameName...");
		
		$timedOutList = array();
		$assassinationsMap = array();
		
		// select all playing players who have timed out
		$sql  = " SELECT assassinations.gameId, players.playerId, players.name, assassinations.victimId, assassinations.endDate ";
		$sql .= " FROM assassinations INNER JOIN players ON players.playerId = assassinations.assassinId "; 
		$sql .= " WHERE assassinations.state='PENDING' AND assassinations.endDate < '$date';";
		$result = mysql_query($sql) or sql_error_report($sql);
		$numTimedOut = mysql_num_rows($result);
		while($row = mysql_fetch_assoc($result)) {
			$playerName = $row['name'];
			trace("Active player $playerName has run out of time to make the kill");
			
			array_push($timedOutList, $row['playerId']);
			$assassinationsMap[$row['playerId']] = $row['victimId'];		
		}
		
		if($numTimedOut > 0) {
			require_once('handler.php');
			
			foreach ($timedOutList as $timedOutId) {
				handleTimeOut($timedOutId);
			}
			
			// eliminate players
			$timedOutListStr =  implode(',', $timedOutList);
			
			$sql = "UPDATE players SET state = 'NOTHING' WHERE playerId IN ($timedOutListStr);";
			mysql_query($sql) or sql_error_report($sql);
			if($numTimedOut != mysql_affected_rows()) {
				showError('IMPOSIBLE1');
			}
			
			$sql = "UPDATE participations SET state = 'KICKED' WHERE gameId = '$gameId' AND playerId IN ($timedOutListStr);";
			mysql_query($sql) or sql_error_report($sql);
			if($numTimedOut != mysql_affected_rows()) {
				showError('IMPOSIBLE2');
			}
			
			$sql = "UPDATE assassinations SET state = 'KICKED' WHERE state='PENDING' AND gameId = '$gameId' AND assassinId IN ($timedOutListStr);";
			mysql_query($sql) or sql_error_report($sql);
			if($numTimedOut != mysql_affected_rows()) {
				showError('IMPOSIBLE3');
			}
			
			// how many players are left in the game?
			$numPlayersLeft = $numPlayers - $numTimedOut;
			if($numPlayersLeft == 0) {
				// If game 0 players, finish it
				$sql = "UPDATE games SET state = 'FINISHED', winnerId='0', endDate='$date' WHERE gameId = '$gameId' LIMIT 1;";
				mysql_query($sql) or sql_error_report($sql);
				
				handleGameOver($gameId);
			} else if($numPlayersLeft == 1) {
				// If game has 1 player mark game as won with the player who is left
				// find the winner's name and alias
				$sql  = " SELECT players.playerId, players.name, participations.alias, assassinations.assassinationId, assassinations.victimId ";
				$sql .= " FROM players INNER JOIN participations ON players.playerId = participations.playerId ";
				$sql .= " INNER JOIN assassinations ON players.playerId = assassinations.assassinId ";
				$sql .= " WHERE participations.state = 'ACTIVE' AND assassinations.state = 'PENDING' AND participations.gameId = '$gameId';";
				$result = mysql_query($sql) or sql_error_report($sql);
				if(mysql_num_rows($result) != 1) {
					showError('IMPOSIBLE4');
				}
				
				if($row = mysql_fetch_assoc($result)) {
					$winnerId = $row['playerId'];
					$winnerName = $row['name'];
					$winnerAlias = $row['alias'];
					$winnerAssId = $row['assassinationId'];
				} else {
					showError('IMPOSIBLE5');
				}
				
				// update the winner
				$sql = "UPDATE assassinations SET state = 'FAIL' WHERE assassinationId='$winnerAssId' LIMIT 1;";
				mysql_query($sql) or sql_error_report($sql);
				
				$sql = "UPDATE participations SET state = 'WON' WHERE gameId = '$gameId' AND playerId = '$winnerId' LIMIT 1;";
				mysql_query($sql) or sql_error_report($sql);
				
				$sql = "UPDATE games SET state = 'FINISHED', winnerId='$winnerId', endDate='$date' WHERE gameId = '$gameId' LIMIT 1;";
				mysql_query($sql) or sql_error_report($sql);
				
				handleGameOver($gameId);
			} else if($numPlayersLeft == 2) {
				// If game has 2 people left set people's time to 96 hours
				$sql  = " SELECT players.playerId, players.name, participations.alias, assassinations.assassinationId, assassinations.victimId ";
				$sql .= " FROM players INNER JOIN participations ON players.playerId = participations.playerId ";
				$sql .= " INNER JOIN assassinations ON players.playerId = assassinations.assassinId ";
				$sql .= " WHERE participations.state = 'ACTIVE' AND assassinations.state = 'PENDING' AND participations.gameId = '$gameId';";
				$result = mysql_query($sql) or sql_error_report($sql);
				if(mysql_num_rows($result) != 2) {
					showError('IMPOSIBLE6');
				}
				
				if($row = mysql_fetch_assoc($result)) {
					$p1Id = $row['playerId'];
					$p1Name = $row['name'];
					$p1Alias = $row['alias'];
					$p1AssId = $row['assassinationId'];
					$p1VicId = $row['victimId'];
				} else {
					showError('IMPOSIBLE7');
				}
				
				if($row = mysql_fetch_assoc($result)) {
					$p2Id = $row['playerId'];
					$p2Name = $row['name'];
					$p2Alias = $row['alias'];
					$p2AssId = $row['assassinationId'];
					$p2VicId = $row['victimId'];
				} else {
					showError('IMPOSIBLE8');
				}
				
				// re arrange the targets
				if($p1VicId == $p2Id) {
					$sql = "UPDATE assassinations SET endDate = '$futureDate' WHERE assassinationId = '$p1AssId' LIMIT 1;";
					mysql_query($sql) or sql_error_report($sql);
				} else {
					$sql = "UPDATE assassinations SET state = 'FAIL' WHERE assassinationId = '$p1AssId' LIMIT 1;";
					mysql_query($sql) or sql_error_report($sql);
					
					$sql = "INSERT INTO assassinations (gameId, assassinId, victimId, state, startDate, endDate) VALUES ('$gameId', '$p1Id', '$p2Id', 'PENDING', '$date', '$futureDate');";
					mysql_query($sql) or sql_error_report($sql);
				}
				
				if($p2VicId == $p1Id) {
					$sql = "UPDATE assassinations SET endDate = '$futureDate' WHERE assassinationId = '$p2AssId' LIMIT 1;";
					mysql_query($sql) or sql_error_report($sql);
				} else {
					$sql = "UPDATE assassinations SET state = 'FAIL' WHERE assassinationId = '$p2AssId' LIMIT 1;";
					mysql_query($sql) or sql_error_report($sql);
					
					$sql = "INSERT INTO assassinations (gameId, assassinId, victimId, state, startDate, endDate) VALUES ('$gameId', '$p2Id', '$p1Id', 'PENDING', '$date', '$futureDate');";
					mysql_query($sql) or sql_error_report($sql);
				}
				
				// handle
				handleDeathmatch($gameId);
			} else {
				// We have more people so do nothing
				// re arrange the targets
				$sql  = " SELECT players.playerId, players.name, participations.alias, assassinations.assassinationId, assassinations.victimId ";
				$sql .= " FROM players INNER JOIN participations ON players.playerId = participations.playerId ";
				$sql .= " INNER JOIN assassinations ON players.playerId = assassinations.assassinId ";
				$sql .= " WHERE participations.state = 'ACTIVE' AND assassinations.state = 'PENDING' AND participations.gameId = '$gameId';";
				$playerResult = mysql_query($sql) or sql_error_report($sql);
				while($row = mysql_fetch_assoc($playerResult)) {
					$pId = $row['playerId'];
					$pAssId = $row['assassinationId'];
					
					$numSkiped = 0;
					$victimId = $row['victimId'];
					while(in_array($victimId, $timedOutList)) {
						$victimId = $assassinationsMap[$victimId];
						$numSkiped++;
					}
					
					if($numSkiped > 0) {
						$sql = "UPDATE assassinations SET state = 'FAIL' WHERE assassinationId = '$pAssId' LIMIT 1;";
						mysql_query($sql) or sql_error_report($sql);
					
						$sql = "INSERT INTO assassinations (gameId, assassinId, victimId, state, startDate, endDate) VALUES ('$gameId', '$pId', '$victimId', 'PENDING', '$date', '$futureDate');";
						mysql_query($sql) or sql_error_report($sql);
						
						handleTargetChanged($pId, $gameId);
					}
				}
			}
		}
	}
	
	trace("Details timeout manager...");
	$sql = "SELECT assassinationId FROM assassinations WHERE state = 'SUCCESS' AND detailsState='NONE' AND endDate < DATE_SUB('$date', INTERVAL 2 MINUTE);";
	$result = mysql_query($sql) or sql_error_report($sql);
	while($row = mysql_fetch_assoc($result)) {
		require_once('handler.php');
		
		$assassinationId = $row['assassinationId'];
		$sql = "UPDATE assassinations SET detailsState='TIMEOUT' WHERE assassinationId='$assassinationId' LIMIT 1;";
		mysql_query($sql) or sql_error_report($sql);
		
		handleDetailsTimeout($assassinationId);
	}
	
	/////////////////////////////////////////////////////////////////
	
	function trace($str) {
		global $debug;
		if($debug) echo "$str\n";
	}
	
	function showError($str) {
		global $debug;
		if($debug) {
			echo "ERROR: $str\n";
		} else {
			$stderr = fopen('php://stderr', 'w');
			fwrite($stderr, "ERROR: $str\n");
			fclose($stderr);
		}
	}
	
	function sql_error_report($sql) {
		require_once('db_login.php');
		
		$date = getDate();
		$sqlError = mysql_error();
		mysql_query(sprintf("INSERT INTO errors (type, error, extra, date) VALUES ('SQL', '%s', 'make_game_deamon:%s', '%s');", addslashes($sqlError), addslashes($sql), $date));
		die($sqlError . "\n" . $sql);
	}
?>