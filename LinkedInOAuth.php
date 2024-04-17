<?php

class LinkedInOAuth {
    private $clientId = '86lp9sdvq4owae';
    private $clientSecret = 'XXXXXXXXXX';
    private $redirectUri = 'http://localhost/oauth/home.php';

    public function getAuthorizationUrl() {
        $authorizationUrl = 'https://www.linkedin.com/oauth/v2/authorization?' . http_build_query([
            'response_type' => 'code',
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'state' => uniqid('', true), // Generate a unique state parameter
            'scope' => 'r_liteprofile r_emailaddress',
        ]);
        return $authorizationUrl;
    }
}
