<?php
require 'linkedin_provider.php';

$clientId = '86lp9sdvq4owae';
$clientSecret = 'vevCs1ULLQ476G3N';
$redirectUri = 'http://localhost/oauth/linkedin_callback.php';

$linkedinProvider = new LinkedInProvider($clientId, $clientSecret, $redirectUri);

// Generate authorization URL
$authorizationUrl = $linkedinProvider->getAuthorizationUrl();

// Redirect user to LinkedIn for authentication
header('Location: ' . $authorizationUrl);
exit;
?>