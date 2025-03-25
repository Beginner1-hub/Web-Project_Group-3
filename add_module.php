<?php
session_start();
include('connect.php'); 

if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== "Admin") {
    header("Location: login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Module</title>
</head>
<body>
    <h1>Add a New Module</h1>
    <form action="process_add_module.php" method="POST">
        <label>Module Name:</label>
        <input type="text" name="module_name" required>
        <button type="submit">Add Module</button>
    </form>
</body>
</html>
