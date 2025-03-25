<?php
session_start();
include("connect.php");

if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== 'Staff') {
    header("Location: login.php?user_type=Staff");
    exit();
}

$staffUsername = $_SESSION['username'];

// Fetch current staff info
$query = "SELECT * FROM staff WHERE Username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $staffUsername);
$stmt->execute();
$result = $stmt->get_result();
$staff = $result->fetch_assoc();

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jobTitle = $_POST['job_title'] ?? '';
    $department = $_POST['department'] ?? '';
    $bio = $_POST['bio'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $office = $_POST['office'] ?? '';
    $profilePhoto = $staff['ProfilePhoto'];

    // Handle image upload
    if (!empty($_FILES['profile_photo']['name'])) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES['profile_photo']['name']);
        if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $targetFile)) {
            $profilePhoto = $targetFile;
        }
    }

    $updateQuery = "UPDATE staff SET JobTitle = ?, Department = ?, Bio = ?, PhoneNumber = ?, OfficeLocation = ?, ProfilePhoto = ? WHERE Username = ?";
    $stmt = $conn->prepare($updateQuery);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("sssssss", $jobTitle, $department, $bio, $phone, $office, $profilePhoto, $staffUsername);
    if ($stmt->execute()) {
        header("Location: staff_dashboard.php?update=success");
        exit();
    } else {
        $error = "Failed to update profile.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 40px;
            background: #121212;
            color: white;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #1f1f2e;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.4);
        }
        h2 {
            text-align: center;
            color: #00adb5;
            margin-bottom: 25px;
        }
        form label {
            font-weight: 600;
            display: block;
            margin: 10px 0 5px;
        }
        form input, form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: none;
        }
        .btn {
            background: #00adb5;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 10px;
            font-weight: bold;
            cursor: pointer;
            display: block;
            margin: 0 auto;
        }
        .btn:hover {
            background: #009ca3;
        }
        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            background: #444;
            color: white;
            padding: 10px 16px;
            border-radius: 8px;
            text-decoration: none;
        }
        .back-btn:hover {
            background: #666;
        }
    </style>
</head>
<body>
<div class="container">
    <a href="staff_dashboard.php" class="back-btn">← Back to Dashboard</a>
    <h2>Edit Your Profile</h2>
    <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
    <form method="POST" enctype="multipart/form-data">
        <label>Job Title</label>
        <input type="text" name="job_title" value="<?= htmlspecialchars($staff['JobTitle']) ?>">

        <label>Department</label>
        <input type="text" name="department" value="<?= htmlspecialchars($staff['Department']) ?>">

        <label>Phone Number</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($staff['PhoneNumber']) ?>">

        <label>Office Location</label>
        <input type="text" name="office" value="<?= htmlspecialchars($staff['OfficeLocation']) ?>">

        <label>Bio</label>
        <textarea name="bio" rows="4"><?= htmlspecialchars($staff['Bio']) ?></textarea>

        <label>Profile Photo</label>
        <input type="file" name="profile_photo">

        <button type="submit" class="btn">Update Profile</button>
    </form>
</div>
</body>
</html>
