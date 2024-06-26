<?php
require 'functions/functions.php';
require 'linkedin_provider.php';
session_start();
if (isset($_SESSION['user_id'])) {
    header("location:home.php");
}
session_destroy();
session_start();
ob_start();
?>
<html>

<head>
    <title>Social Network</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="resources/css/main.css">
    <style>
        .container {
            margin: 40px auto;
            width: 500px;
        }

        .content {
            padding: 30px;
            background-color: white;
            box-shadow: 0 0 5px #4267b2;
        }
    </style>
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/6c8f138a94.js" crossorigin="anonymous"></script>
    <script src="https://accounts.google.com/gsi/client" async></script>
    <script>
        function handleCredentialResponse(response) {
            console.log("Encoded JWT ID token: " + response.credential);
        }
        window.onload = function () {
            google.accounts.id.initialize({
                client_id: "160246886063-63gv7rldj6b5vcgs88h2rqpkmvi31a8e.apps.googleusercontent.com",
                login_uri:"http://localhost/google_auth.php",
                ux_mode: "redirect"
            });
            google.accounts.id.renderButton(
                document.getElementById("google-login-btn"),
                { theme: "outline", size: "large" }  // customization attributes
            );
            google.accounts.id.prompt(); // also display the One Tap dialog
        }
    </script>
    
    <h1>Welcome to Wynch</h1>
    <div class="container">
        <div class="tab">
            <button class="tablink active" onclick="openTab(event,'signin')" id="link1">Login</button>
            <button class="tablink" onclick="openTab(event,'signup')" id="link2">Sign Up</button>
        </div>
        <div class="content">
            <div class="tabcontent" id="signin">
                <form method="post" onsubmit="return validateLogin()">
                    <label>Email<span>*</span></label><br>
                    <input type="text" name="useremail" id="loginuseremail">
                    <div class="required"></div>
                    <br>
                    <label>Password<span>*</span></label><br>
                    <input type="password" name="userpass" id="loginuserpass">
                    <div class="required"></div>
                    <br><br>
                    <input type="submit" value="Login" name="login">
                </form>
                <div class="login-separator">
                    <div class="separator">
                        <hr></hr>
                        <span>OR</span>
                        <hr></hr>
                    </div>
                </div>
            </div>
            <div class="row align-items-start mt-3 mb-3">
                <p class="text-center" style="font-size: 16px;">Continue with</p>
                <div class="col-6">
                    <div id="google-login-btn" class="login-btn"></div>
                </div>
                <div class="col-6">
                    <a href="linkedin_auth.php" class="btn btn-linkedin login-btn"><i class="fa fa-linkedin fa-fw"></i> Sign in with LinkedIn</a>
                </div>
            </div>

            <div class="tabcontent" id="signup">
                <div class="login-separator">
                    <div class="separator">
                        <hr></hr>
                        <span>OR</span>
                        <hr></hr>
                    </div>
                </div>
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
            </div>
        </div>
    </div>
    <script src="resources/js/main.js"></script>
</body>

</html>

<?php
$conn = connect();
if ($_SERVER['REQUEST_METHOD'] == 'POST') { // A form is posted
    if (isset($_POST['login'])) { // Login process
        $useremail = $_POST['useremail'];
        $userpass = md5($_POST['userpass']);
        $query = mysqli_query($conn, "SELECT * FROM users WHERE user_email = '$useremail' AND user_password = '$userpass'");
        //        print  $query;
        if ($query) {
            if (mysqli_num_rows($query) == 1) {
                $row = mysqli_fetch_assoc($query);
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['user_name'] = $row['user_firstname'] . " " . $row['user_lastname'];
                header("location:home.php");
            } else {
                ?>
                <script>
                    document.getElementsByClassName("required")[0].innerHTML = "Invalid Login Credentials.";
                    document.getElementsByClassName("required")[1].innerHTML = "Invalid Login Credentials.";
                    //                    print $row;
                </script> <?php
            }
        } else {
            echo mysqli_error($conn);
        }
    }
    if (isset($_POST['register'])) { // Register process
        // Retrieve Data
        $userfirstname = $_POST['userfirstname'];
        $userlastname = $_POST['userlastname'];
        $usernickname = $_POST['usernickname'];
        $userpassword = md5($_POST['userpass']);
        $useremail = $_POST['useremail'];
        $userbirthdate = $_POST['selectyear'] . '-' . $_POST['selectmonth'] . '-' . $_POST['selectday'];
        $usergender = $_POST['usergender'];
        $userhometown = $_POST['userhometown'];
        $userabout = $_POST['userabout'];
        if (isset($_POST['userstatus'])) {
            $userstatus = $_POST['userstatus'];
        } else {
            $userstatus = NULL;
        }
        // Check for Some Unique Constraints
        $query = mysqli_query($conn, "SELECT user_nickname, user_email FROM users WHERE user_nickname = '$usernickname' OR user_email = '$useremail'");
        if (mysqli_num_rows($query) > 0) {
            $row = mysqli_fetch_assoc($query);
            if ($usernickname == $row['user_nickname'] && !empty($usernickname)) {
                ?>
                <script>
                    document.getElementsByClassName("required")[4].innerHTML = "This Nickname already exists.";
                </script> <?php
            }
            if ($useremail == $row['user_email']) {
                ?>
                <script>
                    document.getElementsByClassName("required")[7].innerHTML = "This Email already exists.";
                </script>
                <?php
            }
        }
        // Insert Data
        $sql = "INSERT INTO users(user_firstname, user_lastname, user_nickname, user_password, user_email, user_gender, user_birthdate, user_status, user_about, user_hometown)
                VALUES ('$userfirstname', '$userlastname', '$usernickname', '$userpassword', '$useremail', '$usergender', '$userbirthdate', '$userstatus', '$userabout', '$userhometown')";
        $query = mysqli_query($conn, $sql);
        if ($query) {
            $query = mysqli_query($conn, "SELECT user_id FROM users WHERE user_email = '$useremail'");
            $row = mysqli_fetch_assoc($query);
            $_SESSION['user_id'] = $row['user_id'];
            header("location:home.php");
        }
    }
}
?>