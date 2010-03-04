<?php
	//header('Content-Type: text/plain; charset=utf-8');
	header('Content-Type: application/json; charset=utf-8');
	
	require_once('common.php');
	
	$invalidArg = false; // assume that we are valid
	
	session_start();
	if(isset($_SESSION['playerId'])) {
		$playerId = $_SESSION['playerId'];
	} else {
		$debugPlayer = arg('debug');
		if($debugPlayer === false || 10 <= $debugPlayer) {
			$ret = array("status" => "BAD_AUTH");
			die(json_encode($ret));
		} else {
			$_SESSION['playerId'] = $debugPlayer;
			$playerId = $debugPlayer;
		}
	}
	
	require_once('db_login.php');
	
	switch(arg('cmd')) {
	case false:
		$invalidArg = 'cmd';
		break;
		
	case 'allData':
		if($invalidArg === false) {
			allData($playerId);
		}
		break;
		
	case 'joinGame':
		if($invalidArg === false) {
			$alias = arg('alias');
			if($alias === false) $invalidArg = 'alias';
		}
		if($invalidArg === false) {
			$tokens = arg('tokens');
			if($tokens === false) $invalidArg = 'tokens';
		}
		if($invalidArg === false) {
			joinGame($playerId, $alias, $tokens);
		}
		break;
		
	case 'reportAssassination':
		if($invalidArg === false) {
			$gameId = arg('gameId');
			if($gameId === false) $invalidArg = 'gameId';
		}
		if($invalidArg === false) {
			$codeword = arg('codeword');
			if($codeword === false) $invalidArg = 'codeword';
		}
		if($invalidArg === false) {
			reportAssassination($playerId, $gameId, $codeword);
		}
		break;
		
	case 'reportLike':
		if($invalidArg === false) {
			$assassinationId = arg('assassinationId');
			if($assassinationId === false) $invalidArg = 'assassinationId';
		}
		if($invalidArg === false) {
			reportLike($playerId, $assassinationId);
		}
		break;
		
	case 'addDetails':
		if($invalidArg === false) {
			$assassinationId = arg('assassinationId');
			if($assassinationId === false) $invalidArg = 'assassinationId';
		}
		if($invalidArg === false) {
			$details = arg('details');
			if($details === false) $invalidArg = 'details';
		}
		if($invalidArg === false) {
			addDetails($playerId, $assassinationId, $details);
		}
		break;
		
	case 'startDispute':
		if($invalidArg === false) {
			$gameId = arg('gameId');
			if($gameId === false) $invalidArg = 'gameId';
		}
		if($invalidArg === false) {
			$against = arg('against');
			if($against === false) $invalidArg = 'against';
		}
		if($invalidArg === false) {
			$description = arg('description');
			if($description === false) $invalidArg = 'description';
		}
		if($invalidArg === false) {
			startDispute($playerId, $gameId, $against, $description);
		}
		break;	
		
	case 'getLeaderboard':
		if($invalidArg === false) {
			getLeaderBoard();
		}
		break;

	case 'getPlayerStats':
		if($invalidArg === false) {
			getPlayerStats($playerId);
		}
		break;
		
	case 'reportError':
		if($invalidArg === false) {
			$error = arg('error');
			if($error === false) $invalidArg = 'error';
		}
		if($invalidArg === false) {
			reportError($playerId, $error);
		}
		break;		
		
	case 'info':
		phpinfo();
		break;
		
	case 'testEmail':
		require_once("emails.php");
		testEmail();
		break;
		
	default:
		$invalidArg = 'cmd';
		break;
	}
	
	if($invalidArg !== false) {
		$ret = array(
			"status" => "INVALID_ARG",
			"arg" => $invalidArg
		);
		
		print json_encode($ret);
	}

	function getPlayerStats($playerId) {
		$playerStats = array();
		
		$sql2 ="SELECT gameAssassinations.playerId, gameAssassinations.name, MAX(killsPerGame) as killingSpree FROM
(SELECT players.playerId, players.name, gamesInquery.gameId, COUNT(gamesInquery.gameId) as killsPerGame
FROM players LEFT JOIN (SELECT * FROM assassinations WHERE assassinations.state = 'SUCCESS') as assassinInquery on assassinInquery.assassinId = players.playerId 
LEFT JOIN (SELECT * FROM games WHERE games.state = 'FINISHED') as gamesInquery ON assassinInquery.gameId = gamesInquery.gameId 
GROUP BY players.playerId, players.name, gamesInquery.gameId) as gameAssassinations WHERE gameAssassinations.playerId =  '$playerId' 
GROUP BY gameAssassinations.playerId, gameAssassinations.name" ;

		$sql  = "SELECT players.playerId, players.name, ";
		$sql .= "(SELECT COUNT(*) FROM assassinations INNER JOIN games ON assassinations.gameId = games.gameId WHERE assassinations.assassinId = players.playerId AND assassinations.state = 'SUCCESS' AND games.state = 'FINISHED') AS assassinationsCompleted, ";
		$sql .= "(SELECT COUNT(*) FROM assassinations WHERE assassinations.victimId = players.playerId AND assassinations.state = 'SUCCESS') AS assassinated, ";
		$sql .= "(SELECT COUNT(*) FROM participations WHERE participations.playerId = players.playerId AND state != 'ACTIVE') AS gamesPlayed, ";
		$sql .= "(SELECT COUNT(*) FROM participations WHERE participations.playerId = players.playerId AND state = 'WON') AS gamesWon, ";
		$sql .= "(SELECT COUNT(likeId) FROM assassinations INNER JOIN likes ON assassinations.assassinationId = likes.assassinationId INNER JOIN games ON assassinations.gameId = games.gameId  WHERE assassinations.assassinId = 16 AND games.state = 'FINISHED' ) as  assassinationsLiked ";
		$sql .= "FROM players " . "WHERE players.playerId = '$playerId' " . "LIMIT 1000;";
		$result = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);

		while($row = mysql_fetch_assoc($result)) {
		
			$result2 = mysql_query($sql2) or sql_error_report($sql2, $_SERVER["SCRIPT_NAME"]);
			
			while($row2 = mysql_fetch_assoc($result2)) {
			
				$playerStat = array(
					"playerId" => $row['playerId'],
					"name" => $row['name'],
					"assassinations" => $row['assassinationsCompleted'],
					"assassinated" => $row['assassinated'],
					"gamesPlayed" => $row['gamesPlayed'],
					"gamesWon" => $row['gamesWon'],
					"killingSpree" => $row2['killingSpree'],
					"likes" => '0' //$row2['assassinationsLiked']
				);
			
				$ret = array(
					"status" => "OK",
					"playerStats" => $playerStat
				);
				print json_encode($ret);
				return;
			}
		}
		
		$ret = array(
			"status" => "NO_PLAYER"
		);
		print json_encode($ret);
	}

	function getLeaderBoard() {
		$playerStats = array();
		
		$sql  = "SELECT players.playerId, players.name, ";
		$sql .= "(SELECT COUNT(*) FROM assassinations INNER JOIN games ON assassinations.gameId = games.gameId WHERE assassinations.assassinId = players.playerId AND assassinations.state = 'SUCCESS' AND games.state = 'FINISHED') AS assassinationsCompleted, ";
		$sql .= "(SELECT COUNT(*) FROM assassinations WHERE assassinations.victimId = players.playerId AND assassinations.state = 'SUCCESS') AS assassinated, ";
		$sql .= "(SELECT COUNT(*) FROM participations WHERE participations.playerId = players.playerId AND state != 'ACTIVE') AS gamesPlayed, ";
		$sql .= "(SELECT COUNT(*) FROM participations WHERE participations.playerId = players.playerId AND state = 'WON') AS gamesWon ";
		$sql .= "FROM players " . "LIMIT 1000;";
		$result = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);

		while($row = mysql_fetch_assoc($result)) {
		
			$playerStat = array(
				"playerId" => $row['playerId'],
    			"name" => $row['name'],
   				"assassinations" => $row['assassinationsCompleted'],
   				"assassinated" => $row['assassinated'],
    			"gamesPlayed" => $row['gamesPlayed'],
    			"gamesWon" => $row['gamesWon']
			);
			
			array_push($playerStats, $playerStat);
		}
		
			$ret = array(
				"status" => "OK",
				"playerStats" => $playerStats
			);
		print json_encode($ret);
	}
	
	function reportError($playerId, $error) {
		if($playerId < 10) return; //  don't bother for the test users
		
		$date = getDateNow();
		$sql = "INSERT INTO errors (type, error, extra, date) VALUES ('USER', '$error', '$playerId', '$date');";
		mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		
		$ret = array( "status" => "OK" );
		print json_encode($ret);
		return;
	}
	
	function reportAssassination($playerId, $gameId, $codeword) {
		// get date
		$date = getDateNow();
		
		// test for the player's victim being killed
		$sql  = "SELECT assassinations.assassinationId, assassinations.victimId, participations.alias AS victimAlias, victim.name AS victimName, victim.email AS victimEmail ";
		$sql .= "FROM participations INNER JOIN assassinations ON participations.playerId = assassinations.victimId AND participations.gameId = assassinations.gameId ";
		$sql .= "INNER JOIN players AS victim ON victim.playerId = participations.playerId ";
		$sql .= "WHERE participations.codeword = '$codeword' AND assassinations.assassinId = '$playerId' AND assassinations.gameId = '$gameId' AND assassinations.state = 'PENDING' LIMIT 1;";
		$result = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		if($row = mysql_fetch_assoc($result)) {
			killPlayer($row["victimId"], $gameId, true, true);
				
			$game = getGameObject($playerId, $gameId);
			if($game !== false) {
				$ret = array(
					"status" => "OK",
					"game" => $game,
					"assassinationId" => $row['assassinationId'],
					"assassinationType" => 'NORMAL',
					"victim" => array(
						"name" => $row['victimName'],
						"email" => $row['victimEmail'],
						"alias" => $row['victimAlias']
					)
				);
			} else {
				$ret = array("status" => "IMPOSSIBLE4.5");
				die(json_encode($ret));
			}
			
			print json_encode($ret);
			return;
		}
		
		// test for the player's assassin being killed
		$sql  = "SELECT assassinations.assassinationId, assassinations.assassinId, participations.alias AS victimAlias, victim.name AS victimName, victim.email AS victimEmail ";
		$sql .= "FROM participations INNER JOIN assassinations ON participations.playerId = assassinations.assassinId AND participations.gameId = assassinations.gameId ";
		$sql .= "INNER JOIN players AS victim ON victim.playerId = participations.playerId ";
		$sql .= "WHERE participations.codeword = '$codeword' AND assassinations.victimId = '$playerId' AND assassinations.gameId = '$gameId' AND assassinations.state = 'PENDING' LIMIT 1;";
		$result = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		if($row = mysql_fetch_assoc($result)) {
			$assassinId = $row["assassinId"];
			killPlayer($row["assassinId"], $gameId, false, false);
			
			// also insert the assassination by this player
			$sql = "INSERT INTO assassinations (gameId, assassinId, victimId, state, startDate, endDate) VALUES ('$gameId', '$playerId', '$assassinId', 'SELF_DEFENSE', '$date', '$date');";
			mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
			
			// Find the assassinationId for the datails
			$sql = "SELECT assassinationId FROM assassinations WHERE gameId='$gameId' AND assassinId='$playerId' AND victimId='$assassinId' LIMIT 1;";
			$result2 = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
			$row2 = mysql_fetch_assoc($result2);
			
			$game = getGameObject($playerId, $gameId);
			if($game !== false) {
				$ret = array(
					"status" => "OK",
					"game" => $game,
					"assassinationId" => $row2['assassinationId'],
					"assassinationType" => 'SELF_DEFENSE',
					"victim" => array(
						"name" => $row['victimName'],
						"email" => $row['victimEmail'],
						"alias" => $row['victimAlias']
					)
				);
			} else {
				$ret = array("status" => "IMPOSSIBLE4.5");
				die(json_encode($ret));
			}
			
			print json_encode($ret);
			return;
		}
		
		// get wanted hours
		$sql  = "SELECT wantedHours FROM games WHERE gameId = '$gameId' LIMIT 1;";
		$result = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		if($row = mysql_fetch_assoc($result)) {
			$wantedHours = intval($row['wantedHours']);
		} else {
			$ret = array("status" => "IMPOSSIBLE4.51");
			die(json_encode($ret));
		}
			
		// test for wanted person being killed
		$sql  = "SELECT assassinations.assassinationId, assassinations.assassinId, wantedPart.alias AS victimAlias, victim.name AS victimName, victim.email AS victimEmail ";
		$sql .= "FROM assassinations INNER JOIN participations AS wantedPart ON wantedPart.gameId = assassinations.gameId AND wantedPart.playerId = assassinations.assassinId ";
		$sql .= "INNER JOIN players AS victim ON victim.playerId = wantedPart.playerId ";
		$sql .= "WHERE wantedPart.codeword='$codeword' AND assassinations.assassinId != '$playerId' AND wantedPart.gameId = '$gameId' AND assassinations.state = 'PENDING' AND assassinations.endDate < DATE_ADD('$date', INTERVAL $wantedHours HOUR) LIMIT 1;";
		$result = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		if($row = mysql_fetch_assoc($result)) {
			$wantedId = $row["assassinId"];
			killPlayer($wantedId, $gameId, false, false);
			
			// also insert the assassination by this player
			$sql = "INSERT INTO assassinations (gameId, assassinId, victimId, state, startDate, endDate) VALUES ('$gameId', '$playerId', '$wantedId', 'WANTED', '$date', '$date');";
			mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
			
			// Add 24 hours to players's actual assassination time
			$sql = "UPDATE assassinations SET endDate = DATE_ADD(endDate, INTERVAL 24 HOUR) WHERE gameId='$gameId' AND assassinId='$playerId' AND state='PENDING' LIMIT 1;";
			mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
			
			// Find teh assassinationId for the datails
			$sql = "SELECT assassinationId FROM assassinations WHERE gameId='$gameId' AND assassinId='$playerId' AND victimId='$wantedId' LIMIT 1;";
			$result2 = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
			$row2 = mysql_fetch_assoc($result2);
				
			$game = getGameObject($playerId, $gameId);
			if($game !== false) {
				$ret = array(
					"status" => "OK",
					"game" => $game,
					"assassinationId" => $row2['assassinationId'],
					"assassinationType" => 'WANTED',
					"victim" => array(
						"name" => $row['victimName'],
						"email" => $row['victimEmail'],
						"alias" => $row['victimAlias']
					)
				);
			} else {
				$ret = array("status" => "IMPOSSIBLE4.5");
				die(json_encode($ret));
			}
			
			print json_encode($ret);
			return;
		}
			
		$ret = array("status" => "BAD_CODEWORD");
		print json_encode($ret);
		return;
	}
	
	function killPlayer($playerId, $gameId, $assassinSuccess, $waitForDetails) {
		$date = getDateNow();
		$dateLimit = getDateLimit();
		
		$sql = "SELECT assassinationId, assassinId FROM assassinations WHERE gameId = '$gameId' AND victimId = '$playerId' AND state='PENDING' LIMIT 1;";
		$result = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		if($row = mysql_fetch_assoc($result)) {
			$successfulAssassinationId = $row['assassinationId'];
			$assassinId = $row['assassinId'];
		} else {
			$ret = array("status" => "IMPOSSIBLE1.3");
			die(json_encode($ret));
		}
		
		$sql  = "SELECT assassinations.assassinationId, players.playerId AS newVictimId, players.name AS newVictimName, participations.alias AS newVictimAlias ";
		$sql .= "FROM assassinations INNER JOIN players ON assassinations.victimId = players.playerId ";
		$sql .= "INNER JOIN participations ON participations.playerId = players.playerId ";
		$sql .= "WHERE assassinations.gameId = '$gameId' AND participations.gameId = '$gameId' AND assassinations.assassinId = '$playerId' AND assassinations.state='PENDING' LIMIT 1;";
		$result = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		if($row = mysql_fetch_assoc($result)) {
			$failedAssassination = $row['assassinationId'];
			$newVictimId = $row['newVictimId'];
			$newVictimName = $row['newVictimName'];
			$newVictimAlias = $row['newVictimAlias'];
		} else {
			$ret = array("status" => "IMPOSSIBLE1");
			die(json_encode($ret));
		}
		
		// update assassin's assassination success
		$sql = "UPDATE assassinations SET state='" . ($assassinSuccess?'SUCCESS':'FAIL') . "', endDate='$date', detailsState='" . ($waitForDetails?'NONE':'ADDED') . "' WHERE assassinationId = '$successfulAssassinationId';";
		mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		
		// update victim's assassination fail
		$sql = "UPDATE assassinations SET state='FAIL', endDate='$date' WHERE assassinationId = '$failedAssassination';";
		mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		
		// update victim's participation to ASSASSINATED
		$sql = "UPDATE participations SET state='ASSASSINATED' WHERE gameId = '$gameId' AND playerId = '$playerId' LIMIT 1;";
		mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		
		// update victim's player to NOTHING
		$sql = "UPDATE players SET state='NOTHING' WHERE playerId = '$playerId' LIMIT 1;";
		mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		
		// count the number of players still in the game
		$sql = "SELECT * FROM participations WHERE participations.gameId = '$gameId' AND state = 'ACTIVE';";
		$result = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		$playersLeft = mysql_num_rows($result);
		
		/// UPDATE END DATE IN ASSASSINATIONS
		require_once('handler.php');
			
		if($playersLeft == 1) {
			// game has been won by playerId
			// update player's participation to WON
			$sql = "UPDATE participations SET state='WON' WHERE gameId = '$gameId' AND playerId = '$assassinId' LIMIT 1;";
			mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
			
			// update player to NOTHING
			$sql = "UPDATE players SET state='NOTHING' WHERE playerId = '$assassinId' LIMIT 1;";
			mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
			
			// update game to finished
			$sql = "UPDATE games SET state='FINISHED', endDate='$date', winnerId='$assassinId' WHERE gameId = '$gameId' LIMIT 1;";
			mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
			
			handlePlayerAssassinated($playerId);
			handleGameOver($gameId);
		} else {
			// create new assassination
			$sql = "INSERT INTO assassinations (gameId, assassinId, victimId, state, startDate, endDate) VALUES ('$gameId', '$assassinId', '$newVictimId', 'PENDING', '$date', '$dateLimit');";
			mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
			
			handlePlayerAssassinated($playerId);
		}
		
		if($playersLeft == 2) {
			handleDeathmatch($gameId);
		}
	}
	
	function reportLike($playerId, $assassinationId) {
		$sql = "SELECT likeId FROM likes WHERE assassinationId='$assassinationId' AND playerId='$playerId' LIMIT 1;";
		$result = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		if($row = mysql_fetch_assoc($result)) {
			$ret = array(
				"status" => "ALREADY_LIKED"
			);
			
			print json_encode($ret);
			return;
		}
		
		// pull out the gameId out of the assassination
		$sql = "SELECT gameId FROM assassinations WHERE assassinationId='$assassinationId' LIMIT 1;";
		$result = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		if($row = mysql_fetch_assoc($result)) {
			$gameId = $row['gameId'];
		} else {
			$ret = array(
				"status" => "BAD_ASSASSINATION"
			);
			
			print json_encode($ret);
			return;
		}
		
		// Add the like
		$sql = "INSERT INTO likes (assassinationId, playerId) VALUES ('$assassinationId', '$playerId');";
		mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		
		$ret = array(
			"status" => "OK",
			"news" => getNews($playerId, $gameId)
		);
		
		print json_encode($ret);
		return;
	}
	
	function startDispute($playerId, $gameId, $against, $description) {		
		$date = getDateNow();
		
		// make sure there are more then 2 people in the game
		$sql = "SELECT * FROM participations WHERE gameId='$gameId' AND state='ACTIVE' ;";
		$result = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		if(mysql_num_rows($result) <= 2) {
			$ret = array( "status" => "DISPUTE_DISABLED" );
			print json_encode($ret);
			return;
		}
						
		// get the targetId
		if($against == 'ASS') {
			$sql = "SELECT assassinId AS targetId FROM assassinations WHERE state='PENDING' AND gameId='$gameId' AND victimId='$playerId' LIMIT 1;";
		} else {
			$sql = "SELECT victimId AS targetId FROM assassinations WHERE state='PENDING' AND gameId='$gameId' AND assassinId='$playerId' LIMIT 1;";
		}
		$result = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		if($row = mysql_fetch_assoc($result)) {
			$targetId = $row['targetId'];
		} else {
			$ret = array(
				"status" => "BAD_REQUEST"
			);
			
			print json_encode($ret);
			return;
		}
		
		// check that the dispute does not already exists
		$sql = "SELECT * FROM disputes WHERE gameId='$gameId' AND accuserId='$playerId' AND defendantId='$targetId' LIMIT 1;";
		$result = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		if($row = mysql_fetch_assoc($result)) {
			$ret = array("status" => "DISPUTE_EXISTS");
			
			print json_encode($ret);
			return;
		}
		
		// get the number of accuser, defendant disputes
		$sql = "SELECT disputes FROM participations WHERE gameId='$gameId' AND playerId='$playerId' LIMIT 1;";
		$result = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		if($row = mysql_fetch_assoc($result)) {
			$accuserDisputes = intval($row['disputes']);
		} else {
			$ret = array("status" => "IMPOSSIBLE5.1");
			die(json_encode($ret));
		}
		
		$sql = "SELECT disputes FROM participations WHERE gameId='$gameId' AND playerId='$targetId' LIMIT 1;";
		$result = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		if($row = mysql_fetch_assoc($result)) {
			$targetDisputes = intval($row['disputes']);
		} else {
			$ret = array("status" => "IMPOSSIBLE5.2");
			die(json_encode($ret));
		}
		
		$sql = "UPDATE participations SET disputes=disputes+1 WHERE gameId='$gameId' AND (playerId='$playerId' OR playerId='$targetId') LIMIT 2;";
		mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		
		// deciede the dispute
		$trial = rand()/getrandmax();
		$p = ($accuserDisputes+1)/($accuserDisputes+$targetDisputes+2);
		if($trial < $p) {
			// accusor lost
			$won = 'DEF';
			$return = 'FAIL';
		} else {
			// accusor won
			$won = 'ACC';
			$return = 'SUCCESS';
			if($against == 'ASS') {
				killPlayer($targetId, $gameId, false, false);
				
				// also insert the assassination by this player
				$sql = "INSERT INTO assassinations (gameId, assassinId, victimId, state, startDate, endDate, detailsState) VALUES ('$gameId', '$playerId', '$targetId', 'SELF_DEFENSE', '$date', '$date', 'ADDED');";
				mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
			} else {
				killPlayer($targetId, $gameId, true, false);
			}
		}
		
		// start the dispute
		$sql  = " INSERT INTO disputes (gameId, accuserId, defendantId, won, status, accusation, defense, createdTime, rebutedTime, resolvedTime) ";
		$sql .= " VALUES ('$gameId', '$playerId', '$targetId', '$won', 'CREATED', '$description', '', '$date', '0000-00-00 00:00:00', '0000-00-00 00:00:00');";
		mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		
		require_once('handler.php');
		handleStartDispute($playerId, $targetId, $won, $description);
		
		// return the result
		$game = getGameObject($playerId, $gameId);
		$ret = array(
			"status" => "OK",
			"dispute" => $return,
			"game" => $game
		);
		
		print json_encode($ret);
	}
	
	function addDetails($playerId, $assassinationId, $details) {
		// make sure that we are trying to add to the correct player
		$sql = "SELECT assassinationId, DATE_ADD(endDate, INTERVAL 2 MINUTE) AS endDateOk FROM assassinations WHERE assassinationId='$assassinationId' AND assassinId='$playerId' AND state in ('SUCCESS', 'SELF_DEFENSE', 'WANTED') AND detailsState!='ADDED' LIMIT 1;";
		$result = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		if($row = mysql_fetch_assoc($result)) {
			$sql = "UPDATE assassinations SET details='$details', detailsState='ADDED' WHERE assassinationId='$assassinationId' LIMIT 1;";
			mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
			
			$date = getDateNow();
			if($date <= $row['endDateOk']) {
				require_once('handler.php');
				handleAddDetails($assassinationId);
			}
			
			$news = getNews($playerId);
			$ret = array(
				"status" => "OK",
				"news" => $news
			);
			print json_encode($ret);
		} else {
			$ret = array( "status" => "INVALID" );
			die(json_encode($ret));
		}
	}
	
	function getPlayerObject($playerId) {
		$sql = "SELECT playerId, email, name, state, waitingAlias, waitingStart, tokens FROM players WHERE playerId='$playerId' LIMIT 1;";
		$result = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		if($row = mysql_fetch_assoc($result)) {
			$player = array(
				"playerId" => $row['playerId'],
				"email" => $row['email'],
				"name" => $row['name'],
				"state" => $row['state'],
    			"waitingAlias" => $row['waitingAlias'],
				"waitingStart" => $row['waitingStart'],
				"tokens" => ($row['tokens']=='')?array():explode(';', $row['tokens'])
			);
			return $player;
		} else {
			return false;
		}
	}
	
	function getGameObject($playerId, $findGameId=false) {
		$date = getDateNow();
		$games = array();

		$sql  = "SELECT games.gameId, games.name AS gameName, games.startDate, participations.alias, participations.codeword, games.state AS gameState, participations.state AS participationState, games.wantedHours, ";
		$sql .= "(SELECT COUNT(*) FROM participations AS p WHERE p.gameId = games.gameId AND p.state = 'ACTIVE') AS numPlayers ";
		$sql .= "FROM games ";
		$sql .= "INNER JOIN participations ON participations.gameId = games.gameId ";
		$sql .= "WHERE participations.playerId='$playerId' " . ($findGameId===false?'ORDER BY games.startDate DESC;':"AND games.gameId = '$findGameId' LIMIT 1;");
		$result = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		while($row = mysql_fetch_assoc($result)) {
			$gameId = $row['gameId'];
			$wantedHours = $row['wantedHours'];
			$game = array(
				"gameState" => $row['gameState'],
				"participationState" => $row['participationState'],
				"gameId" => $gameId,
				"name" => $row['gameName'],
				"startDate" => $row['startDate'],
				"alias" => $row['alias'],
				"target" => '',
				"killDeadline" => '',
				"wantedDeadline" => '',
				"codeword" => $row['codeword'],
				"deathmatch" => ((intval($row['numPlayers'])==2)?'true':'false'),
				"wantedList" => array()
			);
			if($row['participationState'] == 'ACTIVE' && $row['gameState'] == 'ACTIVE') {
				$sql  = "SELECT victims.name AS targetName, victims.email AS targetEmail, victimPart.alias AS targetAlias, ";
				$sql .= "assassinations.endDate AS killDeadline, DATE_SUB(assassinations.endDate, INTERVAL $wantedHours HOUR) AS wantedDeadline ";
				$sql .= "FROM assassinations INNER JOIN players AS victims ON assassinations.victimId = victims.playerId ";
				$sql .= "INNER JOIN participations AS victimPart ON victimPart.gameId = assassinations.gameId AND victimPart.playerId = assassinations.victimId ";
				$sql .= "WHERE victimPart.gameId = '$gameId' AND assassinations.state = 'PENDING' AND assassinations.assassinId='$playerId' LIMIT 1;";
				$result2 = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
				if($row = mysql_fetch_assoc($result2)) {
					$target = array(
						'name' => $row["targetName"],
						'email' => $row["targetEmail"],
						'alias' => $row["targetAlias"]
					);
					$game["target"] = $target;
					$game["killDeadline"] = $row["killDeadline"];
					$game["wantedDeadline"] = $row["wantedDeadline"];
				} else {
					$ret = array( "status" => "IMPOSSIBLE6" );
					die(json_encode($ret));
				}
				
				$sql  = "SELECT wanted.name AS wantedName, wanted.email AS wantedEmail, wantedPart.alias AS wantedAlias ";
				$sql .= "FROM assassinations INNER JOIN players AS wanted ON assassinations.assassinId = wanted.playerId ";
				$sql .= "INNER JOIN participations AS wantedPart ON wantedPart.gameId = assassinations.gameId AND wantedPart.playerId = assassinations.assassinId ";
				$sql .= "WHERE wantedPart.gameId = '$gameId' AND assassinations.state = 'PENDING' AND assassinations.endDate < DATE_ADD('$date', INTERVAL $wantedHours HOUR) ";
				$sql .= "ORDER BY assassinations.endDate ASC; ";
				$result2 = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
				while($row = mysql_fetch_assoc($result2)) {
					$target = array(
						'name' => $row["wantedName"],
						'email' => $row["wantedEmail"],
						'alias' => $row["wantedAlias"]
					);
					array_push($game["wantedList"], $target);
				}
			}
			
			if($findGameId===false) {
				array_push($games, $game);
			} else {
				return $game;
			}
		}
		
		if($findGameId===false) {
			return $games;
		} else {
			return false;
		}
	}
	
	function getNews($playerId, $gameId=false) {
		$news = array();
		
		$sql  = "SELECT assassinations.assassinationId, assassinations.gameId, assassinPart.alias AS assassinAlias, targetPart.alias AS targetAlias, targetPlayer.name AS tagetName, assassinations.endDate, assassinations.state, assassinations.details, ";
		$sql .= "(SELECT COUNT(*) FROM likes WHERE likes.assassinationId = assassinations.assassinationId) AS numLike, ";
		$sql .= "(SELECT COUNT(*) FROM likes WHERE likes.assassinationId = assassinations.assassinationId AND likes.playerId = '$playerId' LIMIT 1) AS isLiked ";
		$sql .= "FROM assassinations ";
		$sql .= "INNER JOIN participations AS playerPart ON assassinations.gameId = playerPart.gameId ";
		$sql .= "INNER JOIN participations AS assassinPart ON assassinations.gameId = assassinPart.gameId AND assassinations.assassinId = assassinPart.playerId ";
		$sql .= "INNER JOIN participations AS targetPart ON assassinations.gameId = targetPart.gameId AND assassinations.victimId = targetPart.playerId ";
		$sql .= "INNER JOIN players AS targetPlayer ON assassinations.victimId = targetPlayer.playerId ";
		$sql .= "WHERE playerPart.playerId = '$playerId' AND assassinations.state IN ('SUCCESS', 'SELF_DEFENSE', 'WANTED') AND assassinations.detailsState != 'NONE' " . ($gameId===false?'':" AND assassinations.gameId = '$gameId'") . " ORDER BY assassinations.endDate DESC;";
		$result = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		while($row = mysql_fetch_assoc($result)) {
			$new = array(
				"assassinationId" => $row['assassinationId'],
				"gameId" => $row['gameId'],
			    "assassinAlias" => $row['assassinAlias'],
			    "targetAlias" => $row['targetAlias'],
			    "targetName" => $row['tagetName'],
			    "time" => $row['endDate'],
			    "details" => $row['details'],
				"likes" => $row['numLike'],
			    "isLiked" => $row['isLiked'],
				"type" => ($row['state'] == 'SUCCESS')?'NORMAL':$row['state']
			);
			array_push($news, $new);
		}
		
		return $news;
	}
	
	function getTokens() {
		$tokens = array();
		
		$sql = "SELECT id, name, type FROM tokens WHERE 1=1 ORDER BY name ASC ;";
		$result = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		while($row = mysql_fetch_assoc($result)) {
			array_push($tokens, $row);
		}
		
		return $tokens;
	}
	
	function allData($playerId) {
		$games = getGameObject($playerId, false);
		
		$player = getPlayerObject($playerId);
		$playerStats = array(
			"kills" => 4,
			"gamesPlayed" => 3
		);
		
		$news = getNews($playerId);
		$tokens = getTokens();
		
		if($player !== false) {
			$ret = array(
				"status" => "OK",
				"player" => $player,
				"news" => $news,
				"games" => $games,
				"tokens" => $tokens
			);
		} else {
			$ret = array(
				"status" => "NO_PLAYER",
				"playerId" => $playerId
			);
		}
		
		print json_encode($ret);
	}
	
	function joinGame($playerId, $alias, $tokens) {
		// check to see that the player's state is NOTHING
		$sql = "SELECT state FROM players WHERE playerId='$playerId' LIMIT 1;";
		$result = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		if($row = mysql_fetch_assoc($result)) {
			$state = $row['state'];
			if($state != 'NOTHING') {
				$ret = array( "status" => "BAD_STATE", "state" => $state );
				print json_encode($ret);
				return;
			}
		} else {
			$ret = array( "status" => "IMPOSSIBLE7" );
			die(json_encode($ret));
		}
			
		// check that the game with the correct token exists
		$tokenList = "'" . implode("','", explode(";\n", $tokens)) . "'";
		$sql = "SELECT gameId FROM games WHERE state = 'PENDING' AND token IN ($tokenList) LIMIT 1;";
		$result = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
		if($row = mysql_fetch_assoc($result)) {
			$gameId = $row['gameId'];
			
			// check to see if alias exists
			$sql = "SELECT * FROM participations WHERE alias='$alias' AND gameId = '$gameId' LIMIT 1;";
			$result = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
			if(mysql_num_rows($result) == 1) {
				$ret = array( "status" => "ALIAS_TAKEN" );		
				print json_encode($ret);
				return;
			}
			
			// get the code word
			$codewords = codewords();
			shuffle($codewords);
			do {
				$codeword = array_pop($codewords);
				$sql = "SELECT * FROM participations WHERE codeword = '$codeword' AND gameId = '$gameId' LIMIT 1;";
				$result = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
			} while(mysql_num_rows($result) > 0);
			
			$sql = "INSERT INTO participations (gameId, playerId, state, alias, codeword) VALUES ('$gameId', '$playerId', 'ACTIVE', '$alias', '$codeword');";
			mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
			
			$sql = "UPDATE players SET state='PLAYING', waitingAlias='', waitingStart='0000-00-00 00:00:00', tokens='$tokens' WHERE playerId = '$playerId' LIMIT 1;";
			mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
			if(mysql_affected_rows() == 1) {
				$player = getPlayerObject($playerId);
				$news = getNews($playerId);
				$game = getGameObject($playerId, $gameId);
				$ret = array(
					"status" => "OK",
					"player" => $player,
					"news" => $news,
					"game" => $game
				);
				print json_encode($ret);
				return;
			} else {
				$ret = array( "status" => "IMPOSSIBLE8a" );
				print json_encode($ret);
				return;
			}
		} else {
			// check to see if alias exists
			$sql = "SELECT * FROM players WHERE waitingAlias='$alias' LIMIT 1;";
			$result = mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
			if(mysql_num_rows($result) == 1) {
				$ret = array( "status" => "ALIAS_TAKEN" );		
				print json_encode($ret);
				return;
			}
			
			$date = getDateNow();
			$sql = "UPDATE players SET waitingAlias='$alias', state='WAITING', waitingStart='$date', tokens='$tokens' WHERE playerId='$playerId' LIMIT 1;";
			mysql_query($sql) or sql_error_report($sql, $_SERVER["SCRIPT_NAME"]);
			if(mysql_affected_rows() == 1) {
				$player = getPlayerObject($playerId);
				$ret = array(
					"status" => "OK",
					"player" => $player
				);
				print json_encode($ret);
				return;
			} else {
				$ret = array( "status" => "IMPOSSIBLE8b" );
				print json_encode($ret);
				return;
			}
		}
	}
?>