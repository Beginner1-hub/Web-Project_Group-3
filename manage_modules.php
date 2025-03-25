<?php
session_start();
include('connect.php');

if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== "Admin") {
    header("Location: login.php");
    exit();
}

$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_module'])) {
    $module_name = mysqli_real_escape_string($conn, $_POST['module_name']);
    $module_desc = mysqli_real_escape_string($conn, $_POST['module_desc']);
    $module_leader = mysqli_real_escape_string($conn, $_POST['module_leader']);

    $sql = "INSERT INTO modules (ModuleName, Description, ModuleLeaderID) 
            VALUES ('$module_name', '$module_desc', '$module_leader')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['msg'] = "✅ Module added successfully.";
        header("Location: manage_modules.php");
        exit();
    } else {
        $_SESSION['msg'] = "❌ Error adding module: " . mysqli_error($conn);
    }
}

if (isset($_GET['export']) && $_GET['export'] === 'pdf') {
    $exp_query = "
        SELECT m.ModuleID, m.ModuleName, m.Description, s.Name AS ModuleLeader 
        FROM modules m 
        LEFT JOIN staff s ON m.ModuleLeaderID = s.StaffID
        ORDER BY m.ModuleID ASC
    ";
    $exp_result = mysqli_query($conn, $exp_query);

    $content = "<h2 style='text-align:center;'>Module List</h2><table border='1' width='100%' cellspacing='0' cellpadding='5'>";
    $content .= "<thead style='background-color:#3498db;color:#fff;'><tr><th>ID</th><th>Module Name</th><th>Description</th><th>Module Leader</th></tr></thead><tbody>";

    while ($row = mysqli_fetch_assoc($exp_result)) {
        $leader = $row['ModuleLeader'] ?? 'Not Assigned';
        $content .= "<tr><td>{$row['ModuleID']}</td><td>{$row['ModuleName']}</td><td>{$row['Description']}</td><td>{$leader}</td></tr>";
    }
    $content .= "</tbody></table>";

    $filename = "modules_export.html";
    file_put_contents($filename, $content);

    header('Content-Type: application/octet-stream');
    header("Content-Disposition: attachment; filename=modules_export.html");
    readfile($filename);
    exit();
}

$search = $_GET['search'] ?? '';
$filter_sql = "";
if (!empty($search)) {
    $search = mysqli_real_escape_string($conn, $search);
    $filter_sql = "WHERE m.ModuleName LIKE '%$search%' OR m.Description LIKE '%$search%' OR s.Name LIKE '%$search%'";
}

$total_query = "SELECT COUNT(*) AS total FROM modules m LEFT JOIN staff s ON m.ModuleLeaderID = s.StaffID $filter_sql";
$total_result = mysqli_query($conn, $total_query);
$total_modules = mysqli_fetch_assoc($total_result)['total'];
$total_pages = ceil($total_modules / $limit);

$query = "
    SELECT m.ModuleID, m.ModuleName, m.Description, m.ModuleLeaderID, s.Name AS ModuleLeader 
    FROM modules m
    LEFT JOIN staff s ON m.ModuleLeaderID = s.StaffID
    $filter_sql
    ORDER BY m.ModuleID ASC
    LIMIT $limit OFFSET $offset
";
$result = mysqli_query($conn, $query);

$staff_query = "SELECT StaffID, Name FROM staff";
$staff_result = mysqli_query($conn, $staff_query);
$staff_list = [];
while ($staff = mysqli_fetch_assoc($staff_result)) {
    $staff_list[] = $staff;
}
$message = $_SESSION['msg'] ?? '';
unset($_SESSION['msg']);
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  body {
    background-color:rgb(255, 255, 255);
    font-family: 'Poppins', sans-serif;
  }
  .container-custom {
    max-width: 95%;
    margin: auto;
    padding-top: 30px;
    background:white;
  }
  .table-container {
    background:white; 
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    padding: 20px;
  }
  .table thead {
    background: linear-gradient(to right, #4e54c8, #8f94fb) !important;
    color: white;
    font-weight: bold;
    font-size: 15px;
  }
  .table th {
    color:white;
    font-weight: 3000;
    background:black;
  }
  .btn-edit {
    background-color: #28a745;
    color: white;
  }
  .btn-delete {
    background-color: #dc3545;
    color: white;
  }
  .btn-edit:hover, .btn-delete:hover {
    opacity: 0.9;
  }
  .pagination {
    justify-content: center;
    margin-top: 20px;
  }
</style>

<div class="container-custom">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="admin_dashboard.php" class="btn btn-secondary">← Back</a>
    <h2 class="text-primary fw-bold"><i class="bi bi-journal-bookmark-fill"></i> Manage Modules</h2>
    <div>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModuleModal">+ Add Module</button>
      <a href="?export=pdf" class="btn btn-danger">Export PDF</a>
    </div>
  </div>

  <form method="GET" class="input-group mb-3">
    <input type="text" name="search" class="form-control" placeholder="Search Modules..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
    <button class="btn btn-outline-primary">Search</button>
  </form>

  <?php if (!empty($message)): ?>
    <div class="alert alert-info alert-dismissible fade show text-center" role="alert">
      <?= $message ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <div class="table-container">
    <table class="table table-hover align-middle text-center">
      <thead>
        <tr>
          <th>#</th>
          <th>Module Name</th>
          <th>Description</th>
          <th>Module Leader</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php $sn = $offset + 1; while ($row = mysqli_fetch_assoc($result)) : ?>
          <tr>
            <td><?= $sn++ ?></td>
            <td><?= htmlspecialchars($row['ModuleName']) ?></td>
            <td><?= htmlspecialchars($row['Description']) ?></td>
            <td><?= $row['ModuleLeader'] ? htmlspecialchars($row['ModuleLeader']) : '<span class="text-danger">Not Assigned</span>' ?></td>
            <td>
              <a href="edit_module.php?id=<?= $row['ModuleID'] ?>" class="btn btn-sm btn-edit">Edit</a>
              <a href="delete_module.php?id=<?= $row['ModuleID'] ?>" class="btn btn-sm btn-delete" onclick="return confirm('Are you sure you want to delete this module?')">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <nav>
    <ul class="pagination">
      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
          <a class="page-link" href="?page=<?= $i ?><?= $search ? '&search=' . urlencode($search) : '' ?>"><?= $i ?></a>
        </li>
      <?php endfor; ?>
    </ul>
  </nav>
</div>

<!-- Add Module Modal -->
<div class="modal fade" id="addModuleModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="" method="POST">
        <div class="modal-header">
          <h5 class="modal-title">Add New Module</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Module Name</label>
            <input type="text" name="module_name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Module Description</label>
            <input type="text" name="module_desc" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Module Leader</label>
            <select name="module_leader" class="form-select" required>
              <option value="">Select Leader</option>
              <?php foreach ($staff_list as $staff): ?>
                <option value="<?= $staff['StaffID'] ?>"><?= htmlspecialchars($staff['Name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="add_module" class="btn btn-success">Save</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  setTimeout(() => {
    const alert = document.querySelector('.alert-dismissible');
    if (alert) alert.remove();
  }, 3000);
</script>