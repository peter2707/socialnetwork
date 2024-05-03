<?php
require '../socialnetwork/functions/functions.php';
session_start();
require '../socialnetwork/linkedin_provider.php';

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
            header("Location: ../socialnetwork/home.php");
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

    // Extract user profile data
    $sub = $userProfile->sub;
    $emailVerified = $userProfile->email_verified;
    $name = $userProfile->name;
    $country = $userProfile->locale->country;
    $language = $userProfile->locale->language;
    $givenName = $userProfile->given_name;
    $familyName = $userProfile->family_name;
    $email = $userProfile->email;
    $picture = $userProfile->picture;

    $query = mysqli_query($conn, "SELECT user_email FROM users WHERE user_email = '$email'");
    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        if ($useremail == $row['user_email']) {
            $query = mysqli_query($conn, "SELECT user_id FROM users WHERE user_email = '$email'");
            $row = mysqli_fetch_assoc($query);
            $_SESSION['user_id'] = $row['user_id'];
            header("Location: ../socialnetwork/home.php");
        }
    }

    // Constructing the SQL INSERT query
    $sql = "INSERT INTO users(user_firstname, user_lastname, user_nickname, user_password, user_email, user_gender, user_birthdate, user_status, user_about, user_hometown)
    VALUES ('$givenName', '$familyName', NULL, NULL, '$email', NULL, NULL, NULL, NULL, '$country')";

    // Add other necessary fields from $userProfile

    $query = mysqli_query($conn, $sql);

    // Check if insertion was successful
    if ($query) {
        // Get the userId of the newly inserted user
        $query = mysqli_query($conn, "SELECT user_id FROM users WHERE user_email = '$email'");
        $row = mysqli_fetch_assoc($query);
        $_SESSION['user_id'] = $row['user_id'];
        return $row['user_id'];
    } else {
        // Error handling
        echo "Error: " . mysqli_error($conn);
        return null;
    }
}
?>
