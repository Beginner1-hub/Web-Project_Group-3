<?php
// Check if a session is already active before starting one
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$servername = "localhost";
$username = "root"; // Change if needed
$password = ""; // Change if needed
$dbname = "student_course_hub"; // Ensure this is your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
