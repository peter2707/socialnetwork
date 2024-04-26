<?php
require_once 'vendor/autoload.php';

session_start();

/**
 * This page will capture the POST callback from google witch includes the 
 * ID token and verify it.
 */
$client = new Google_Client(['client_id' => '160246886063-63gv7rldj6b5vcgs88h2rqpkmvi31a8e.apps.googleusercontent.com']);

$id_token = $_POST['credential'];

// Check for cookie in consecutive tries.
if (isset($id_token)) {
    $verifiedPayload = $client->verifyIdToken($id_token);
    if ($verifiedPayload) {
        list($header, $payload, $signature) = explode('.', $id_token);
        $jsonToken = base64_decode($payload);
        $arrayToken = json_decode($jsonToken, true);
        print_r($arrayToken);
        // $userid = $payload['sub'];
        // echo ($userid . "" . $id_token . "");
    } else {
        // Invalid ID token
    }
}


// if (isset($_GET['code'])) {
//     $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

//     $_SESSION['access_token'] = $token;
//     header('Location: ' . filter_var('http://localhost/oauth/home.php', FILTER_SANITIZE_URL));
// }

// if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
//     $client->setAccessToken($_SESSION['access_token']);
// } else {
//     $authUrl = $client->createAuthUrl();
// }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login with Google</title>
</head>

<body>
    <!-- <?php if (isset($authUrl)): ?>
        <a href="<?php echo $authUrl; ?>">Login with Google</a>
    <?php else: ?>
        <p>You are logged in with Google.</p>
    <?php endif; ?> -->
</body>

</html>