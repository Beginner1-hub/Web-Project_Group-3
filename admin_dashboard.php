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
    <link rel="stylesheet" href="admin_styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .export-btn {
            margin-top: 12px;
            background-color: #f06292;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 6px 12px;
            font-family: 'Poppins', sans-serif;
        }
        .export-btn:hover {
            background-color: #ec407a;
        }
        .modal-header {
            background-color: #1976d2;
            color: white;
        }
        .modal-footer button {
            border-radius: 6px;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-header">
        <h2>🧠 Admin</h2>
    </div>
    <ul class="nav">
        <li><a href="admin_dashboard.php">🏠 Dashboard</a></li>
        <li><a href="manage_programs.php">📚 Manage Programs</a></li>
        <li><a href="manage_modules.php">📖 Manage Modules</a></li>
        <li><a href="view_interested_students.php">👨‍🎓 View Interested Students</a></li>
    </ul>
    <button class="export-btn" data-bs-toggle="modal" data-bs-target="#exportModal">📤 Export Data</button>
    <ul class="nav">
        <li><a href="logout.php">🔓 Logout</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="welcome-box">
        <h1>Welcome, <span><?php echo ucfirst($admin_name); ?></span> 👋</h1>
        <p>This is your admin dashboard.</p>
        <p>Use the navigation menu on the left to manage programs, modules, students, and export data.</p>
        <p>Everything is just a click away.</p>

        <!-- Back Button -->
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>