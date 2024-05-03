<?php
require_once 'vendor/autoload.php';
require 'functions/functions.php';

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

                        print ($update_query);
                    }
                    $update_query = mysqli_query($conn, $update_query_string);
                    if($update_query){
                        header("location:home.php");
                    } else {
                        echo mysqli_error($conn);
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