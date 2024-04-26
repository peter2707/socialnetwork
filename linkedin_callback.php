<?php
//Filename: linkedin_callback.php
require 'LinkedInOAuth.php';

// Initialize LinkedInOAuth class with your credentials
$clientId = '86lp9sdvq4owae';
$clientSecret = 'vevCs1ULLQ476G3N';
$redirectUri = 'http://localhost/oauth/linkedin_callback.php';

$linkedinOAuth = new LinkedInOAuth($clientId, $clientSecret, $redirectUri);

if (isset($_GET['code'])) {
    $code = $_GET['code'];
    $accessToken = $linkedinOAuth->getAccessToken($code);

    if ($accessToken) {
        $userProfile = $linkedinOAuth->getUserProfile($accessToken);

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