#!/usr/bin/php -q
<?php
	header('Content-Type: text/plain; charset=utf-8');
	
	require_once('db_login.php');
	require_once('emails.php');
	$debug = isset($_GET['debug']);
	
	$date = gmdate("Y-m-d H:i:s");
	$futureDate = gmdate("Y-m-d H:i:s", time() + 96*60*60);
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
		
		$playerNameMap = array();
		$playerEmailMap = array();
		
		$timedOutList = array();
		$assassinationsMap = array();
		
		// select all playing players who have timed out
		$sql  = " SELECT assassinations.gameId, players.playerId, players.name, players.email, assassinations.victimId, assassinations.endDate ";
		$sql .= " FROM assassinations INNER JOIN players ON players.playerId = assassinations.assassinId "; 
		$sql .= " WHERE assassinations.state='PENDING' AND assassinations.endDate < '$date';";
		$result = mysql_query($sql) or sql_error_report($sql);
		$numTimedOut = mysql_num_rows($result);
		while($row = mysql_fetch_assoc($result)) {
			$playerName = $row['name'];
			$playerNameMap[$row['playerId']] = $playerName; 
			$playerEmailMap[$row['playerId']] = $row['email']; 
			trace("Active player $playerName has run out of time to make the kill");
			
			array_push($timedOutList, $row['playerId']);
			$assassinationsMap[$row['playerId']] = $row['victimId'];		
		}
		
		if($numTimedOut > 0) {
			foreach ($timedOutList as $timedOutId) {
				sendTimeOut($playerEmailMap[$timedOutId], $gameName);
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
				// If game 0 players, finish it (and send out email to every one)
				$sql  = " SELECT players.playerId, players.name AS playerName, players.email ";
				$sql .= " FROM participations INNER JOIN players ON players.playerId = participations.playerId "; 
				$sql .= " WHERE participations.gameId = '$gameId';"; 
				$result = mysql_query($sql) or sql_error_report($sql);
				while($row = mysql_fetch_assoc($result)) {
					sendGameOver($row['email'], $gameId, $gameName);
				}
				
				$sql = "UPDATE games SET state = 'FINISHED', winnerId='0', endDate='$date' WHERE gameId = '$gameId' LIMIT 1;";
				mysql_query($sql) or sql_error_report($sql);
			} else if($numPlayersLeft == 1) {
				// If game has 1 player mark game as won with the player who is left + send out game finished email
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
				
				$sql  = " SELECT players.playerId, players.name AS playerName, players.email ";
				$sql .= " FROM participations INNER JOIN players ON players.playerId = participations.playerId "; 
				$sql .= " WHERE participations.gameId = '$gameId';"; 
				$result = mysql_query($sql) or sql_error_report($sql);
				while($row = mysql_fetch_assoc($result)) {
					if($row['playerId'] == $winnerId) {
						sendGameWon($row['email'], $gameId, $gameName);
					} else {
						sendGameOver($row['email'], $gameId, $gameName, $winnerAlias, $winnerName);
					}
				}
				
				// update the winner
				$sql = "UPDATE assassinations SET state = 'FAIL' WHERE assassinationId='$winnerAssId' LIMIT 1;";
				mysql_query($sql) or sql_error_report($sql);
				
				$sql = "UPDATE participations SET state = 'WON' WHERE gameId = '$gameId' AND playerId = '$winnerId' LIMIT 1;";
				mysql_query($sql) or sql_error_report($sql);
				
				$sql = "UPDATE games SET state = 'FINISHED', winnerId='$winnerId', endDate='$date' WHERE gameId = '$gameId' LIMIT 1;";
				mysql_query($sql) or sql_error_report($sql);
			} else if($numPlayersLeft == 2) {
				// If game has 2 people left set people's time to 96 hours and send awesome deathmatch emails
				$sql  = " SELECT players.playerId, players.name, players.email, participations.alias, assassinations.assassinationId, assassinations.victimId ";
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
					$p1Email = $row['email'];
					$p1Alias = $row['alias'];
					$p1AssId = $row['assassinationId'];
					$p1VicId = $row['victimId'];
				} else {
					showError('IMPOSIBLE7');
				}
				
				if($row = mysql_fetch_assoc($result)) {
					$p2Id = $row['playerId'];
					$p2Name = $row['name'];
					$p2Email = $row['email'];
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
				
				// send emails
				sendDeathmatch($p1Email, $gameName, $p2Name);
				sendDeathmatch($p2Email, $gameName, $p1Name);
			} else {
				// We have more people so do nothing
				// re arrange the targets
				$sql  = " SELECT players.playerId, players.name, players.email, participations.alias, assassinations.assassinationId, assassinations.victimId ";
				$sql .= " FROM players INNER JOIN participations ON players.playerId = participations.playerId ";
				$sql .= " INNER JOIN assassinations ON players.playerId = assassinations.assassinId ";
				$sql .= " WHERE participations.state = 'ACTIVE' AND assassinations.state = 'PENDING' AND participations.gameId = '$gameId';";
				$playerResult = mysql_query($sql) or sql_error_report($sql);
				while($row = mysql_fetch_assoc($playerResult)) {
					$pId = $row['playerId'];
					$pEmail = $row['email'];
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
						
						$sql  = " SELECT players.name AS newVictimName, participations.alias AS newVictimAlias ";
						$sql .= " FROM players INNER JOIN participations ON participations.playerId = players.playerId ";
						$sql .= " WHERE participations.gameId = '$gameId' AND players.playerId = '$victimId' AND participations.state='ACTIVE' LIMIT 1;";
						$result = mysql_query($sql) or sql_error_report($sql);
						if($row = mysql_fetch_assoc($result)) {
							sendTargetChanged($pEmail, $gameName, $row['newVictimAlias'], $row['newVictimName']);
						}
					}
				}
			}
		}
	}
	

	
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
		
		$date = gmdate("Y-m-d H:i:s");
		$sqlError = mysql_error();
		mysql_query(sprintf("INSERT INTO errors (type, error, extra, date) VALUES ('SQL', '%s', 'make_game_deamon:%s', '%s');", addslashes($sqlError), addslashes($sql), $date));
		die($sqlError . "\n" . $sql);
	}
	
	/*
	 * go through all the active games (have number of currently active players)
  count number of timed out players
    (know how many will be left)
    elimnate all the timed out players (update players, participations and fail assassinations, keep assassinations as a map)
    
    
    
    
    eliominate them and send email + record games where palyer timed out
   
go through all the active games where a player has timed out
  if game 0 palyers 
    finish it (and send out email to every one
  
  if game has 1 palyer
    mark game as won with the palyer who is left + send ot game finished email
    
  if game has 2 people left
    set people's time to 96 hours and send awesome deathmatch emails
    
  if game has 3 players left then do nothing 
	*/
?>