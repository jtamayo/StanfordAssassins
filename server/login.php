<?php
if(isset($_GET['r'])) {
    $encrypt = $_GET['r'];
    $fail = false;
} else {
    $fail = 'empty';
}

// decrypt
if($fail === false) {
	//header('Content-Type: text/plain; charset=utf-8');
	
	$HASH_SALT = "OH NO! The fight's out,I'ma 'bout to punch yo...lights out,Get the FUCK back, guard ya grill,There's somethin' wrong, we can't stay still,I've been drankin' and bustin' two,and I been thankin' of bustin' you,Upside ya motherfuckin' forehead";;
	$XOR_KEY = "Causin' confusion, Disturbin Tha Peace,It's not an illusion, we runnin the streets,So bye-bye to all you groupies and golddiggers,Is there a bumper on your ass? NO NIGGA!,I'm doin' a hundred on the highway,So if you do the speed limit, get the FUCK outta my way,I'm D.U.I., hardly ever caught sober,and you about to get ran the FUCK over";

	//echo 'Reply: ' . $encrypt . "\n";
	$messageSign = XORDecrypt($encrypt, $XOR_KEY);
	//echo 'MessageSign: ' . $messageSign . "\n";
	$sign = substr($messageSign, 0, 32);
	//echo 'Sign: ' . $sign . "\n";
	$message = substr($messageSign, 32);
	//echo 'Message: ' . $message . "\n";
	
	//echo 'M =?= S: ' . ((md5($message . $HASH_SALT) == $sign)?'True':'False') . "\n";
    if(md5($message . $HASH_SALT) != $sign) {
        $fail = 'bad sign';
    }
}

// check format
if($fail === false) {
    $part = explode('|', $message);
    if(count($part) != 4) {
    	$fail = 'bad count: ' . $message;
    }
}

// check timestamp
if($fail === false) {
    $rqTime = intval($part[3], 10);
	$time = time();
    if(($rqTime < $time - 60)||($time < $rqTime)) {
    	$fail = 'bad time';
    }
}

// Check email
if($fail === false) {
    $name = $part[0];
    $email = $part[1];
    $rule = $part[2];
    if(strstr($email, 'stanford.edu') === false) {
    	$fail = 'bad email';
    }
}

// authenticate player
if($fail === false) {
	require_once 'db_login.php';
	$date = gmdate("Y-m-d H:i:s");
	
	$sql = "SELECT playerId FROM players WHERE email='$email' LIMIT 1;";
	$result = mysql_query($sql) or ($fail = mysql_error());
	if($fail === false) {
		if($row = mysql_fetch_assoc($result)) {
			// player exists get the playerId
			$playerId = $row['playerId'];
			
			// update the last time logged in
			$sql = "UPDATE players SET lastLogin='$date' WHERE playerId='$playerId' LIMIT 1;";
			mysql_query($sql) or ($fail = mysql_error());
		} else {
			// first time player, create him
			$sql = "INSERT INTO players (email, name, rule, dateCreated, lastLogin) VALUES ('$email', '$name', '$rule', '$date', '$date');";
			mysql_query($sql) or ($fail = mysql_error());
			if($fail === false) {
				$sql = "SELECT playerId FROM players WHERE email='$email' LIMIT 1;";
				$result = mysql_query($sql) or ($fail = mysql_error());
				if($fail === false) {
					if($row = mysql_fetch_assoc($result)) {
						// player exists get the playerId
						$playerId = $row['playerId'];
					} else {
						$fail = 'the imposible happened';
					}
				}
			}
		}
	}
}

// create session
if($fail === false) {
	session_start();
	$_SESSION['playerId'] = intval($playerId);
	header("Location: http://stanfordassassins.com/StanfordAssassins.html");
} else {
?>
<html>
<head>
    <title>Stanford Assassins</title>
    <style type="text/css">
    body {
        font-family: Helvetica, Arial, sans-serif;
        font-size: 18px;
    }
    
    h1 {
      font-size: 40px;
      font-weight: bold;
      color: #777777;
      margin-top: 10px;
      margin-bottom: 30px;
      text-align: center;
    }
    </style>
</head>
<body style="text-align: center; padding-top: 200px;">
	<h1>Stanford Assassins</h1>
	<br/>
	<a href="http://webauth.stanfordassassins.com">Login with WebAuth</a>
	<!-- FAIL: <?= $fail ?> -->
</body>
</html>
<?php
}




/**
 * XOR encrypts a given string with a given key phrase.
 *
 * @param     string    $InputString    Input string
 * @param     string    $KeyPhrase      Key phrase
 * @return    string    Encrypted string    
 */    
function XOREncryption($InputString, $KeyPhrase){
 
    $KeyPhraseLength = strlen($KeyPhrase);
 
    // Loop trough input string
    for ($i = 0; $i < strlen($InputString); $i++){
 
        // Get key phrase character position
        $rPos = $i % $KeyPhraseLength;
 
        // Magic happens here:
        $r = ord($InputString[$i]) ^ ord($KeyPhrase[$rPos]);
 
        // Replace characters
        $InputString[$i] = chr($r);
    }
 
    return $InputString;
}
 
// Helper functions, using base64 to
// create readable encrypted texts:
 
function XOREncrypt($InputString, $KeyPhrase){
    $InputString = XOREncryption($InputString, $KeyPhrase);
    $InputString = base64_encode($InputString);
    return $InputString;
}
 
function XORDecrypt($InputString, $KeyPhrase){
    $InputString = base64_decode($InputString);
    $InputString = XOREncryption($InputString, $KeyPhrase);
    return $InputString;
}
?>