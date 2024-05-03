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
            $userEmail = $userProfile->email;

            // Check if the user email exists in the database
            $conn = connect();
            $query = mysqli_query($conn, "SELECT * FROM users WHERE user_email = '$userEmail'");

            if ($query) {
                if (mysqli_num_rows($query) == 0) {
                    // User email doesn't exist, register the user
                    $userFirstName = $userProfile->given_name;
                    $userLastName = $userProfile->family_name;

                    // Insert user into the database
                    $sql = "INSERT INTO users (user_firstname, user_lastname, user_email) VALUES ('$userFirstName', '$userLastName', '$userEmail')";
                    $insertQuery = mysqli_query($conn, $sql);

                    if ($insertQuery) {
                        // User registered successfully, proceed with login
                        $userId = mysqli_insert_id($conn);
                        $_SESSION['user_id'] = $userId;
                        $_SESSION['user_name'] = $userFirstName . ' ' . $userLastName;
                        header("location: home.php");
                        exit(); // Exit to prevent further execution
                    } else {
                        // Error occurred while registering user
                        echo "Error registering user. Please try again later.";
                    }
                } else {
                    // User email already exists, show error
                    echo "User with this email already exists. Please login with your credentials.";
                }
            } else {
                // Error occurred while checking email existence
                echo "Error checking email existence. Please try again later.";
            }
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
