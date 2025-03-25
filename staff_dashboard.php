<?php
session_start();
include("connect.php");

if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== 'Staff') {
    header("Location: login.php?user_type=Staff");
    exit();
}

$staffUsername = $_SESSION['username'];
$staffQuery = "SELECT * FROM staff WHERE Username = ?";
$stmt = $conn->prepare($staffQuery);
$stmt->bind_param("s", $staffUsername);
$stmt->execute();
$staffResult = $stmt->get_result();
$staff = $staffResult->fetch_assoc();
$staffID = $staff['StaffID'];

$modulesQuery = "SELECT m.*, (SELECT COUNT(*) FROM programmeModules pm WHERE pm.ModuleID = m.ModuleID) AS StudentCount FROM modules m WHERE m.ModuleLeaderID = ?";
$stmt = $conn->prepare($modulesQuery);
$stmt->bind_param("i", $staffID);
$stmt->execute();
$modulesResult = $stmt->get_result();

$programmesQuery = "SELECT DISTINCT p.ProgrammeName FROM programmeModules pm JOIN programmes p ON pm.ProgrammeID = p.ProgrammeID JOIN modules m ON pm.ModuleID = m.ModuleID WHERE m.ModuleLeaderID = ?";
$stmt = $conn->prepare($programmesQuery);
$stmt->bind_param("i", $staffID);
$stmt->execute();
$programmesResult = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            margin: 0;
            font-family: 'Rubik', sans-serif;
            background: linear-gradient(to right, #141e30, #243b55);
            color: #f0f0f0;
        }
        .header {
            background: #0f2027;
            padding: 20px;
            text-align: center;
            font-size: 30px;
            font-weight: 700;
            color: #00eaff;
            position: relative;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }
        .logout-btn, .back-btn {
            position: absolute;
            top: 20px;
            background: #ff5e57;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
        }
        .logout-btn {
            right: 20px;
        }
        .back-btn {
            left: 20px;
            background: #00b894;
        }
        .container {
            padding: 40px 60px;
        }
        .section {
            margin-bottom: 50px;
        }
        .section h2 {
            font-size: 26px;
            border-bottom: 2px solid #00cec9;
            padding-bottom: 12px;
            margin-bottom: 20px;
            color: #00cec9;
        }
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }
        .card {
            background: #2c3e50;
            padding: 20px;
            border-radius: 14px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.4);
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: scale(1.03);
            box-shadow: 0 8px 20px rgba(0,0,0,0.5);
        }
        .card h3 {
            margin-bottom: 10px;
            color: #1abc9c;
        }
        .card p {
            margin: 6px 0;
            color: #dfe6e9;
        }
        .profile {
            display: flex;
            gap: 30px;
            background: #34495e;
            padding: 30px;
            border-radius: 15px;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.4);
        }
        .profile img {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #00cec9;
        }
        .profile-details h3 {
            margin: 0;
            color: #00cec9;
            font-size: 24px;
        }
        .quick-links a {
            background: #6c5ce7;
            color: white;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            display: inline-block;
            margin-top: 12px;
        }
        .quick-links a:hover {
            background: #a29bfe;
        }
    </style>
</head>
<body>
    <div class="header">
        Welcome, <?= htmlspecialchars($staff['Name']); ?>
        <a href="logout.php?user_type=Staff" class="logout-btn">Logout</a>
        <a href="staff_dashboard.php" class="back-btn">← Back</a>
    </div>

    <div class="container">
        <div class="section profile">
            <img src="<?= htmlspecialchars($staff['ProfilePhoto']); ?>" alt="Profile Photo">
            <div class="profile-details">
                <h3><?= htmlspecialchars($staff['Name']); ?></h3>
                <p><strong>Job Title:</strong> <?= htmlspecialchars($staff['JobTitle']); ?></p>
                <p><strong>Department:</strong> <?= htmlspecialchars($staff['Department']); ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($staff['PhoneNumber']); ?></p>
                <p><strong>Office:</strong> <?= htmlspecialchars($staff['OfficeLocation']); ?></p>
                <p><strong>Bio:</strong> <?= htmlspecialchars($staff['Bio']); ?></p>
                <div class="quick-links">
                    <a href="change_password.php?user_type=Staff">Change Password</a>
                    <a href="edit_profile.php">Edit Profile</a>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>Modules You Lead</h2>
            <div class="card-grid">
                <?php while ($module = $modulesResult->fetch_assoc()): ?>
                    <div class="card">
                        <h3><?= htmlspecialchars($module['ModuleName']); ?></h3>
                        <p><?= htmlspecialchars($module['Description']); ?></p>
                        <p><strong>Students Enrolled:</strong> <?= $module['StudentCount']; ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <div class="section">
            <h2>Programmes Containing Your Modules</h2>
            <div class="card-grid">
                <?php while ($programme = $programmesResult->fetch_assoc()): ?>
                    <div class="card">
                        <h3><?= htmlspecialchars($programme['ProgrammeName']); ?></h3>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <div class="section">
            <h2>Reminders & Tasks</h2>
            <div class="card">
                <ul>
                    <li>📝 Update course materials for upcoming semester</li>
                    <li>📊 Review student feedback from last term</li>
                    <li>📅 Schedule meeting with module assistants</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
