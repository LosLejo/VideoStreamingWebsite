<?php
require_once 'vendor/autoload.php';

$client = new Google_Client();
$client->setClientId('128555133903-hie6tnnvcsom14guhb09qpuglk000h72.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('https://strikeflix.com/google-callback');
$client->addScope("email");
$client->addScope("profile");

$auth_url = $client->createAuthUrl();
header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
exit();
