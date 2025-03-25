<?php
session_start();
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $programmeID = $_POST['ProgrammeID'];
    $name = $_POST['ProgrammeName'];
    $levelID = $_POST['LevelID'];
    $leaderID = $_POST['ProgrammeLeaderID'];
    $description = $_POST['Description'];
    $status = $_POST['Status'];

    if (empty($programmeID)) {
        die("Programme ID is missing.");
    }

    $imageName = null;

    if (!empty($_FILES['Image']['name'])) {
        $targetDir = "uploads/";
        $imageName = basename($_FILES["Image"]["name"]);
        $targetFile = $targetDir . $imageName;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($imageFileType, $allowed)) {
            die("Invalid file type. Only JPG, PNG, GIF, or WEBP allowed.");
        }

        move_uploaded_file($_FILES["Image"]["tmp_name"], $targetFile);

        $stmt = $conn->prepare("UPDATE programmes SET ProgrammeName = ?, LevelID = ?, ProgrammeLeaderID = ?, Description = ?, Image = ?, Status = ? WHERE ProgrammeID = ?");
        $stmt->bind_param("siisssi", $name, $levelID, $leaderID, $description, $imageName, $status, $programmeID);
    } else {
        $stmt = $conn->prepare("UPDATE programmes SET ProgrammeName = ?, LevelID = ?, ProgrammeLeaderID = ?, Description = ?, Status = ? WHERE ProgrammeID = ?");
        $stmt->bind_param("siissi", $name, $levelID, $leaderID, $description, $status, $programmeID);
    }

    if ($stmt->execute()) {
        $_SESSION['msg'] = "Programme updated successfully.";
        header("Location: manage_programs.php");
        exit;
    } else {
        echo "Error updating programme: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: manage_programs.php");
    exit;
}
?>
