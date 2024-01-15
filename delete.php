<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}
$servername = "localhost";
$username = "username";
$password = "web2hikmat2023";
$db = "oh";

// Create connection
$conn = new mysqli($servername, $username, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_POST["delete"])) {
    $position_id = mysqli_real_escape_string($conn, $_POST["delete"]);
    $query = "DELETE FROM position WHERE id='$position_id' ";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $_SESSION["msg"] = "Successfully deleted";
        header("Location: index.php");
        exit(0);
    } else {
        $_SESSION["msg"] = "Not deleted!";
        header("Location: index.php");
        exit(0);
    }
}
