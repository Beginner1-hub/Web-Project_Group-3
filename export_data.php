<?php
session_start();
include('connect.php');

if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== "Admin") {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['type'])) {
    die("Invalid export type.");
}

$type = $_GET['type'];

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="export_' . $type . '.csv"');

$output = fopen('php://output', 'w');

switch ($type) {
    case 'students':
        fputcsv($output, ['Student Name', 'Email', 'Programme Name', 'Interested At']);
        $query = "
            SELECT 
                i.StudentName, 
                i.StudentEmail, 
                p.ProgrammeName, 
                i.InterestedAt 
            FROM 
                interestedstudents i
            LEFT JOIN 
                programmes p ON i.ProgrammeID = p.ProgrammeID
        ";
        break;

    case 'programmes':
        fputcsv($output, ['Programme ID', 'Programme Name', 'Level', 'Description', 'Status']);
        $query = "
            SELECT ProgrammeID, ProgrammeName, Level, Description, Status
            FROM programmes
        ";
        break;

    case 'modules':
        fputcsv($output, ['Module ID', 'Module Name', 'Description', 'Module Leader']);
        $query = "
            SELECT m.ModuleID, m.ModuleName, m.Description, s.Name AS ModuleLeader
            FROM modules m
            LEFT JOIN staff s ON m.ModuleLeaderID = s.StaffID
        ";
        break;

    default:
        die("Unsupported export type.");
}

$result = mysqli_query($conn, $query);

if (!$result) {
    fputcsv($output, ['Error:', mysqli_error($conn)]);
} else {
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, $row);
    }
}

fclose($output);
exit();
?>