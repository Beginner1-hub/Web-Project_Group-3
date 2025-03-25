<?php
require_once 'connect.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$query = "
    SELECT p.*, s.Name AS StaffName, l.LevelName
    FROM programmes p
    LEFT JOIN staff s ON p.ProgrammeLeaderID = s.StaffID
    LEFT JOIN levels l ON p.LevelID = l.LevelID
    ORDER BY p.ProgrammeID DESC
";
$result = mysqli_query($conn, $query);
$staff = mysqli_query($conn, "SELECT * FROM staff");
$levels = mysqli_query($conn, "SELECT * FROM levels");
$message = $_SESSION['msg'] ?? '';
unset($_SESSION['msg']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Programmes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.ckeditor.com/4.25.1/standard/ckeditor.js"></script>
  <style>
    body {
      background: linear-gradient(to right, #f4f7fa, #e9edf2);
      font-family: 'Segoe UI', sans-serif;
    }
    .container-fluid {
      padding: 2rem;
    }
    .card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 6px 24px rgba(0, 0, 0, 0.1);
      background-color: #ffffff;
    }
    .table thead th {
      background-color: #0d6efd;
      color: white;
      border: none;
    }
    .table-bordered td, .table-bordered th {
      border: 1px solid #dee2e6;
    }
    .btn-back {
      margin-bottom: 20px;
      font-weight: 700;
      border-radius: 30px;
      padding: 6px 16px;
      background: linear-gradient(to right, #dee2e6, #cdd4da);
      border: none;
      color: #333;
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
      transition: all 0.3s ease;
    }
    .btn-back:hover {
      background: #adb5bd;
      color: white;
    }
    .btn-primary.rounded-pill {
      border-radius: 30px;
      font-weight: 700;
    }
    .btn-warning, .btn-danger {
      font-size: 0.85rem;
      font-weight: 500;
      border-radius: 30px;
      padding: 4px 12px;
    }
    .modal-content {
      border-radius: 15px;
      box-shadow: 0 12px 32px rgba(0, 0, 0, 0.15);
    }
    .form-label {
      font-weight: 600;
    }
    .alert {
      font-weight: 500;
    }
    h3 img {
      height: 28px;
      margin-right: 10px;
    }
  </style>
</head>
<body>
<div class="container-fluid">
  <a href="admin_dashboard.php" class="btn btn-back d-inline-flex align-items-center">
    <img src="https://img.icons8.com/ios-filled/20/000000/circled-left-2.png" alt="Back Icon" class="me-2">
    Back to Dashboard
</a>
  <div class="card p-4 border-start border-4 border-primary">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="mb-0 d-flex align-items-center"><span class="me-2 text-primary">Admin Panel /</span>
        <img src="https://img.icons8.com/fluency/32/book-stack.png" alt="icon">
        Manage Programmes
      </h3>
      <button class="btn btn-primary px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#addModal">+ Add Programme</button>
    </div>

    <?php if (!empty($message)): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $message ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <div class="table-responsive">
      <table class="table table-bordered table-striped table-hover align-middle text-center">
        <thead>
          <tr>
            <th>#</th>
            <th>Programme Name</th>
            <th>Level</th>
            <th>Leader</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php $sn = 1; while($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?= $sn++ ?></td>
            <td><?= htmlspecialchars($row['ProgrammeName']) ?></td>
            <td><?= htmlspecialchars($row['LevelName']) ?></td>
            <td><?= htmlspecialchars($row['StaffName']) ?></td>
            <td>
              <form method="post" action="update_status.php" class="d-inline">
                <input type="hidden" name="ProgrammeID" value="<?= $row['ProgrammeID'] ?>">
                <select name="Status" class="form-select form-select-sm" onchange="this.form.submit()">
                  <option <?= $row['Status'] == 'Published' ? 'selected' : '' ?>>Published</option>
                  <option <?= $row['Status'] == 'Unpublished' ? 'selected' : '' ?>>Unpublished</option>
                  <option <?= $row['Status'] == 'Draft' ? 'selected' : '' ?>>Draft</option>
                </select>
              </form>
            </td>
            <td>
              <a href="edit_programme.php?id=<?= $row['ProgrammeID'] ?>" class="btn btn-sm btn-warning rounded-pill">Edit</a>
              <a href="delete_programme.php?id=<?= $row['ProgrammeID'] ?>" class="btn btn-sm btn-danger rounded-pill" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Programme Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="add_programme.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title">Add New Programme</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Programme Name</label>
            <input type="text" name="ProgrammeName" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Level</label>
            <select name="LevelID" class="form-select" required>
              <?php while($level = mysqli_fetch_assoc($levels)): ?>
                <option value="<?= $level['LevelID'] ?>"><?= $level['LevelName'] ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Programme Leader</label>
            <select name="ProgrammeLeaderID" class="form-select" required>
              <?php while($staffMember = mysqli_fetch_assoc($staff)): ?>
                <option value="<?= $staffMember['StaffID'] ?>"><?= $staffMember['Name'] ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="Description" id="Description" class="form-control"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="Status" class="form-select">
              <option value="Published">Published</option>
              <option value="Unpublished">Unpublished</option>
              <option value="Draft">Draft</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success rounded-pill">Save</button>
          <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>CKEDITOR.replace('Description');</script>
<script>
  setTimeout(() => {
    const alert = document.querySelector('.alert-dismissible');
    if (alert) {
      alert.classList.remove('show');
      alert.classList.add('fade');
    }
  }, 3000);
</script>
</body>
</html>
