<?php
session_start();
include('connect.php');

// Ensure only Admins can access
if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== "Admin") {
    header("Location: login.php");
    exit();
}

// Get Module ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['msg'] = "❌ Invalid Module ID.";
    header("Location: manage_modules.php");
    exit();
}

$module_id = intval($_GET['id']);

// Delete the module
$delete_query = "DELETE FROM modules WHERE ModuleID = $module_id";

if (mysqli_query($conn, $delete_query)) {
    $_SESSION['msg'] = "✅ Module deleted successfully.";
} else {
    $_SESSION['msg'] = "❌ Error deleting module: " . mysqli_error($conn);
}

header("Location: manage_modules.php");
exit();
?>
