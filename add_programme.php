<?php
session_start();
include ('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['ProgrammeName'];
    $levelID = $_POST['LevelID'];
    $leaderID = $_POST['ProgrammeLeaderID'];
    $description = $_POST['Description'];
    $status = $_POST['Status'];

    $imageName = null;

    if (!empty($_FILES['Image']['name'])) {
        $targetDir = "uploads/";
        $imageName = basename($_FILES["Image"]["name"]);
        $targetFile = $targetDir . $imageName;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($imageFileType, $allowedTypes)) {
            die("Invalid file type. Only JPG, PNG, GIF, or WEBP allowed.");
        }

        move_uploaded_file($_FILES["Image"]["tmp_name"], $targetFile);
    }

    $stmt = $conn->prepare("INSERT INTO programmes (ProgrammeName, LevelID, ProgrammeLeaderID, Description, Image, Status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siisss", $name, $levelID, $leaderID, $description, $imageName, $status);

    if ($stmt->execute()) {
        $_SESSION['msg'] = "Programme added successfully.";
        header("Location: manage_programs.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
