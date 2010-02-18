<?php
	function testEmail() {
		$subject  = "This is a test";
		$body = <<<END_OF_BODY
<p>This is a link: <a href="http://stanfordassassins.com/?t=MyStats">Go to stats</a></p>

<p>Hope it works</p>
END_OF_BODY;
		return sendHtmlEmail('vad@stanford.edu', $subject, $body);		
	}
	
	function sendDeathmatch($email, $gameName, $otherPlayerName) {
		$gameTag = str_replace(' ', '', $gameName);
		$subject  = "IT'S ON!";
		$body = <<<END_OF_BODY
<p>You and $otherPlayerName are the last two players in $gameName.</p>

<p>Whoever assassinates the other wins this game.</p>

<p>You both have 96 hours.</p>

<p>Go to the <a href="http://stanfordassassins.com/?t=$gameTag">game page</a>.</p>
END_OF_BODY;
		return sendHtmlEmail($email, $subject, $body);		
	}
	
	function sendTimeOut($email, $gameName) {
		$gameTag = str_replace(' ', '', $gameName);
		$subject  = "You have been removed from $gameName";
		$body = <<<END_OF_BODY
<p>Your time to make an assassination has ended.</p>

<p>You are no longer playing in $gameName, but you can still <a href="http://stanfordassassins.com/?t=$gameTag">spectate it</a>.</p>

<p>To participate in a new game, go to the <a href="http://stanfordassassins.com/?t=Join">join page</a>.</p>
END_OF_BODY;
		return sendHtmlEmail($email, $subject, $body);		
	}

	function sendGameStarted($email, $gameId, $gameName, $codeword, $targetAlias, $targetName) {
		$gameTag = str_replace(' ', '', $gameName);
		$subject  = "$gameName has started";
		$body = <<<END_OF_BODY
<p>$gameName has begun.</p>

<p>Your codeword for this game is <b>$codeword</b>; keep it on you at all times.</p>

<p>Your target for now is $targetName ($targetAlias). You have 96 hours to complete and report the assassination.</p>

<p>The guild should not need to remind you to stay within the rules at all times. You can view the rules <a href="http://stanfordassassins.com/rules.html">here</a></p>

<p>Go to the <a href="http://stanfordassassins.com/?t=$gameTag">game page</a>.</p>

<p>Remember, someone out there is out to get you so trust no one.</p>

<p>Good luck... You’ll need it.</p>
END_OF_BODY;
		return sendHtmlEmail($email, $subject, $body);
	}
	
	function sendBeenAssassinated($email, $assassinAlias, $assassinName, $details) {
		$gameTag = str_replace(' ', '', $gameName);
		$subject  = 'You have been assassinated';
		$body = <<<END_OF_BODY
<p>You have been assassinated by $assassinName (aka $assassinAlias).</p>

<p>$assassinName described the kill as: <em>"$details"</em></p>

<p>Let’s hope that you can learn from this.</p>

<p>You can still <a href="http://stanfordassassins.com/?t=$gameTag">spectate $gameName</a>.</p>

<p>To participate in a new game, go to the <a href="http://stanfordassassins.com/?t=Join">join page</a>.</p>

<p>Better luck next time.</p>
END_OF_BODY;
		return sendHtmlEmail($email, $subject, $body);
	}

	function sendAssassinIsOut($email, $assassinAlias, $assassinName, $details) {
		$subject  = "Someone else is after you";
		$body = <<<END_OF_BODY
<p>Your assassin, $assassinName (aka $assassinAlias), is out of the game.</p>

<p>There is someone new chasing you.</p>

<p>Good luck.</p>
END_OF_BODY;
		return sendHtmlEmail($email, $subject, $body);
	}
	
	
	function sendTargetChanged($email, $gameName, $targetAlias, $targetName) {
		$gameTag = str_replace(' ', '', $gameName);
		$subject  = "Your target has changed";
		$body = <<<END_OF_BODY
<p>Your new target is $targetName (who goes by the alias of $targetAlias). The assassination timer has been reset to 96 hours.</p>

<p>The guild should not need to remind you to stay within <a href="http://stanfordassassins.com/rules.html">the rules</a> at all times.</p>

<p>Go to the <a href="http://stanfordassassins.com/?t=$gameTag">game page</a>.</p>
END_OF_BODY;
		return sendHtmlEmail($email, $subject, $body);
	}
	
	function sendGameOver($email, $gameId, $gameName, $winnerAlias='', $winnerName='') {
		$gameTag = str_replace(' ', '', $gameName);
		$subject  = "$gameName has ended";
		if($winnerAlias=='') {
			$body = <<<END_OF_BODY
<p>$gameName has ended without a winner.</p>

<p>Go to the <a href="http://stanfordassassins.com/?t=$gameTag">game page</a>.</p>
END_OF_BODY;
		} else {
			$body = <<<END_OF_BODY
<p>$gameName has been won by $winnerName ($winnerAlias).</p>

<p>Go to the <a href="http://stanfordassassins.com/?t=$gameTag">game page</a>.</p>
END_OF_BODY;
		}
		return sendHtmlEmail($email, $subject, $body);
	}
	
	function sendGameWon($email, $gameId, $gameName) {
		$subject  = "You won $gameName";
		$body = <<<END_OF_BODY
<p>Congratulations on winning $gameName!</p>

<p>Go to the <a href="http://stanfordassassins.com/?t=$gameTag">game page</a>.</p>

<p>To participate in a new game, go to the <a href="http://stanfordassassins.com/?t=Join">join page</a>.</p>
END_OF_BODY;
		return sendHtmlEmail($email, $subject, $body);
	}
	
	function sendBeenDispute($email, $uid1, $player1, $claim1, $uid2, $player2, $claim2, $disputeId) {
		$subject  = 'There has been a dispute';
		$body = <<<END_OF_BODY
<p>$player1 has a dispute with $player2</p>

$player1 claims that:
<p>$claim1</p>

$player2 claims that:
<p>$claim2</p>

Who do you think is right?
<form action="http://stanfordassassins.com/gameserver.php" method="get">
	<input type="hidden" name="cmd" value="dispute-judge"/>
	<input type="hidden" name="disputeId" value="$disputeId"/>
	<input type="hidden" name="vote" value="$uid1"/>
	<input type="submit" value="$player1 is right" />
</form>
<form action="http://stanfordassassins.com/gameserver.php" method="get">
	<input type="hidden" name="cmd" value="dispute-judge"/>
	<input type="hidden" name="disputeId" value="$disputeId"/>
	<input type="hidden" name="vote" value="$uid2"/>
	<input type="submit" value="$player2 is right" />
</form>
<form action="http://stanfordassassins.com/gameserver.php" method="get">
	<input type="hidden" name="cmd" value="dispute-judge"/>
	<input type="hidden" name="disputeId" value="$disputeId"/>
	<input type="hidden" name="vote" value="0"/>
	<input type="submit" value="Flip a coin!" />
</form>
END_OF_BODY;
		return sendHtmlEmail($email, $subject, $body);
	}
	
	function sendHtmlEmail($email, $subject, $body) {
		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$headers .= "From: Stanford Assassins <admin@stanfordassassins.com>\r\n";
		$message = <<<END_OF_EMAIL
<html>
	<head>
		<title>Stanford Assassins</title>
	</head>
	<body>		
		$body
		
		<p>
		The Game Master,
		</p>
		<p>
		StanfordAssassins.com
		</p>
	</body>
</html>
END_OF_EMAIL;
		if(strstr($email, 'test.edu') === false) {
			return mail($email, $subject, $message, $headers);
		} else {
			echo "==== START SEND EMAIL =====================\n";
			echo "TO: $email\n";
			echo "SUBJECT: $subject\n";
			echo "$body\n";
			echo "===== END SEND EMAIL ======================\n";
			return true;
		}
	}
?>
