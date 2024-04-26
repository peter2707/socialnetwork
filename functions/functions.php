<?php
// Establish Connection to Database
function connect() {
    static $conn;
    if ($conn === NULL){ 
        $conn = mysqli_connect('localhost','root','root','socialnetwork');
        if (mysqli_connect_errno()) {
        echo "FAILED TO CONNECT TO MYSQL" . mysqli_connect_error();
        }
    }
    return $conn;
}

?>