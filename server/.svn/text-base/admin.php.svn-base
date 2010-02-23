<html>
<head>
    <title>Stanford Assassins: Admin Interface</title>
</head>
<body>
<?php
if(isset($_POST['pass'])) {
	$expire = time() + 24*60*60;
	$loghash = md5(md5($_POST['pass'] . 'SALT1') . 'I love ducks');
	setcookie(awesome, $loghash, $expire, '/', 'stanfordassassins.com');
} else if(isset($_COOKIE['awesome'])) {
	$loghash = $_COOKIE['awesome'];
} else {
	$loghash = 'Please log in!';
}

$login = ($loghash == '191b45780be90afacac6028492be0d1d');
if($login) {
	
?>
	<form action="<?= $_SERVER['PHP_SELF']?>" method="post">
	Select what you want to do:<br/>
	<input type="radio" name="cmd" value="showActiveGames"/> Show active games<br/>
	<textarea name="extra" rows="8" cols="80"></textarea><br/>
	<input type="submit" value="Do it!"><br/>
	</form>
<?php	

	$cmd = isset($_GET['cmd'])?$_GET['cmd']:'none';
	$extra = isset($_GET['extra'])?$_GET['extra']:'';
	require_once('db_login.php');
	
	switch($cmd) {
	case 'showActiveGames':
		$sql = "SELCET gameId, name FROM games WHERE state = 'ACTIVE';";
		$result = mysql_query($sql) or mysql_error();
		echo "<table>\n";
		echo "<tr><th>gameId</th><th>Game Name</th></tr>\n";
		while($row = mysql_fetch_assoc($result)) {
			echo "<tr><td>$row[gameId]</td><td>$row[name]</td></tr>\n";
		}
		echo "</table>\n";
		break;
	
	default:
		break;
	}
} else {
?>
	<form action="<?= $_SERVER['PHP_SELF']?>" method="get">
	Go on, log in:<br/>
	Password: <input type="password" name="pass"><br/>
	<input type="submit" value="Log in"><br/>
	</form>
<?php	
}
?>
</body>
</html>