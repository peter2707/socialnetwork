<?php
require 'LinkedInOAuth.php';

$clientId = '86lp9sdvq4owae';
$clientSecret = 'vevCs1ULLQ476G3N';
$redirectUri = 'http://localhost/oauth/linkedin_callback.php';

// Initialize LinkedInOAuth class
$linkedinOAuth = new LinkedInOAuth($clientId, $clientSecret, $redirectUri);

// Generate authorization URL
$authorizationUrl = $linkedinOAuth->getAuthorizationUrl();

// Redirect user to LinkedIn for authentication
header('Location: ' . $authorizationUrl);
exit;
?>