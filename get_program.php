<?php
include('connect.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = mysqli_query($conn, "SELECT * FROM programmes WHERE ProgrammeID = $id");
    echo json_encode(mysqli_fetch_assoc($result));
}
?>
