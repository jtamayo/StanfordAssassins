<?php
	//header('Content-Type: text/plain; charset=utf-8');
	header('Content-Type: application/json; charset=utf-8');
	
	$invalidArg = false; # assume that we are valid
	
	session_start();
	if(isset($_SESSION['playerId'])) {
		$playerId = $_SESSION['playerId'];
	} else {
		$debugPlayer = arg('debug');
		if($debugPlayer === false || 10 <= $debugPlayer) {
			$ret = array(
				"status" => "BAD_AUTH"
			);
			
			die(json_encode($ret));
		} else {
			$_SESSION['playerId'] = $debugPlayer;
			$playerId = $debugPlayer;
		}
	}
	
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
			joinGame($playerId, $alias);
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
		
	case 'getLeaderboard':
		if($invalidArg === false) {
			getPlayerStats(false);
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
	
	function arg($arg) {
		if(isset($_POST[$arg])) return ini_get('magic_quotes_gpc') ? $_POST[$arg] : addslashes($_POST[$arg]);
		if(isset($_GET[$arg])) return ini_get('magic_quotes_gpc') ? $_GET[$arg] : addslashes($_GET[$arg]);
		return false;
	}
	
	function sql_error_report($sql) {
		require_once('db_login.php');
		
		$date = gmdate("Y-m-d H:i:s");
		$sqlError = mysql_error();
		mysql_query(sprintf("INSERT INTO errors (type, error, extra, date) VALUES ('SQL', '%s', 'gameserver:%s', '%s');", addslashes($sqlError), addslashes($sql), $date));
		
		$ret = array(
			"status" => "SQL_ERROR",
			"error" => $sqlError,
			"sql" => $sql
		);
		
		die(json_encode($ret));
	}

	function getPlayerStats($playerId = false) {
		require_once('db_login.php');
		
		$playerStats = array();
		
		$sql  = "SELECT players.playerId, players.name, ";
		$sql .= "(SELECT COUNT(*) FROM assassinations INNER JOIN games ON assassinations.gameId = games.gameId WHERE assassinations.assassinId = players.playerId AND assassinations.state = 'SUCCESS' AND games.state = 'FINISHED') AS assassinationsCompleted, ";
		$sql .= "(SELECT COUNT(*) FROM assassinations WHERE assassinations.victimId = players.playerId AND assassinations.state = 'SUCCESS') AS assassinated, ";
		$sql .= "(SELECT COUNT(*) FROM participations WHERE participations.playerId = players.playerId AND state != 'ACTIVE') AS gamesPlayed, ";
		$sql .= "(SELECT COUNT(*) FROM participations WHERE participations.playerId = players.playerId AND state = 'WON') AS gamesWon ";
		$sql .= "FROM players " . ($playerId!==false?"WHERE players.playerId = '$playerId' ":'') . "LIMIT 1000;";
		$result = mysql_query($sql) or sql_error_report($sql);
		while($row = mysql_fetch_assoc($result)) {
			$playerStat = array(
				"playerId" => $row['playerId'],
    			"name" => $row['name'],
   				"assassinations" => $row['assassinationsCompleted'],
   				"assassinated" => $row['assassinated'],
    			"gamesPlayed" => $row['gamesPlayed'],
    			"gamesWon" => $row['gamesWon']
			);
			
			if($playerId===false) {
				array_push($playerStats, $playerStat);
			} else {
				$ret = array(
					"status" => "OK",
					"playerStats" => $playerStat
				);
				print json_encode($ret);
				return;
			}
		}
		
		if($playerId===false) {
			$ret = array(
				"status" => "OK",
				"playerStats" => $playerStats
			);
		} else {
			$ret = array(
				"status" => "NO_PLAYER"
			);
		}
		print json_encode($ret);
	}
	
	function reportError($playerId, $error) {
		if($playerId < 10) return; //  don't bother for the test users
		require_once('db_login.php');
		
		$date = gmdate("Y-m-d H:i:s");
		$sql = "INSERT INTO errors (type, error, extra, date) VALUES ('USER', '$error', '$playerId', '$date');";
		mysql_query($sql) or sql_error_report($sql);
		
		$ret = array( "status" => "OK" );
		print json_encode($ret);
		return;
	}
	
	function reportAssassination($playerId, $gameId, $codeword) {
		require_once('db_login.php');
		
		// test for the player being killed
		
		$sql  = "SELECT assassinations.assassinationId, assassinations.victimId, participations.codeword ";
		$sql .= "FROM participations INNER JOIN assassinations ON participations.playerId = assassinations.victimId AND participations.gameId = assassinations.gameId ";
		$sql .= "WHERE assassinations.assassinId = '$playerId' AND assassinations.gameId = '$gameId' AND assassinations.state = 'PENDING' LIMIT 1;";
		$result = mysql_query($sql) or sql_error_report($sql);
		if($row = mysql_fetch_assoc($result)) {
			if($codeword == $row["codeword"]) {
				$assassinationId = $row['assassinationId'];
				$victimId = $row['victimId'];
				
				$date = gmdate("Y-m-d H:i:s");
				$dateLimit = gmdate("Y-m-d H:i:s", time()+96*60*60);
				
				$sql  = "SELECT assassinations.assassinationId, players.playerId AS newVictimId, players.name AS newVictimName, participations.alias AS newVictimAlias ";
				$sql .= "FROM assassinations INNER JOIN players ON assassinations.victimId = players.playerId ";
				$sql .= "INNER JOIN participations ON participations.playerId = players.playerId ";
				$sql .= "WHERE assassinations.gameId = '$gameId' AND participations.gameId = '$gameId' AND assassinations.assassinId = '$victimId' AND assassinations.state='PENDING' LIMIT 1;";
				$result = mysql_query($sql) or sql_error_report($sql);
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
				$sql = "UPDATE assassinations SET state='SUCCESS', endDate='$date' WHERE assassinationId = '$assassinationId';";
				mysql_query($sql) or sql_error_report($sql);
				
				// update victim's assassination fail
				$sql = "UPDATE assassinations SET state='FAIL', endDate='$date' WHERE assassinationId = '$failedAssassination';";
				mysql_query($sql) or sql_error_report($sql);
				
				// update victim's participation to ASSASSINATED
				$sql = "UPDATE participations SET state='ASSASSINATED' WHERE gameId = '$gameId' AND playerId = '$victimId' LIMIT 1;";
				mysql_query($sql) or sql_error_report($sql);
				
				// update victim's player to NOTHING
				$sql = "UPDATE players SET state='NOTHING' WHERE playerId = '$victimId' LIMIT 1;";
				mysql_query($sql) or sql_error_report($sql);
				
				/// UPDATE END DATE IN ASSASSINATIONS
					
				if($newVictimId == $playerId) {
					require_once('emails.php');
					
					// game has been won by playerId
					// update player's participation to WON
					$sql = "UPDATE participations SET state='WON' WHERE gameId = '$gameId' AND playerId = '$playerId' LIMIT 1;";
					mysql_query($sql) or sql_error_report($sql);
					
					// update player to NOTHING
					$sql = "UPDATE players SET state='NOTHING' WHERE playerId = '$playerId' LIMIT 1;";
					mysql_query($sql) or sql_error_report($sql);
					
					// update game to finished
					$sql = "UPDATE games SET state='FINISHED', endDate='$date', winnerId='$playerId' WHERE gameId = '$gameId' LIMIT 1;";
					mysql_query($sql) or sql_error_report($sql);
					
					// send out emails
					///////////////////////////
					
					// get winner name
					$sql = "SELECT name FROM players WHERE playerId = '$playerId' LIMIT 1;";
					$result = mysql_query($sql) or sql_error_report($sql);
					if($row = mysql_fetch_assoc($result)) {
						$winnerName = $row['name'];
					} else {
						$ret = array("status" => "IMPOSSIBLE2");
						die(json_encode($ret));
					}
					
					// get winner alias
					$sql = "SELECT alias FROM participations WHERE gameId = '$gameId' AND playerId = '$playerId' LIMIT 1;";
					$result = mysql_query($sql) or sql_error_report($sql);
					if($row = mysql_fetch_assoc($result)) {
						$winnerAlias = $row['alias'];
					} else {
						$ret = array("status" => "IMPOSSIBLE3");
						die(json_encode($ret));
					}
					
					// get game name
					$sql = "SELECT name FROM games WHERE gameId = '$gameId' LIMIT 1;";
					$result = mysql_query($sql) or sql_error_report($sql);
					if($row = mysql_fetch_assoc($result)) {
						$gameName = $row['name'];
					} else {
						$ret = array("status" => "IMPOSSIBLE4");
						die(json_encode($ret));
					}
					
					// get all the players
					$sql = "SELECT players.playerId, players.name, players.email FROM players INNER JOIN participations ON players.playerId = participations.playerId WHERE participations.gameId = '$gameId';";
					$result = mysql_query($sql) or sql_error_report($sql);
					while($row = mysql_fetch_assoc($result)) {
						if($row['playerId'] == $playerId) {
							// Send "you won"
							sendGameWon($row['email'], $gameId, $gameName);
						} else {
							// Send "game won"
							sendGameOver($row['email'], $gameId, $gameName, $winnerAlias, $winnerName);
						}
					}
				} else {
					// create new assassination
					$sql = "INSERT INTO assassinations (gameId, assassinId, victimId, state, startDate, endDate) VALUES ('$gameId', '$playerId', '$newVictimId', 'PENDING', '$date', '$dateLimit');";
					mysql_query($sql) or sql_error_report($sql);
				}
				
				$game = getGameObject($playerId, $gameId);
				if($game !== false) {
					$ret = array(
						"status" => "OK",
						"game" => $game,
						"assassinationId" => $assassinationId
					);
				} else {
					$ret = array("status" => "IMPOSSIBLE4.5");
					die(json_encode($ret));
				}
				
				print json_encode($ret);
			} else {
				$ret = array(
					"status" => "BAD_CODEWORD"
				);
				
				print json_encode($ret);
			}
		} else {
			$ret = array("status" => "IMPOSSIBLE5");
			die(json_encode($ret));
		}
	}
	
	function reportLike($playerId, $assassinationId) {
		require_once('db_login.php');
		
		$sql = "SELECT likeId FROM likes WHERE assassinationId='$assassinationId' AND playerId='$playerId' LIMIT 1;";
		$result = mysql_query($sql) or sql_error_report($sql);
		if($row = mysql_fetch_assoc($result)) {
			$ret = array(
				"status" => "ALREADY_LIKED"
			);
			
			print json_encode($ret);
			return;
		}
		
		// pull out the gameId out of the assassination
		$sql = "SELECT gameId FROM assassinations WHERE assassinationId='$assassinationId' LIMIT 1;";
		$result = mysql_query($sql) or sql_error_report($sql);
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
		mysql_query($sql) or sql_error_report($sql);
		
		$ret = array(
			"status" => "OK",
			"news" => getNews($playerId, $gameId)
		);
		
		print json_encode($ret);
		return;
	}
	
	function addDetails($playerId, $assassinationId, $details) {
		require_once('db_login.php');
		
		$twoMinAgo = gmdate("Y-m-d H:i:s", time() - 2*60);
		
		if($details == '#') $details = '';
		
		// make sure that we are trying to add to teh correct player
		$sql = "SELECT assassinationId FROM assassinations WHERE assassinationId='$assassinationId' AND assassinId='$playerId' AND state='SUCCESS' AND details='#' AND '$twoMinAgo' <= endDate LIMIT 1;";
		$result = mysql_query($sql) or sql_error_report($sql);
		if($row = mysql_fetch_assoc($result)) {
			$sql = "UPDATE assassinations SET details='$details' WHERE assassinationId='$assassinationId' LIMIT 1;";
			mysql_query($sql) or sql_error_report($sql);
			
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
		require_once('db_login.php');
			
		$sql = "SELECT playerId, email, name, state, waitingAlias, waitingStart FROM players WHERE playerId='$playerId' LIMIT 1;";
		$result = mysql_query($sql) or sql_error_report($sql);
		if($row = mysql_fetch_assoc($result)) {
			$player = array(
				"playerId" => $row['playerId'],
				"email" => $row['email'],
				"name" => $row['name'],
				"state" => $row['state'],
    			"waitingAlias" => $row['waitingAlias'],
				"waitingStart" => $row['waitingStart']
			);
			return $player;
		} else {
			return false;
		}
	}
	
	function getGameObject($playerId, $findGameId=false) {
		require_once('db_login.php');
		
		$games = array();

		$sql  = "SELECT games.gameId, games.name AS gameName, participations.alias, participations.codeword, games.state AS gameState, participations.state AS participationState ";
		$sql .= "FROM games ";
		$sql .= "INNER JOIN participations ON participations.gameId = games.gameId ";
		$sql .= "WHERE participations.playerId='$playerId' " . ($findGameId===false?'ORDER BY games.startDate DESC;':"AND games.gameId = '$findGameId' LIMIT 1;");
		$result = mysql_query($sql) or sql_error_report($sql);
		while($row = mysql_fetch_assoc($result)) {
			$gameId = $row['gameId'];
			$game = array(
				"gameState" => $row['gameState'],
				"participationState" => $row['participationState'],
				"gameId" => $gameId,
				"name" => $row['gameName'],
				"alias" => $row['alias'],
				"targetName" => '',
				"targetAlias" => '',
				"killDeadline" => '',
				"codeword" => $row['codeword']
			);
			if($row['participationState'] == 'ACTIVE') {
				$sql  = "SELECT victims.name AS targetName, victimPart.alias AS targetAlias, assassinations.endDate ";
				$sql .= "FROM assassinations ";
				$sql .= "INNER JOIN players AS victims ON assassinations.victimId = victims.playerId ";
				$sql .= "INNER JOIN participations AS victimPart ON victimPart.playerId = assassinations.victimId ";
				$sql .= "WHERE victimPart.gameId = '$gameId' AND assassinations.state = 'PENDING' AND assassinations.assassinId='$playerId' LIMIT 1;";
				$result2 = mysql_query($sql) or sql_error_report($sql);
				if($row = mysql_fetch_assoc($result2)) {
					$game["targetName"] = $row["targetName"];
					$game["targetAlias"] = $row["targetAlias"];
					$game["killDeadline"] = $row["endDate"];
				} else {
					$ret = array( "status" => "IMPOSSIBLE6" );
					die(json_encode($ret));
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
		require_once('db_login.php');
		
		$news = array();
		$twoMinAgo = gmdate("Y-m-d H:i:s", time() - 2*60);
		
		$sql  = "SELECT assassinations.assassinationId, assassinations.gameId, assassinPart.alias AS assassinAlias, targetPart.alias AS targetAlias, targetPlayer.name AS tagetName, assassinations.endDate, assassinations.details, ";
		$sql .= "(SELECT COUNT(*) FROM likes WHERE likes.assassinationId = assassinations.assassinationId) AS numLike, ";
		$sql .= "(SELECT COUNT(*) FROM likes WHERE likes.assassinationId = assassinations.assassinationId AND likes.playerId = '$playerId' LIMIT 1) AS isLiked ";
		$sql .= "FROM assassinations ";
		$sql .= "INNER JOIN participations AS playerPart ON assassinations.gameId = playerPart.gameId ";
		$sql .= "INNER JOIN participations AS assassinPart ON assassinations.gameId = assassinPart.gameId AND assassinations.assassinId = assassinPart.playerId ";
		$sql .= "INNER JOIN participations AS targetPart ON assassinations.gameId = targetPart.gameId AND assassinations.victimId = targetPart.playerId ";
		$sql .= "INNER JOIN players AS targetPlayer ON assassinations.victimId = targetPlayer.playerId ";
		$sql .= "WHERE playerPart.playerId = '$playerId' AND assassinations.state = 'SUCCESS' AND (assassinations.details != '#' OR assassinations.endDate < '$twoMinAgo')" . ($gameId===false?'':" AND assassinations.gameId = '$gameId'") . " ORDER BY assassinations.endDate DESC;";
		$result = mysql_query($sql) or sql_error_report($sql);
		while($row = mysql_fetch_assoc($result)) {
			$new = array(
				"assassinationId" => $row['assassinationId'],
				"gameId" => $row['gameId'],
			    "assassinAlias" => $row['assassinAlias'],
			    "targetAlias" => $row['targetAlias'],
			    "targetName" => $row['tagetName'],
			    "time" => $row['endDate'],
			    "details" => ($row['details']!='#'?$row['details']:''),
				"likes" => $row['numLike'],
			    "isLiked" => $row['isLiked']
			);
			array_push($news, $new);
		}
		
		return $news;
	}
	
	function allData($playerId) {
		$games = getGameObject($playerId, false);
		
		$player = getPlayerObject($playerId);
		$playerStats = array(
			"kills" => 4,
			"gamesPlayed" => 3
		);
		
		$news = getNews($playerId);
		
		if($player !== false) {
			$ret = array(
				"status" => "OK",
				"player" => $player,
				"news" => $news,
				"games" => $games
			);
		} else {
			$ret = array(
				"status" => "NO_PALYER",
				"playerId" => $playerId
			);
		}
		
		print json_encode($ret);
	}
	
	function joinGame($playerId, $alias) {
		require_once('db_login.php');
		
		// check to see if alias exists
		$sql = "SELECT * FROM players WHERE waitingAlias='$alias' LIMIT 1;";
		$result = mysql_query($sql) or sql_error_report($sql);
		if(mysql_num_rows($result) == 1) {
			$ret = array( "status" => "ALIAS_TAKEN" );		
			print json_encode($ret);
			return;
		}
		
		// check to see that the player's state is NOTHING
		$sql = "SELECT state FROM players WHERE playerId='$playerId' LIMIT 1;";
		$result = mysql_query($sql) or sql_error_report($sql);
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
		
		$date = gmdate("Y-m-d H:i:s");
		$sql = "UPDATE players SET waitingAlias='$alias', state='WAITING', waitingStart='$date' WHERE playerId='$playerId' LIMIT 1;";
		mysql_query($sql) or sql_error_report($sql);
		if(mysql_affected_rows() == 1) {
			$player = getPlayerObject($playerId);
			$ret = array(
				"status" => "OK",
				"player" => $player
			);
			print json_encode($ret);
			return;
		} else {
			$ret = array( "status" => "IMPOSSIBLE8" );
			print json_encode($ret);
			return;
		}
	}
?>