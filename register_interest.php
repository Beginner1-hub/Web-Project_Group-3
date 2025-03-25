<?php
session_start();
include "connect.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $programme_id = $_POST['programme_id'];
    $student_name = trim($_POST['name']);
    $student_email = trim($_POST['email']);

    // Validate input
    if (empty($programme_id) || empty($student_name) || empty($student_email)) {
        echo "<div class='error-message'>⚠ Please fill in all fields.</div>";
        exit();
    }

    // Check if the student is already registered
    $check_stmt = $conn->prepare("SELECT * FROM interestedstudents WHERE ProgrammeID = ? AND StudentEmail = ?");
    $check_stmt->bind_param("is", $programme_id, $student_email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // If already registered, allow withdrawal
        $delete_stmt = $conn->prepare("DELETE FROM interestedstudents WHERE ProgrammeID = ? AND StudentEmail = ?");
        $delete_stmt->bind_param("is", $programme_id, $student_email);

        if ($delete_stmt->execute()) {
            echo "<div class='success-message'>❌ You have withdrawn your interest successfully.</div>";
        } else {
            echo "<div class='error-message'>⚠ Error withdrawing interest. Please try again.</div>";
        }
    } else {
        // Register interest
        $insert_stmt = $conn->prepare("INSERT INTO interestedstudents (ProgrammeID, StudentEmail, StudentName) VALUES (?, ?, ?)");
        $insert_stmt->bind_param("iss", $programme_id, $student_email, $student_name);

        if ($insert_stmt->execute()) {
            echo "<div class='success-message'>✅ Interest registered successfully!</div>";
        } else {
            echo "<div class='error-message'>⚠ Error registering interest. Please try again.</div>";
        }
    }
}
?>

<!-- 💡 Add some basic CSS to make the success/error messages look more professional -->
<style>
    .success-message, .error-message {
        width: 60%;
        margin: 20px auto;
        padding: 15px;
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        border-radius: 8px;
    }

    .success-message {
        background-color: #d4edda;
        color: #155724;
        border: 2px solid #c3e6cb;
    }

    .error-message {
        background-color: #f8d7da;
        color: #721c24;
        border: 2px solid #f5c6cb;
    }
</style>

<!-- Automatically redirect back after 2 seconds -->
<script>
    setTimeout(() => {
        window.location.href = "programme_details.php?id=<?= $programme_id ?>";
    }, 2000);
</script>
