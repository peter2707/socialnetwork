<?php
require 'linkedin_provider.php';
require 'functions/functions.php';
session_start();

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
            // Save user profile data into the database
            $userId = saveUserProfileToDatabase($userProfile);

            // Redirect to home.php
            header("Location: home.php");
            exit;
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

// Function to save user profile data into the database and return the userId
function saveUserProfileToDatabase($userProfile) {
    // Connect to the database
    $conn = connect();

    $user_details = (object) [
        'sub' => $userProfile->sub,
        'email_verified' => $userProfile->email_verified,
        'name' => $userProfile->name,
        'country' => $userProfile->locale->country,
        'language' => $userProfile->locale->language,
        'givenName' => $userProfile->given_name,
        'familyName' => $userProfile->family_name,
        'email' => $userProfile->email,
        'picture' => $userProfile->picture
    ];
    
    // Save user_details object into session
    $_SESSION['user_details'] = $user_details;

    $email = $userProfile->email;

    $query = mysqli_query($conn, "SELECT user_email FROM users WHERE user_email = '$email'");
    if (mysqli_fetch_assoc($query)) {
        $row = mysqli_fetch_assoc($query);
        $query = mysqli_query($conn, "SELECT user_id FROM users WHERE user_email = '$email'");
        $row = mysqli_fetch_assoc($query);
        $_SESSION['user_id'] = $row['user_id'];
        return $row['user_id'];
    } else {
        header("Location: additional_details.php");
        exit;
    }
}
?>
