<?php
	session_start(); 
	session_destroy();
?>
<html>
<head>
    <title>Stanford Logout</title>
    <style type="text/css">
    body {
        font-family: Helvetica, Arial, sans-serif;
        font-size: 18px;
    }
    </style>
</head>
<body style="text-align: center; padding-top: 200px;">
	You have logged out of Stanford Assassins but you are still logged into WebAuth.<br/>
	To log out of WebAuth, close your browser.
	<br/>
	<br/>
	<a href="login.php">Go to the login page</a>
</body>
</html>