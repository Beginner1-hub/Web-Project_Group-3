<?php
include('connect.php');

$result = mysqli_query($conn, "SELECT StaffID, FullName FROM staff");
$staff = [];

while ($row = mysqli_fetch_assoc($result)) {
    $staff[] = $row;
}

echo json_encode($staff);
?>
