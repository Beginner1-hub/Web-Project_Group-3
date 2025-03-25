<?php
session_start();
include "connect.php";

if (!isset($_GET['id'])) {
    header("Location: student_dashboard.php");
    exit();
}

$programme_id = $_GET['id'];
$success_message = "";

$is_interested = false;
$student_name = "";
$student_email = $_SESSION['username'] ?? null;

if ($student_email) {
    $check_interest_query = "SELECT StudentName FROM interestedstudents WHERE ProgrammeID = ? AND StudentEmail = ?";
    $stmt = $conn->prepare($check_interest_query);
    $stmt->bind_param("is", $programme_id, $student_email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $is_interested = true;
        $student_name = $row['StudentName'];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register_interest'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    if (!empty($name) && !empty($email)) {
        $insert_interest = $conn->prepare("INSERT INTO interestedstudents (ProgrammeID, StudentEmail, StudentName) VALUES (?, ?, ?)");
        $insert_interest->bind_param("iss", $programme_id, $email, $name);

        if ($insert_interest->execute()) {
            $is_interested = true;
            $student_name = $name;
            $success_message = "✅ Registered Successfully!";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['withdraw_interest'])) {
    $delete_interest = $conn->prepare("DELETE FROM interestedstudents WHERE ProgrammeID = ? AND StudentEmail = ?");
    $delete_interest->bind_param("is", $programme_id, $student_email);
    if ($delete_interest->execute()) {
        $is_interested = false;
        $student_name = "";
        $success_message = "❌ Withdrawn Successfully!";
    }
}

$stmt = $conn->prepare("SELECT p.*, s.Name AS LeaderName FROM programmes p JOIN staff s ON p.ProgrammeLeaderID = s.StaffID WHERE p.ProgrammeID = ?");
$stmt->bind_param("i", $programme_id);
$stmt->execute();
$programme_result = $stmt->get_result();
$programme = $programme_result->fetch_assoc();
if (!$programme) {
    die("Programme not found.");
}

$modules_query = "SELECT m.ModuleID, m.ModuleName, m.Description, s.Name AS ModuleLeader, pm.Year, m.Image 
                 FROM programmeModules pm
                 JOIN modules m ON pm.ModuleID = m.ModuleID
                 JOIN staff s ON m.ModuleLeaderID = s.StaffID
                 WHERE pm.ProgrammeID = ?
                 ORDER BY pm.Year ASC";

$stmt = $conn->prepare($modules_query);
$stmt->bind_param("i", $programme_id);
$stmt->execute();
$modules_result = $stmt->get_result();
$defaultModuleImage = "uploads/default.jpg";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($programme['ProgrammeName']); ?> - Modules</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            color: white;
            background: url('uploads/black.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .header {
            background: linear-gradient(120deg, #003366, #001f3f);
            padding: 40px 20px;
            text-align: center;
            color: white;
            border-bottom: 4px solid #00d4ff;
        }
        .header h1 {
            font-size: 40px;
            margin: 0;
            letter-spacing: 1px;
        }
        .top-nav {
            margin: 10px;
            text-align: left;
        }
        .back-btn {
            background: rgba(0, 0, 0, 0.7);
            color: #00d4ff;
            padding: 10px 18px;
            border: 2px solid #00d4ff;
            border-radius: 6px;
            font-weight: bold;
            text-decoration: none;
            transition: background 0.3s;
        }
        .back-btn:hover {
            background: #00d4ff;
            color: black;
        }
        .container {
            width: 95%;
            margin: auto;
            margin-top: 20px;
            text-align: center;
        }
        .interest-buttons {
            margin-bottom: 20px;
        }
        .interest-btn {
            background: linear-gradient(90deg, #00c6ff, #0072ff);
            color: white;
            padding: 12px 28px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 114, 255, 0.4);
            transition: 0.3s ease;
        }
        .interest-btn:hover {
            transform: scale(1.05);
            background: linear-gradient(90deg, #0072ff, #00c6ff);
        }
        .withdraw-btn {
            background: #ff4c4c;
        }
        .interest-form {
            display: none;
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.95);
            padding: 20px;
            border-radius: 10px;
            width: 320px;
            text-align: center;
            z-index: 999;
        }
        .interest-form input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .module-year {
            font-size: 26px;
            font-weight: bold;
            color: white;
            margin: 60px 0 20px;
            padding: 12px;
            background: #001f3f;
            border-radius: 8px;
        }
        .module-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            padding: 0 10px;
        }
        .module-card {
            height: 270px;
            background-size: cover;
            background-position: center;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
            display: flex;
            align-items: flex-end;
            overflow: hidden;
            position: relative;
        }
        .module-info {
            background: rgba(0, 0, 0, 0.95);
            padding: 12px;
            width: 100%;
            font-size: 14px;
            text-align: left;
        }
        .module-info h3 {
            color: #00d4ff;
            margin-bottom: 5px;
        }
        .module-info p {
            margin: 3px 0;
            color: #f0f0f0;
        }
        .success-message {
            background: rgba(0, 255, 0, 0.15);
            color: #0f0;
            font-weight: bold;
            font-size: 16px;
            padding: 10px 20px;
            margin: 10px auto;
            width: fit-content;
            border: 2px solid #0f0;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.4);
            transition: opacity 0.5s ease-in-out;
        }
        .close-btn {
            background: #555;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="top-nav">
    <a href="student_dashboard.php" class="back-btn">← Back</a>
</div>
<div class="header">
    <h1><?= htmlspecialchars($programme['ProgrammeName']); ?></h1>
</div>
<div class="container">
    <?php if ($success_message): ?>
        <p id="successMessage" class="success-message"><?= htmlspecialchars($success_message); ?></p>
    <?php endif; ?>

    <div class="interest-buttons">
        <?php if ($is_interested): ?>
            <form method="POST">
                <input type="hidden" name="withdraw_interest" value="1">
                <button type="submit" class="interest-btn withdraw-btn">Withdraw Interest</button>
            </form>
        <?php else: ?>
            <button class="interest-btn" onclick="document.getElementById('interestForm').style.display='block'">Register Interest</button>
        <?php endif; ?>
    </div>

    <div id="interestForm" class="interest-form">
        <h3>Register Interest</h3>
        <form method="POST">
            <input type="hidden" name="register_interest" value="1">
            <input type="text" name="name" placeholder="Enter your name" value="<?= htmlspecialchars($student_name); ?>" required>
            <input type="email" name="email" placeholder="Enter your email" value="<?= htmlspecialchars($student_email); ?>" required>
            <button type="submit" class="interest-btn">Submit</button>
        </form>
        <button class="close-btn" onclick="document.getElementById('interestForm').style.display='none'">Cancel</button>
    </div>

    <?php 
    $current_year = 0;
    while ($module = $modules_result->fetch_assoc()): 
        $moduleImage = (!empty($module['Image']) && file_exists("uploads/" . $module['Image'])) ? "uploads/" . $module['Image'] : $defaultModuleImage;
        if ($module['Year'] != $current_year):
            if ($current_year != 0) echo "</div>"; 
            $current_year = $module['Year'];
            echo "<div class='module-year'>Year $current_year</div><div class='module-grid'>";
        endif;
    ?>
        <div class="module-card" style="background-image: url('<?= $moduleImage; ?>');">
            <div class="module-info">
                <h3><?= htmlspecialchars($module['ModuleName']); ?></h3>
                <p><?= htmlspecialchars($module['Description']); ?></p>
                <p><strong>Leader:</strong> <?= htmlspecialchars($module['ModuleLeader']); ?></p>
            </div>
        </div>
    <?php endwhile; echo "</div>"; ?>
</div>
<script>
    const successMessage = document.getElementById('successMessage');
    if (successMessage) {
        setTimeout(() => {
            successMessage.style.opacity = '0';
            setTimeout(() => {
                successMessage.remove();
            }, 500);
        }, 3000);
    }
</script>
</body>
</html>
