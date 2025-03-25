<?php
session_start();
include('connect.php');

if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== "Admin") {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid Module ID.");
}

$module_id = intval($_GET['id']);
$query = "SELECT * FROM modules WHERE ModuleID = $module_id";
$result = mysqli_query($conn, $query);
$module = mysqli_fetch_assoc($result);

if (!$module) {
    die("Module not found.");
}

$staff_query = "SELECT StaffID, Name FROM staff";
$staff_result = mysqli_query($conn, $staff_query);
$staff_list = [];
while ($staff = mysqli_fetch_assoc($staff_result)) {
    $staff_list[] = $staff;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $module_name = mysqli_real_escape_string($conn, $_POST['module_name']);
    $module_desc = mysqli_real_escape_string($conn, $_POST['module_desc']);
    $module_leader = mysqli_real_escape_string($conn, $_POST['module_leader']);

    $update_query = "UPDATE modules 
                     SET ModuleName='$module_name', Description='$module_desc', ModuleLeaderID='$module_leader'
                     WHERE ModuleID = $module_id";

    if (mysqli_query($conn, $update_query)) {
        $_SESSION['msg'] = "✅ Module updated successfully.";
        header("Location: manage_modules.php");
        exit();
    } else {
        $error = "❌ Error updating module: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Module</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f2f4f8;
      font-family: 'Segoe UI', sans-serif;
    }
    .edit-container {
      max-width: 600px;
      margin: 60px auto;
      padding: 30px;
      background: white;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #2c3e50;
    }
    .form-label {
      font-weight: 600;
    }
    .btn-update {
      background-color: #2c3e50;
      color: white;
      font-weight: 500;
    }
    .btn-update:hover {
      background-color: #1a252f;
    }
    .btn-back {
      margin-bottom: 20px;
      font-weight: 500;
    }
    .alert {
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
<div class="edit-container">
  <a href="manage_modules.php" class="btn btn-outline-secondary btn-back">← Back to Module List</a>
  <h2>✏️ Edit Module</h2>
  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"> <?= $error ?> </div>
  <?php endif; ?>
  <form method="POST">
    <div class="mb-3">
      <label class="form-label">Module Name</label>
      <input type="text" name="module_name" class="form-control" value="<?= htmlspecialchars($module['ModuleName']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Description</label>
      <input type="text" name="module_desc" class="form-control" value="<?= htmlspecialchars($module['Description']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Module Leader</label>
      <select name="module_leader" class="form-select" required>
        <option value="">Select Leader</option>
        <?php foreach ($staff_list as $staff): ?>
          <option value="<?= $staff['StaffID'] ?>" <?= ($module['ModuleLeaderID'] == $staff['StaffID']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($staff['Name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <button type="submit" class="btn btn-update w-100">Update Module</button>
  </form>
</div>
</body>
</html>
