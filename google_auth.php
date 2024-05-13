<?php
require_once 'vendor/autoload.php';
require 'functions/functions.php';

$factory = new RandomLib\Factory;
$generator = $factory->getGenerator(new SecurityLib\Strength(SecurityLib\Strength::MEDIUM));

session_start();
if (isset($_SESSION['user_id'])) {
    header("location:home.php");
}
session_destroy();
session_start();

/**
 * This page will capture the POST callback from google witch includes the 
 * ID token and verify it.
 */
$client = new Google_Client(['client_id' => '160246886063-63gv7rldj6b5vcgs88h2rqpkmvi31a8e.apps.googleusercontent.com']);

$id_token = $_POST['credential'];
$conn = connect();

// Check for cookie in consecutive tries.
if (isset($id_token)) {
    $verifiedPayload = $client->verifyIdToken($id_token);
    if ($verifiedPayload) {
        list($header, $payload, $signature) = explode('.', $id_token);
        $jsonToken = base64_decode($payload);
        $arrayToken = json_decode($jsonToken, true);
        if ($arrayToken['email']) {
            // Check if the email is already existing in our DB.
            $query = mysqli_query($conn, "SELECT * FROM users WHERE user_email ='" . $arrayToken['email'] . "'");
            //        print  $query;
            if ($query) {
                // If a user is existing with this email address.
                if (mysqli_num_rows($query) == 1) {
                    // We automatically link the user accounts no prompt is given
                    // because if there were to be multiple accounts most existing
                    // code especially "Search function" would have to be modified.
                    $row = mysqli_fetch_assoc($query);
                    // Here we update information of the user, based on the response. 
                    // if changes are found they take precedence over existing ones.
                    $updatedData = array();
                    $_SESSION['user_id'] = $row['user_id'];
                    if (isset($arrayToken['given_name'])) {
                        if (strtolower($arrayToken['given_name']) != strtolower($row['user_firstname'])) {
                            $updatedData["user_firstname"] = $arrayToken['given_name'];
                        }
                    }
                    if (isset($arrayToken['family_name'])) {
                        if (strtolower($arrayToken['family_name']) != strtolower($row['user_lastname'])) {
                            $updatedData["user_lastname"] = $arrayToken['family_name'];
                        }
                    }
                    if (sizeof($updatedData) > 0) {
                        $update_string = "";
                        $count = 0;
                        foreach ($updatedData as $tbl_n => $tbl_val) {
                            $update_string .= "" . $tbl_n . "='" . $tbl_val . "' ";
                            $count++;
                            if ($count != sizeof($updatedData)) {
                                $update_string .= ",";
                            }
                        }
                        $update_query_string = "UPDATE socialnetwork.users SET " . $update_string . " WHERE user_email='" . $arrayToken['email'] . "'";
                        $update_query = mysqli_query($conn, $update_query_string);
                        if ($update_query) {
                            $_SESSION['user_name'] = $arrayToken['given_name'] . " " . $arrayToken['family_name'];
                        } else {
                            echo mysqli_error($conn);
                        }
                    } else {
                        $_SESSION['user_name'] = $row['user_firstname'] . " " . $row['user_lastname'];
                    }
                    header("location:home.php");
                } else {
                    // This is a new user
                    // As of now the required information for a user are 
                    // firstname, lastname, password, email,gender and birthdate,
                    // If these information are not present we will genreate random values for them and insert for
                    // the time being.

                    // Holds the information to be captured from the user.
                    // We assume email is always a given.
                    $requiredData = array();
                    if (!isset($arrayToken['given_name'])) {
                        array_push($requiredData, 'user_firstname');
                        $arrayToken['given_name'] = "user" . $generator->generateString(10);
                    }
                    if (!isset($arrayToken['family_name'])) {
                        array_push($requiredData, 'user_lastname');
                        $arrayToken['family_name'] = $generator->generateString(5);
                    }
                    if (!isset($arrayToken['dob'])) {
                        array_push($requiredData, 'user_birthdate');
                        // default birthdate will be an impossible one 
                        $arrayToken['dob'] = date_create('1900-01-01');
                    }
                    if (!isset($arrayToken['gender'])) {
                        array_push($requiredData, 'user_gender');
                        $arrayToken['gender'] = 'NA';
                    }
                    $random_password = $generator->generateRandomString(20);
                    $insertQueryString = "INSERT INTO socialnetwork.users
                    (user_firstname, user_lastname, user_nickname, user_password, user_email, user_gender, user_birthdate)
                    VALUES('" . $arrayToken['given_name'] . "', '" . $arrayToken['family_name'] . "', '" . $random_password . "', '" . $arrayToken['email'] . "', '" . $arrayToken['gender'] . "', '" . $arrayToken['dob'] . "');
                    ";
                    $insertQuery = mysqli_query($conn, $insertQueryString);
                    if ($insertQuery) {
                        $_SESSION['user_name'] = $arrayToken['given_name'] . " " . $arrayToken['family_name'];
                    } else {
                        echo mysqli_error($conn);
                    }
                    if (count($requiredData) > 0) {
                        // go to required data page
                    } else {
                        header("location:home.php");
                    }
                }
            } else {
                echo mysqli_error($conn);
            }
        }
    } else {
        // Invalid ID token
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login with Google</title>
</head>

<body>
    <!-- <?php if (isset($authUrl)): ?>
        <a href="<?php echo $authUrl; ?>">Login with Google</a>
    <?php else: ?>
        <p>You are logged in with Google.</p>
    <?php endif; ?> -->
</body>

</html>