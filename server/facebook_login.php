<?php
include "facebook.php";



function getFacebookConnector() {
$appapikey = '920537198948cb508dd7f38ca4b1a53b';
$appsecret = '1df24d2f3941380932ead0268ac31278';
$session_token = 'P711BE';
$facebook_userid = '1768790698' ;
$facebook_infinite_session_key = 'b0a5a1c8d1b3bd89d9467c25-1768790698' ;

$facebook= new Facebook($appapikey, $appsecret,$facebook_infinite_session_key);
$facebook->api_client->user = $facebook_userid;
$facebook->api_client->expires = 0;
return $facebook;
}
?>