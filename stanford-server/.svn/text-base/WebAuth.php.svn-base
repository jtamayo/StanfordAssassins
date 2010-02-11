#!/usr/bin/php
<?php
//header('Content-Type: text/plain; charset=utf-8');
$HASH_SALT = "OH NO! The fight's out,I'ma 'bout to punch yo...lights out,Get the FUCK back, guard ya grill,There's somethin' wrong, we can't stay still,I've been drankin' and bustin' two,and I been thankin' of bustin' you,Upside ya motherfuckin' forehead";
$XOR_KEY = "Causin' confusion, Disturbin Tha Peace,It's not an illusion, we runnin the streets,So bye-bye to all you groupies and golddiggers,Is there a bumper on your ass? NO NIGGA!,I'm doin' a hundred on the highway,So if you do the speed limit, get the FUCK outta my way,I'm D.U.I., hardly ever caught sober,and you about to get ran the FUCK over";

$message = $_ENV['WEBAUTH_LDAP_DISPLAYNAME'] . '|' . $_ENV['WEBAUTH_LDAP_MAIL'] . '|' . $_ENV['WEBAUTH_LDAPAUTHRULE'] . '|' . time();
$messageSign = md5($message . $HASH_SALT) . $message;
$encrypt = XOREncrypt($messageSign, $XOR_KEY);

if(isset($_GET['dev']) {
    header("Location: http://stanfordassassins.com/dev/login.php?r=$encrypt");
} else {
    header("Location: http://stanfordassassins.com/login.php?r=$encrypt");
}
exit(0);

/**
 * XOR encrypts a given string with a given key phrase
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