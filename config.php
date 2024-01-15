<?php
$servername = "localhost";
$username = "username";
$password = "web2hikmat2023";
$db = "oh";

// Create connection
$conn = new mysqli("localhost", "username", "web2hikmat2023", "oh");

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
