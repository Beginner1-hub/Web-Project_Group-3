<?php
require_once 'connect.php';

if (!isset($_GET['id'])) {
    die("No Programme ID provided.");
}

$programmeID = $_GET['id'];

// First, get the image name (if any) to delete the file
$stmt = $conn->prepare("SELECT Image FROM programmes WHERE ProgrammeID = ?");
$stmt->bind_param("i", $programmeID);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$imageName = $row['Image'];
$stmt->close();

// Delete the programme from database
$stmt = $conn->prepare("DELETE FROM programmes WHERE ProgrammeID = ?");
$stmt->bind_param("i", $programmeID);

if ($stmt->execute()) {
    // Delete image from uploads folder if exists
    if ($imageName && file_exists("../uploads/$imageName")) {
        unlink("../uploads/$imageName");
    }

    header("Location: manage_programs.php?deleted=1");
    exit;
} else {
    echo "Error deleting programme: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
