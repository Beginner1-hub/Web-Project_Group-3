<?php
// Set your desired plain-text password here
$password = "staff123";

// Generate the hashed version
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Display the hashed password
echo "Hashed password for 'staff123' is:<br><br>";
echo "<strong>$hashedPassword</strong>";
?>
