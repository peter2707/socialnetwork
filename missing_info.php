<?php

session_start();

/**
 *This page will be navigated to once a user has logged in or signed up using a social IDP.
 * And capture the missing information that would usually be captured during manual signup
 */
$client = new Google_Client(['client_id' => '160246886063-63gv7rldj6b5vcgs88h2rqpkmvi31a8e.apps.googleusercontent.com']);

$id_token = $_POST['credential'];

// Check for cookie in consecutive tries.
if (isset($id_token)) {
    $verifiedPayload = $client->verifyIdToken($id_token);
    if ($verifiedPayload) {
        list($header, $payload, $signature) = explode('.', $id_token);
        $jsonToken = base64_decode($payload);
        $arrayToken = json_decode($jsonToken, true);
        print_r($arrayToken);
        // $userid = $payload['sub'];
        // echo ($userid . "" . $id_token . "");
    } else {
        // Invalid ID token
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Mandatory and Additional Information</title>
</head>

<body>
    <form method="post" onsubmit="return validateRegister()">
        <!--Package One-->
        <h2>Highly Required Information</h2>
        <hr>
        <!--First Name-->
        <label>First Name<span>*</span></label><br>
        <input type="text" name="userfirstname" id="userfirstname">
        <div class="required"></div>
        <br>
        <!--Last Name-->
        <label>Last Name<span>*</span></label><br>
        <input type="text" name="userlastname" id="userlastname">
        <div class="required"></div>
        <br>
        <!--Nickname-->
        <label>Nickname</label><br>
        <input type="text" name="usernickname" id="usernickname">
        <div class="required"></div>
        <br>
        <!--Password-->
        <label>Password<span>*</span></label><br>
        <input type="password" name="userpass" id="userpass">
        <div class="required"></div>
        <br>
        <!--Confirm Password-->
        <label>Confirm Password<span>*</span></label><br>
        <input type="password" name="userpassconfirm" id="userpassconfirm">
        <div class="required"></div>
        <br>
        <!--Email-->
        <label>Email<span>*</span></label><br>
        <input type="text" name="useremail" id="useremail">
        <div class="required"></div>
        <br>
        <!--Birth Date-->
        Birth Date<span>*</span><br>
        <select name="selectday">
            <?php
            for ($i = 1; $i <= 31; $i++) {
                echo '<option value="' . $i . '">' . $i . '</option>';
            }
            ?>
        </select>
        <select name="selectmonth">
            <?php
            echo '<option value="1">January</option>';
            echo '<option value="2">February</option>';
            echo '<option value="3">March</option>';
            echo '<option value="4">April</option>';
            echo '<option value="5">May</option>';
            echo '<option value="6">June</option>';
            echo '<option value="7">July</option>';
            echo '<option value="8">August</option>';
            echo '<option value="9">September</option>';
            echo '<option value="10">October</option>';
            echo '<option value="11">Novemeber</option>';
            echo '<option value="12">December</option>';
            ?>
        </select>
        <select name="selectyear">
            <?php
            for ($i = 2017; $i >= 1900; $i--) {
                if ($i == 1996) {
                    echo '<option value="' . $i . '" selected>' . $i . '</option>';
                }
                echo '<option value="' . $i . '">' . $i . '</option>';
            }
            ?>
        </select>
        <br><br>
        <!--Gender-->
        <input type="radio" name="usergender" value="M" id="malegender" class="usergender">
        <label>Male</label>
        <input type="radio" name="usergender" value="F" id="femalegender" class="usergender">
        <label>Female</label>
        <div class="required"></div>
        <br>
        <!--Hometown-->
        <label>Hometown</label><br>
        <input type="text" name="userhometown" id="userhometown">
        <br>
        <!--Package Two-->
        <h2>Additional Information</h2>
        <hr>
        <!--Marital Status-->
        <input type="radio" name="userstatus" value="S" id="singlestatus">
        <label>Single</label>
        <input type="radio" name="userstatus" value="E" id="engagedstatus">
        <label>Engaged</label>
        <input type="radio" name="userstatus" value="M" id="marriedstatus">
        <label>Married</label>
        <br><br>
        <!--About Me-->
        <label>About Me</label><br>
        <textarea rows="12" name="userabout" id="userabout"></textarea>
        <br><br>
        <input type="submit" value="Create Account" name="register">
    </form>
</body>