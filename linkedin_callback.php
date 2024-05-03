<?php
require 'linkedin_provider.php';

$clientId = '86lp9sdvq4owae';
$clientSecret = 'vevCs1ULLQ476G3N';
$redirectUri = 'http://localhost/oauth/linkedin_callback.php';

$linkedinProvider = new LinkedInProvider($clientId, $clientSecret, $redirectUri);

if (isset($_GET['code'])) {
    $code = $_GET['code'];
    $accessToken = $linkedinProvider->getAccessToken($code);

    if ($accessToken) {
        $userProfile = $linkedinProvider->getUserProfile($accessToken);

        if ($userProfile) {
            echo "<pre>";
            print_r($userProfile);
            echo "</pre>";
        } else {
            error_log("Error retrieving user profile.");
            echo "Error retrieving user profile. Please try again later.";
        }
    } else {
        error_log("Error retrieving access token.");
        echo "Error retrieving access token. Please try again later.";
    }
} else {
    error_log("Authorization failed. Code not received.");
    echo "Authorization failed. Code not received.";
}
?>