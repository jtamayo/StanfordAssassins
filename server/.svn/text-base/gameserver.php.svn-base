<?php
	header('Content-Type: text/plain; charset=utf-8');
	
	$invalidArg = false; # assume that we are valid
	
	session_start();
	if(isset($_SESSION['userId'])) {
		$userId = $_SESSION['userId'];
	} else {
		$debugUser = arg('debug');
		if($debugUser === false || 10 <= $debugUser) {
			$ret = array(
				"status" => "BAD_AUTH"
			);
			
			die(json_encode($ret));
		} else {
			$_SESSION['userId'] = $debugUser;
			$userId = $debugUser;
		}
	}
	
	switch(arg('cmd')) {
	case false:
		$invalidArg = 'cmd';
		break;
		
	case 'allData':
		if($invalidArg === false) {
			allData($userId);
		}
		break;
		
	case 'joinGame':
		if($invalidArg === false) {
			$alias = arg('alias');
			if($alias === false) $invalidArg = 'alias';
		}
		if($invalidArg === false) {
			joinGame($userId, $alias);
		}
		break;
		
	case 'info':
		phpinfo();
		break;

	case 'sendGameStarted':
		if($invalidArg === false) {
			$email = arg('email');
			if($email === false) $invalidArg = 'email';
		}
		if($invalidArg === false) {
			$targetAlias = arg('targetAlias');
			if($targetAlias === false) $invalidArg = 'targetAlias';
		}
		if($invalidArg === false) {
			$targetName = arg('targetName');
			if($targetName === false) $invalidArg = 'targetName';
		}
		if($invalidArg === false) {
			sendGameStarted($email, 1337, "Operation Yellow Tiger", "teapot", $targetAlias, $targetName);
			echo "Test email sent to: $email";
		}
		break;
		
	case 'sendBeenAssassinated':
		if($invalidArg === false) {
			$email = arg('email');
			if($email === false) $invalidArg = 'email';
		}
		if($invalidArg === false) {
			$assassinAlias = arg('assassinAlias');
			if($assassinAlias === false) $invalidArg = 'assassinAlias';
		}
		if($invalidArg === false) {
			$assassinName = arg('assassinName');
			if($assassinName === false) $invalidArg = 'assassinName';
		}
		if($invalidArg === false) {
			sendBeenAssassinated($email, $assassinAlias, $assassinName, "I killed him with a my sock, muhahahaha.");
			echo "Test email sent to: $email";
		}
		break;
		
	case 'sendAssassinIsOut':
		if($invalidArg === false) {
			$email = arg('email');
			if($email === false) $invalidArg = 'email';
		}
		if($invalidArg === false) {
			$assassinAlias = arg('assassinAlias');
			if($assassinAlias === false) $invalidArg = 'assassinAlias';
		}
		if($invalidArg === false) {
			$assassinName = arg('assassinName');
			if($assassinName === false) $invalidArg = 'assassinName';
		}
		if($invalidArg === false) {
			sendAssassinIsOut($email, $assassinAlias, $assassinName);
			echo "Test email sent to: $email";
		}
		break;
		
	case 'sendTargetChanged':
		if($invalidArg === false) {
			$email = arg('email');
			if($email === false) $invalidArg = 'email';
		}
		if($invalidArg === false) {
			$targetAlias = arg('targetAlias');
			if($targetAlias === false) $invalidArg = 'targetAlias';
		}
		if($invalidArg === false) {
			$targetName = arg('targetName');
			if($targetName === false) $invalidArg = 'targetName';
		}
		if($invalidArg === false) {
			sendTargetChanged($email, 1337, $targetAlias, $targetName);
			echo "Test email sent to: $email";
		}
		break;

	case 'sendGameFinished':
		if($invalidArg === false) {
			$email = arg('email');
			if($email === false) $invalidArg = 'email';
		}
		if($invalidArg === false) {
			$winnerAlias = arg('winnerAlias');
			if($winnerAlias === false) $invalidArg = 'winnerAlias';
		}
		if($invalidArg === false) {
			$winnerName = arg('winnerName');
			if($winnerName === false) $invalidArg = 'winnerName';
		}
		if($invalidArg === false) {
			sendGameFinished($email, 1337, "Operation Yellow Tiger", $winnerAlias, $winnerName);
			echo "Test email sent to: $email";
		}
		break;

	case 'sendGameWon':
		if($invalidArg === false) {
			$email = arg('email');
			if($email === false) $invalidArg = 'email';
		}
		if($invalidArg === false) {
			sendGameWon($email, 1337, "Operation Yellow Tiger");
			echo "Test email sent to: $email";
		}
		break;
		
	case 'sendBeenDispute': 
		if($invalidArg === false) {
			$email = arg('email');
			if($email === false) $invalidArg = 'email';
		}
		if($invalidArg === false) {
			sendBeenDispute('vad@stanford.edu', 1, 'JamesBond', 'I am right', 2, 'Cyborg2001', 'I am in the right', 1337);
			echo "Test email sent to: $email";
		}
		break;
		
	default:
		$invalidArg = 'cmd';
		break;
	}
	
	if($invalidArg !== false) {
		$ret = array(
			"status" => "invalidArg",
			"arg" => $invalidArg
		);
		
		print json_encode($ret);
	}
	
	function arg($arg) {
		if(isset($_POST[$arg])) return $_POST[$arg];
		if(isset($_GET[$arg])) return $_GET[$arg];
		return false;
	}
	
	function sql_error_report($sql) {
		$ret = array(
			"status" => "sqlError",
			"error" => mysql_error(),
			"sql" => $sql
		);
		
		die(json_encode($ret));
	}
	
	function getUserObject($userId) {
		require_once('db_login.php');
			
		$sql = "SELECT userId, email, name, state, waitingAlias, waitingStart FROM users WHERE userId='$userId' LIMIT 1;";
		$result = mysql_query($sql) or sql_error_report($sql);
		if($row = mysql_fetch_assoc($result)) {
			$user = array(
				"userId" => $row['userId'],
				"email" => $row['email'],
				"name" => $row['name'],
				"state" => $row['state'],
    			"waitingAlias" => $row['waitingAlias'],
				"waitingStart" => $row['waitingStart']
			);
			return $user;
		} else {
			return false;
		}
	}
	
	function allData($userId) {
		$game = array(
			"gameId" => 1432,
			"name" => "Operation Yellow Tiger",
			"targetName" => "Isac Bulm",
			"targetAlias" => "ipoo",
			"killDeadline" => "2010-01-16 23:23:23",
			"codeWord" => "teapot"
		);
		$user = getUserObject($userId);
		$userStats = array(
			"kills" => 4,
			"gamesPlayed" => 3
		);
		
		if($user !== false) {
			$ret = array(
				"status" => "OK",
				"user" => $user,
				"news" => array(),
				"games" => array($game)
			);
		} else {
			$ret = array(
				"status" => "NO_USER",
				"userId" => $userId
			);
		}
		
		print json_encode($ret);
	}
	
	function joinGame($userId, $alias) {
		require_once('db_login.php');
		
		// check to see if alias exists
		$sql = "SELECT * FROM users WHERE waitingAlias='$alias' LIMIT 1;";
		$result = mysql_query($sql) or sql_error_report($sql);
		if(mysql_num_rows($result) == 1) {
			$ret = array( "status" => "ALIAS_TAKEN" );		
			print json_encode($ret);
			return;
		}
		
		// check to see that the user's state is NOTHING
		$sql = "SELECT state FROM users WHERE userId='$userId' LIMIT 1;";
		$result = mysql_query($sql) or sql_error_report($sql);
		if($row = mysql_fetch_assoc($result)) {
			$state = $row['state'];
			if($state != 'NOTHING') {
				$ret = array( "status" => "BAD_STATE", "state" => $state );
				print json_encode($ret);
				return;
			}
		} else {
			$ret = array( "status" => "IMPOSSIBLE" );
			print json_encode($ret);
			return;
		}
		
		$date = gmdate("Y-m-d H:i:s");
		$sql = "UPDATE users SET waitingAlias='$alias', state='WAITING', waitingStart='$date' WHERE userId='$userId' LIMIT 1;";
		mysql_query($sql) or sql_error_report($sql);
		if(mysql_affected_rows() == 1) {
			$user = getUserObject($userId);
			$ret = array(
				"status" => "OK",
				"user" => $user
			);
			print json_encode($ret);
			return;
		} else {
			$ret = array( "status" => "IMPOSSIBLE" );
			print json_encode($ret);
			return;
		}
	}
?>