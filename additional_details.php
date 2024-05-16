<?php
require 'functions/functions.php';
session_start();

$conn = connect();
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_details = $_SESSION['user_details'];
    // Access properties of user_details object
    $sub = $user_details->sub;
    $email_verified = $user_details->email_verified;
    $name = $user_details->name;
    $country = $user_details->country;
    $language = $user_details->language;
    $givenName = $user_details->givenName;
    $familyName = $user_details->familyName;
    $email = $user_details->email;
    $picture = $user_details->picture;

    // Retrieve form data
    $user_gender = $_POST['user_gender'];
    $user_birthdate = $_POST['user_birthdate'];
    $user_nickname = $_POST['user_nickname'];
    $user_status = $_POST['user_status'];
    $user_about = $_POST['user_about'];
    $user_hometown = $_POST['user_hometown'];

    $randomBytes = random_bytes(10); 

    $randomString = bin2hex($randomBytes);
    
    // Constructing the SQL INSERT query
    $sql = "INSERT INTO users(user_firstname, user_lastname, user_nickname, user_password, user_email, user_gender, user_birthdate, user_status, user_about, user_hometown, user_picture)
    VALUES ('$givenName', '$familyName','$user_nickname', '$randomString', '$email', '$user_gender', '$user_birthdate', '$user_status', '$user_about', '$user_hometown','$picture')";

    // Add other necessary fields from $userProfile

    $query = mysqli_query($conn, $sql);

    // Check if insertion was successful
    if ($query) {
        // Get the userId of the newly inserted user
        $query = mysqli_query($conn, "SELECT user_id FROM users WHERE user_email = '$email'");
        $row = mysqli_fetch_assoc($query);
        $_SESSION['user_id'] = $row['user_id'];
        header("Location: home.php");
        exit;
    } else {
        // Error handling
        echo "Error: " . mysqli_error($conn);
        return null;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Additional Details</title>
    <style>
        /* Form Styles */
        form {
            margin: 0 auto;
            max-width: 500px;
            padding: 20px;
            background-color: #fefefe;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }

        label {
            margin-bottom: 8px;
            display: block;
        }

        .radio-group label {
            margin-bottom: 2px;
        }

        .radio-group {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .radio-group input[type="radio"] {
            margin-right: 10px;
        }

        input[type="radio"] {
            -webkit-appearance: radio;
            -moz-appearance: radio;
            appearance: radio;
            display: inline-block;
            vertical-align: middle;
            margin: 0;
            padding: 0;
            width: auto;
            height: auto;
            position: relative;
            cursor: pointer;
        }

        input[type="date"],
        input[type="text"],
        select,
        textarea {
            width: 90%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 12px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Additional Details</h2>
    <!-- Form -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <label for="user_gender">Gender:</label>
        <select name="user_gender" id="user_gender" required>
            <option value="">Select Gender</option>
            <option value="M">Male</option>
            <option value="F">Female</option>
            <option value="O">Other</option>
        </select>

        <label for="user_birthdate">Birthdate:</label>
        <input type="date" name="user_birthdate" id="user_birthdate" required>

        <label for="user_nickname">Nickname:</label>
        <input type="text" name="user_nickname" id="user_nickname" maxlength="20">

        <!--Marital Status-->
        <label for="user_status">Status:</label>
        <div class="radio-group">
            <input type="radio" name="user_status" value="S" id="singlestatus">
            <label for="singlestatus">Single</label>
        </div>
        <div class="radio-group">
            <input type="radio" name="user_status" value="E" id="engagedstatus">
            <label for="engagedstatus">Engaged</label>
        </div>
        <div class="radio-group">
            <input type="radio" name="user_status" value="M" id="marriedstatus">
            <label for="marriedstatus">Married</label>
        </div>

        <label for="user_about">About:</label>
        <textarea name="user_about" id="user_about" rows="4" cols="50"></textarea>

        <label for="user_hometown">Hometown:</label>
        <input type="text" name="user_hometown" id="user_hometown" maxlength="255">

        <input type="submit" value="Create Account">
    </form>
</body>
</html>
