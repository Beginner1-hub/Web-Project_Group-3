<?php
require_once 'connect.php';

if (!isset($_GET['id'])) {
    die("Programme ID missing.");
}

$programmeID = $_GET['id'];

// Fetch programme details
$stmt = $conn->prepare("SELECT * FROM programmes WHERE ProgrammeID = ?");
$stmt->bind_param("i", $programmeID);
$stmt->execute();
$result = $stmt->get_result();
$programme = $result->fetch_assoc();
$stmt->close();

// Fetch levels and staff
$levels = mysqli_query($conn, "SELECT * FROM levels");
$staff = mysqli_query($conn, "SELECT * FROM staff");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Programme</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.ckeditor.com/4.25.1/standard/ckeditor.js"></script>
</head>
<body class="bg-light">
<div class="container py-4">
  <h2>Edit Programme</h2>

  <?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-success">Programme updated successfully.</div>
  <?php endif; ?>

  <a href="manage_programs.php" class="btn btn-secondary mb-3">← Back to Manage Programmes</a>

  <form action="update_programme.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="ProgrammeID" value="<?= $programme['ProgrammeID'] ?>">

    <div class="mb-3">
      <label>Programme Name</label>
      <input type="text" name="ProgrammeName" class="form-control" value="<?= htmlspecialchars($programme['ProgrammeName']) ?>" required>
    </div>

    <div class="mb-3">
      <label>Level</label>
      <select name="LevelID" class="form-select" required>
        <?php while($level = mysqli_fetch_assoc($levels)): ?>
          <option value="<?= $level['LevelID'] ?>" <?= $level['LevelID'] == $programme['LevelID'] ? 'selected' : '' ?>>
            <?= $level['LevelName'] ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="mb-3">
      <label>Programme Leader</label>
      <select name="ProgrammeLeaderID" class="form-select" required>
        <?php while($staffMember = mysqli_fetch_assoc($staff)): ?>
          <option value="<?= $staffMember['StaffID'] ?>" <?= $staffMember['StaffID'] == $programme['ProgrammeLeaderID'] ? 'selected' : '' ?>>
            <?= $staffMember['Name'] ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="mb-3">
      <label>Description</label>
      <textarea name="Description" id="Description" class="form-control"><?= htmlspecialchars($programme['Description']) ?></textarea>
    </div>

    <div class="mb-3">
      <label>Current Image:</label><br>
      <?php if ($programme['Image']): ?>
        <img src="uploads/<?= $programme['Image'] ?>" width="100"><br>
      <?php else: ?>
        <em>No image uploaded.</em><br>
      <?php endif; ?>
      <label>Change Image (optional)</label>
      <input type="file" name="Image" class="form-control">
    </div>

    <div class="mb-3">
      <label>Status</label>
      <select name="Status" class="form-select">
        <option <?= $programme['Status'] == 'Published' ? 'selected' : '' ?>>Published</option>
        <option <?= $programme['Status'] == 'Unpublished' ? 'selected' : '' ?>>Unpublished</option>
        <option <?= $programme['Status'] == 'Draft' ? 'selected' : '' ?>>Draft</option>
      </select>
    </div>

    <button type="submit" class="btn btn-success">Update</button>
    <a href="manage_programs.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>

<script>CKEDITOR.replace('Description');</script>
</body>
</html>
