<?php
$twitter_username	='su_assassins';
$twitter_psw		='vadisgay';
include "twitter.php";

function getTwitterConnector() {
global $twitter_username ;
global $twitter_psw;
return new Twitter($twitter_username,$twitter_psw);
}
?>