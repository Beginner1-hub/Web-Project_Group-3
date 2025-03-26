<?php
session_start();
include('connect.php');

if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== "Admin") {
    header("Location: login.php");
    exit();
}

$admin_name = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-bg: #2a2f45;
            --sidebar-hover: #3d425c;
            --text-light: #ffffff;
            --highlight: #f1c40f;
            --button-bg: #00bcd4;
            --button-hover: #0097a7;
            --main-bg: #eef1f5;
            --card-bg: #ffffff;
            --text-color: #2a2a2a;
        }

        body.dark {
            --sidebar-bg: #1c1f2e;
            --sidebar-hover: #2b3043;
            --text-light: #ffffff;
            --highlight: #ffd54f;
            --button-bg: #0097a7;
            --button-hover: #00838f;
            --main-bg: #121212;
            --card-bg: #1e1e2f;
            --text-color: #eeeeee;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: var(--main-bg);
            color: var(--text-color);
            transition: all 0.3s ease;
        }

        .sidebar {
            width: 270px;
            height: 100vh;
            background: var(--sidebar-bg);
            color: var(--text-light);
            position: fixed;
            top: 0;
            left: 0;
            padding: 30px 20px;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.15);
        }

        .sidebar h2 {
            font-weight: 700;
            font-size: 24px;
            margin-bottom: 35px;
            color: var(--highlight);
        }

        .sidebar .nav {
            list-style: none;
            padding: 0;
            margin-bottom: 25px;
        }

        .sidebar .nav li {
            margin-bottom: 18px;
        }

        .sidebar .nav a {
            color: var(--text-light);
            text-decoration: none;
            font-size: 17px;
            font-weight: 500;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .sidebar .nav a:hover {
            background-color: var(--sidebar-hover);
            padding-left: 20px;
            color: var(--highlight);
        }

        .sidebar .nav i {
            margin-right: 12px;
            font-size: 18px;
        }

        .export-btn {
            background: var(--button-bg);
            color: white;
            border: none;
            border-radius: 6px;
            padding: 12px 16px;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }

        .export-btn:hover {
            background-color: var(--button-hover);
        }

        .main-content {
            margin-left: 280px;
            padding: 50px;
        }

        .welcome-box {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.07);
        }

        .welcome-box h1 {
            font-size: 30px;
            font-weight: 600;
            margin-bottom: 14px;
        }

        .welcome-box p {
            font-size: 16px;
            line-height: 1.6;
        }

        .back-btn {
            display: inline-block;
            margin-top: 25px;
            padding: 10px 16px;
            background-color: #1976d2;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover {
            background-color: #1565c0;
        }

        .modal-header {
            background-color: #1976d2;
            color: white;
        }

        .theme-toggle {
            background: transparent;
            border: none;
            color: var(--text-light);
            font-size: 18px;
            cursor: pointer;
            margin-top: 10px;
            transition: color 0.3s ease;
        }

        .theme-toggle:hover {
            color: var(--highlight);
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-header">
        <h2><i class="fas fa-user-shield me-2"></i>Admin Panel</h2>
    </div>
    <ul class="nav">
        <li><a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="manage_programs.php"><i class="fas fa-book"></i> Manage Programs</a></li>
        <li><a href="manage_modules.php"><i class="fas fa-layer-group"></i> Manage Modules</a></li>
        <li><a href="view_interested_students.php"><i class="fas fa-user-graduate"></i> View Students</a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
    <button class="export-btn" data-bs-toggle="modal" data-bs-target="#exportModal"><i class="fas fa-file-export me-2"></i>Export Data</button>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="welcome-box">
        <h1>Welcome, <span><?php echo ucfirst($admin_name); ?></span> 👋</h1>
        <p>This is your admin dashboard.</p>
        <p>Use the navigation menu on the left to manage programs, modules, students, and export data.</p>
        <p>Everything is just a click away.</p>
        <a href="javascript:history.back()" class="back-btn">🔙 Go Back</a>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="GET" action="export_data.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">📤 Export Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Select Data Type to Export:</label>
                    <select name="type" class="form-select" required>
                        <option value="">-- Choose --</option>
                        <option value="students">Interested Students</option>
                        <option value="programmes">Programmes</option>
                        <option value="modules">Modules</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Export</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
