<?php
class LinkedInProvider {
    private $clientId;
    private $clientSecret;
    private $redirectUri;

    public function __construct($clientId, $clientSecret, $redirectUri) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
    }

    public function getAuthorizationUrl() {
        $authorizationUrl = 'https://www.linkedin.com/oauth/v2/authorization?' . http_build_query([
            'response_type' => 'code',
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'state' => uniqid('', true), // Generate a unique state parameter
            'scope' => 'openid profile email',
        ]);
        return $authorizationUrl;
    }

    public function getAccessToken($code) {
        $url = 'https://www.linkedin.com/oauth/v2/accessToken';
        $data = [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $this->redirectUri,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ];
    
        // URL encode the data
        $postData = http_build_query($data);
    
        $response = $this->sendPostRequest($url, $postData);
        $accessTokenResponse = json_decode($response);
    
        if (isset($accessTokenResponse->access_token)) {
            // Access token successfully retrieved
            return $accessTokenResponse->access_token;
        } else {
            // Handle error retrieving access token
            error_log("Error retrieving access token: " . json_encode($accessTokenResponse));
            return false;
        }
    }

    public function getUserProfile($accessToken) {
        $url = 'https://api.linkedin.com/v2/userinfo';
        $headers = [
            'Authorization: Bearer ' . $accessToken,
            'Accept: application/json',
        ];
    
        $response = $this->sendGetRequest($url, $headers);
        $userProfile = json_decode($response);
    
        if ($userProfile && !isset($userProfile->serviceErrorCode)) {
            // User profile retrieved successfully
            return $userProfile;
        } else {
            // Handle error retrieving user profile
            error_log("Error retrieving user profile: " . json_encode($userProfile));
            return false;
        }
    }

    private function sendPostRequest($url, $data) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    private function sendGetRequest($url, $headers) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}
?>