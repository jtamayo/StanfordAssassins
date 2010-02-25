<?php
require_once('emails.php');
require_once('db_login.php');
require_once('common.php');

function handleStartDispute($accuserId, $defendantId, $won, $accusation) {
	
}

function handleGameOver($gameId) {
	$gameName = getGameName($gameId);
	
	// get winner name alias
	$sql  = " SELECT players.playerId, players.name, participations.alias ";
	$sql .= " FROM players INNER JOIN participations ON players.playerId = participations.playerId ";
	$sql .= " WHERE participations.gameId='$gameId' AND participations.state = 'WON' LIMIT 1;";
	$result = mysql_query($sql) or sql_error_report($sql);
	if($row = mysql_fetch_assoc($result)) {
		$winnerId = $row['playerId'];
		$winnerName = $row['name'];
		$winnerAlias = $row['alias'];
	} else {
		$winnerId = '';
		$winnerName = '';
		$winnerAlias = '';
	}
	
	// get all the players
	$sql = "SELECT players.playerId, players.name, players.email FROM players INNER JOIN participations ON players.playerId = participations.playerId WHERE participations.gameId = '$gameId';";
	$result = mysql_query($sql) or sql_error_report($sql);
	while($row = mysql_fetch_assoc($result)) {
		if($row['playerId'] == $winnerId) {
			// Send "you won"
			sendGameWon($row['email'], $gameId, $gameName);
		} else {
			// Send "game won"
			sendGameOver($row['email'], $gameId, $gameName, $winnerAlias, $winnerName);
		}
	}
}

function handlePlayerAssassinated($playerId) {
	
}

function handleAddDetails($assassinationId) {
	require_once("twitter_login.php");
	require_once("facebook_login.php");
		$sql = "SELECT assassinationId,games.name as gameName,killer.alias as killerName,victim.alias as victimName, details 
			FROM assassinations INNER JOIN games on assassinations.gameId = games.gameId
			INNER JOIN participations as killer ON assassinId = killer.playerId
			INNER JOIN participations as victim  ON victimId = victim.playerId
			WHERE assassinationId='$assassinationId' AND killer.gameId = games.gameId AND victim.gameId = games.gameId LIMIT 1;";
			
		$result = mysql_query($sql) or sql_error_report($sql);
	
	
	
		if($row = mysql_fetch_assoc($result)) {			
			$hash_tag = "#".str_replace (" ", "", $row['gameName']) ;
			$kill_tag = $row['killerName']. "->". $row['victimName'] . ":" ;
			$header = $hash_tag . " " .$kill_tag ;
			$header = $hash_tag . " " .$kill_tag ;
			$user_text = $header. " " .$row['details'];
			
			postToTwitter($user_text );
			postToFacebook($user_text );
		}
}

function handleDetailsTimeout($assassinationId) {
	
}

function handleTargetChanged($playerId, $gameId) {
	$gameName = getGameName($gameId);
	$playerEmail = getPlayerName($playerId);
	
	$sql  = " SELECT players.name AS newVictimName, participations.alias AS newVictimAlias ";
	$sql .= " FROM players INNER JOIN participations ON participations.playerId = players.playerId ";
	$sql .= " INNER JOIN assassinations ON participations.playerId = assassinations.victimId ";
	$sql .= " WHERE participations.gameId = '$gameId' AND assassinations.assassinId = '$victimId' AND assassinations.state='PENDING' AND participations.state='ACTIVE' LIMIT 1;";
	$result = mysql_query($sql) or sql_error_report($sql);
	if($row = mysql_fetch_assoc($result)) {
		sendTargetChanged($playerEmail, $gameName, $row['newVictimAlias'], $row['newVictimName']);
	}
}

function handleDeathmatch($gameId) {
	$gameName = getGameName($gameId);
	
	$sql  = " SELECT players.playerId, players.name, players.email, participations.alias ";
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
	} else {
		showError('IMPOSIBLE7');
	}
	
	if($row = mysql_fetch_assoc($result)) {
		$p2Id = $row['playerId'];
		$p2Name = $row['name'];
		$p2Email = $row['email'];
		$p2Alias = $row['alias'];
	} else {
		showError('IMPOSIBLE8');
	}
	
	sendDeathmatch($p1Email, $gameName, $p2Name);
	sendDeathmatch($p2Email, $gameName, $p1Name);
}

function handleTimeOut($playerId) {
	$gameName = getGameName($gameId);
	$playerEmail = getPlayerName($playerId);
	
	sendTimeOut($playerEmail, $gameName);
}

function handleGameStart($gameId) {
	$gameName = getGameName($gameId);
	
	$sql  = "SELECT assassin.email, assassinPart.codeword, victimPart.alias, victim.name ";
	$sql .= "FROM assassinations ";
	$sql .= "INNER JOIN players AS assassin ON assassinations.assassinId = assassin.playerId ";
	$sql .= "INNER JOIN players AS victim ON assassinations.victimId = victim.playerId ";
	$sql .= "INNER JOIN participations AS assassinPart ON assassinations.gameId = assassinPart.gameId AND assassinations.assassinId = assassinPart.playerId ";
	$sql .= "INNER JOIN participations AS victimPart ON assassinations.gameId = victimPart.gameId AND assassinations.victimId = victimPart.playerId ";
	$sql .= "WHERE assassinations.gameId='$gameId' AND assassinations.state = 'PENDING' AND victimPart.state = 'ACTIVE';";
	$result = mysql_query($sql) or sql_error_report($sql);
	while($row = mysql_fetch_assoc($result)) {
		sendGameStarted($row['email'], $gameName, $row['codeword'], $row['alias'], $row['name']);
	}
}

////////////////////////////////
// HELPERS
////////////////////////////////

function getGameName($gameId) {
	$sql = "SELECT name FROM games WHERE gameId = '$gameId' LIMIT 1;";
	$result = mysql_query($sql) or sql_error_report($sql);
	if($row = mysql_fetch_assoc($result)) {
		return $row['name'];
	} else {
		$ret = array("status" => "IMPOSSIBLE4");
		die(json_encode($ret));
	}
}

function getPlayerName($playerId) {
	$sql = "SELECT email FROM players WHERE playerId = '$playerId' LIMIT 1;";
	$result = mysql_query($sql) or sql_error_report($sql);
	if($row = mysql_fetch_assoc($result)) {
		return $row['email'];
	} else {
		$ret = array("status" => "IMPOSSIBLE4");
		die(json_encode($ret));
	}
}

function postToTwitter($post) {
	
	$twi_user = getTwitterConnector()	;
	
	if (strlen($post)>140 ){
		//TODO HAS TO CHECK FOR MORE CASES
		$result = $twi_user->updateStatus(substr( $post,0,strrpos( $post , " " ,-(strlen( $post)-137))) . "...");
		$result = $twi_user->updateStatus($hash_tag . " ...".substr( $post,strrpos( $post , " " ,-(strlen($post )-137))));
	}else{
		$result = $twi_user->updateStatus($post);
	}
}

function postToFacebook($post) {
	
	$facebook = getFacebookConnector();

	$page_id = '325648886259'; 
	return $facebook->api_client->stream_publish( $post,null,null,null,$page_id );
	
}

function sql_error_report($sql) {		
	$date = getDateNow();
	$sqlError = mysql_error();
	mysql_query(sprintf("INSERT INTO errors (type, error, extra, date) VALUES ('SQL', '%s', 'gameserver:%s', '%s');", addslashes($sqlError), addslashes($sql), $date));
	
	$ret = array(
		"status" => "SQL_ERROR",
		"error" => $sqlError,
		"sql" => $sql
	);
	
	die(json_encode($ret));
}
?>