<?php
session_start();

// Store the user type before destroying session
$user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 'Admin';

// Destroy the session
session_unset();
session_destroy();

// Redirect to correct login page
if ($user_type === 'Staff') {
    header("Location: login.php?user_type=Staff");
} else {
    header("Location: login.php?user_type=Admin");
}
exit();
