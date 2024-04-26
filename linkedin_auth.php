<?php
require 'LinkedInOAuth.php';

$clientId = '86lp9sdvq4owae';
$clientSecret = ***REMOVED***;
$redirectUri = 'http://localhost/oauth/linkedin_callback.php';

// Initialize LinkedInOAuth class
$linkedinOAuth = new LinkedInOAuth($clientId, $clientSecret, $redirectUri);

// Generate authorization URL
$authorizationUrl = $linkedinOAuth->getAuthorizationUrl();

// Redirect user to LinkedIn for authentication
header('Location: ' . $authorizationUrl);
exit;
?>