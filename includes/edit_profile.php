<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $row['user_id'];
    // Retrieve and sanitize form data
    $conn = connect();

    // Define an array to hold the update values
    $updates = array();

    // Check each form field and add non-empty ones to the update array
    // If a field is blank, do not update that field in the database
    if (!empty($_POST["nickname"])) {
        $updates[] = "user_nickname = '" . htmlspecialchars($_POST["nickname"]) . "'";
    }
    if (!empty($_POST["gender"])) {
        $updates[] = "user_gender = '" . htmlspecialchars($_POST["gender"]) . "'";
    }
    if (!empty($_POST["dob"])) {
        $dob = date('Y-m-d', strtotime($_POST["dob"])); // Format date for MySQL
        $updates[] = "user_birthdate = '$dob'";
    }
    if (!empty($_POST["hometown"])) {
        $updates[] = "user_hometown = '" . htmlspecialchars($_POST["hometown"]) . "'";
    }
    if (!empty($_POST["userabout"])) {
        $updates[] = "user_about = '" . htmlspecialchars($_POST["userabout"]) . "'";
    }
    if (!empty($_POST["userstatus"])) {
        $updates[] = "user_status = '" . htmlspecialchars($_POST["userstatus"]) . "'";
    }

    // Check if there are updates to perform
    if (!empty($updates)) {
        // Construct the SQL update query
        $updateQuery = "UPDATE users SET " . implode(", ", $updates) . " WHERE user_id = '$userId'";
        
        // Perform the update in the database
        $query = mysqli_query($conn, $updateQuery);

        if ($query) {
            // Data updated successfully, redirect or perform further actions
            header("location: profile.php");
            exit();
        } else {
            // Handle database update error
            printf("Query failed: %s\n", mysqli_error($conn));
            echo "Error updating data. Please try again later.";
        }
    } else {
        // No updates were made, redirect or display a message
        header("location: profile.php");
        exit();
    }
} else {
    // Handle invalid access
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <!-- Link to CSS styles for modal -->
    <link rel="stylesheet" type="text/css" href="resources/css/popup_modal.css">
</head>
<body>
    <button id="editProfileBtn" class="editProfileBtn">Edit Profile</button>
    <!-- The modal -->
    <div id="editProfileModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Profile</h2>
            <form method="POST">
                <label for="name">Nickname:</label>
                <input type="text" id="nickname" name="nickname"><br><br>

                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob"><br><br>

                <label for="gender">Gender:</label>
                <input type="radio" name="gender" value="M" id="malegender" class="usergender">
                <label>Male</label>
                <input type="radio" name="gender" value="F" id="femalegender" class="usergender">
                <label>Female</label>
                <br><br>

                <label for="hometown">Hometown:</label>
                <input type="text" id="hometown" name="hometown"><br><br>

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

                <input type="submit" value="Save">
            </form>
        </div>
    </div>
</body>
</html>


<script>
// Get the modal element
var modal = document.getElementById('editProfileModal');

// Get the button that opens the modal
var btn = document.getElementById("editProfileBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on the button, open the modal
btn.onclick = function() {
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

