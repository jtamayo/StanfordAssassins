<?php
	function sendGameStarted($email, $gameId, $gameName, $codeword, $targetAlias, $targetName) {
		$subject  = "$gameName has started";
		$body = <<<END_OF_BODY
<p>$gameName has begun.</p>

<p>Your codeword for this game is <b>$codeword</b>; keep it on you at all times.</p>

<p>Your target for now is $targetName ($targetAlias). You have 96 hours to complete and report the assassination.</p>

<p>The guild should not need to remind you to stay within the rules at all times. You can view the rules here: http://stanfordassassins.com/rules.html</p>

<p>To go to the game page click here: http://stanfordassassins.com</p>

<p>Remember, someone out there is out to get you so trust no one.</p>

<p>Good luck... You’ll need it.</p>
END_OF_BODY;
		return sendHtmlEmail($email, $subject, $body);
	}
	
	function sendBeenAssassinated($email, $assassinAlias, $assassinName, $details) {
		$subject  = 'You have been assassinated';
		$body = <<<END_OF_BODY
<p>You have been assassinated by $assassinName (aka $assassinAlias).</p>

<p>$assassinName described the kill as: <em>"$details"</em></p>

<p>Let’s hope that you can learn from this.</p>

<p>To join the next game, go to: http://stanfordassassins.com</p>

<p>Better luck next time.</p>
END_OF_BODY;
		return sendHtmlEmail($email, $subject, $body);
	}

	function sendAssassinIsOut($email, $assassinAlias, $assassinName, $details) {
		$subject  = 'Someone else is after you';
		$body = <<<END_OF_BODY
<p>Your assassin, $assassinName (aka $assassinAlias), is out of the game.</p>

<p>There is someone new chasing you.</p>

<p>Good luck.</p>
END_OF_BODY;
		return sendHtmlEmail($email, $subject, $body);
	}
	
	
	function sendTargetChanged($email, $gameId, $targetAlias, $targetName) {
		$subject  = 'Your target has changed';
		$body = <<<END_OF_BODY
<p>Your new target is $targetName (who goes by the alias of $targetAlias). The assassination timer has been reset to 96 hours.</p>

<p>The guild should not need to remind you to stay within the rules at all times. (http://stanfordassassins.com/rules.html)</p>

<p>To go to your game page click here: http://stanfordassassins.com</p>
END_OF_BODY;
		return sendHtmlEmail($email, $subject, $body);
	}
	
	function sendGameOver($email, $gameId, $gameName, $winnerAlias, $winnerName) {
		$subject  = "$gameName has ended";
		$body = <<<END_OF_BODY
<p>$gameName has been won by $winnerName ($winnerAlias).</p>
END_OF_BODY;
		return sendHtmlEmail($email, $subject, $body);
	}
	
	function sendGameWon($email, $gameId, $gameName) {
		$subject  = "You won $gameName";
		$body = <<<END_OF_BODY
<p>Congratulations on winning $gameName!</p>
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
		return mail($email, $subject, $message, $headers);
	}
?>
