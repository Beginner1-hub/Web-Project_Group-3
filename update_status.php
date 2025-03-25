<?php
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $programmeID = $_POST['ProgrammeID'];
    $status = $_POST['Status'];

    $stmt = $conn->prepare("UPDATE programmes SET Status = ? WHERE ProgrammeID = ?");
    $stmt->bind_param("si", $status, $programmeID);

    if ($stmt->execute()) {
        header("Location: manage_programs.php?status_updated=1");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: manage_programs.php");
    exit;
}
