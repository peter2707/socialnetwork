<?php
require 'linkedin_provider.php';

$clientId = '86lp9sdvq4owae';
$clientSecret = ***REMOVED***;
$redirectUri = 'http://localhost/oauth/linkedin_callback.php';

$linkedinProvider = new LinkedInProvider($clientId, $clientSecret, $redirectUri);

// Generate authorization URL
$authorizationUrl = $linkedinProvider->getAuthorizationUrl();

// Redirect user to LinkedIn for authentication
header('Location: ' . $authorizationUrl);
exit;
?>