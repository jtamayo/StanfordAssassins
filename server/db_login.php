<?php
$db_hostspec = "localhost"; 
$db_database = "oguievet_assassins";
$db_username = "oguievet_stanass";
$db_password = "hs)kLk.725&+";

mysql_connect($db_hostspec, $db_username, $db_password) or die('Problem 1'); 
mysql_select_db($db_database) or die('Problem 2');
mysql_query("SET NAMES 'utf8'");
?>