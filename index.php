<!-- made by monarch60 -->
<?php

$cmd = escapeshellcmd('python3 scripts/getusr.py');
shell_exec($cmd);

session_start();

function get_config( $int1) {
    $configContent = file_get_contents("/var/www/html/auth.config");
    if ($configContent === false) {
        return null;
    }
    $list1 = explode("\n", trim($configContent));
    if (count($list1) < $int1) {
        return null;
    }
    $substring = $list1[$int1 - 1];
    $array2 = explode("\t", $substring);
    return end($array2);
}

function isTwitterBot($userAgent) {
    $twitterBotUserAgent = 'Twitterbot';
    return stripos($userAgent, $twitterBotUserAgent) !== false;
}

if (isset($_SERVER['HTTP_USER_AGENT']) && isTwitterBot($_SERVER['HTTP_USER_AGENT'])) {
    header($get_config(3));
    exit;
}

require "vendor/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;

$consumerKey = get_config(1);
$consumerSecret = get_config(2);
$callbackUrl = get_config(3);

$twitterOAuth = new TwitterOAuth($consumerKey, $consumerSecret);

$requestToken = $twitterOAuth->oauth('oauth/request_token', array('oauth_callback' => $callbackUrl));

$_SESSION['oauth_token'] = $requestToken['oauth_token'];
$_SESSION['oauth_token_secret'] = $requestToken['oauth_token_secret'];

$authUrl = $twitterOAuth->url('oauth/authenticate', array('oauth_token' => $requestToken['oauth_token']));

header('Location: ' . $authUrl);
exit;

?>
