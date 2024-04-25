<?php
require_once 'vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setClientId('160246886063-63gv7rldj6b5vcgs88h2rqpkmvi31a8e.apps.googleusercontent.com');
$client->setClientSecret('XXXXXXXXXX');
$client->setRedirectUri('http://localhost/oauth/home.php');
$client->addScope('email');

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    
    $_SESSION['access_token'] = $token;
    header('Location: ' . filter_var('http://localhost/oauth/home.php', FILTER_SANITIZE_URL));
}

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
} else {
    $authUrl = $client->createAuthUrl();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login with Google</title>
</head>
<body>
<?php if (isset($authUrl)): ?>
    <a href="<?php echo $authUrl; ?>">Login with Google</a>
<?php else: ?>
    <p>You are logged in with Google.</p>
<?php endif; ?>
</body>
</html>
