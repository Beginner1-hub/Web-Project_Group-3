<?php
session_start();
include('connect.php');

if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== "Admin") {
    header("Location: login.php");
    exit();
}

// Handle delete
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_id'])) {
    $deleteId = intval($_POST['delete_id']);
    mysqli_query($conn, "DELETE FROM interestedstudents WHERE InterestID = $deleteId");
    header("Location: view_interested_students.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Interested Students</title>
    <link rel="stylesheet" href="interested_styles.css">

    <!-- DataTables + jQuery -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
</head>
<body>

<div class="main-container">
    <div class="card">
        <a href="admin_dashboard.php" class="back-btn">← Back to Dashboard</a>
        <h1>📋 Interested Students</h1>
        <p>Browse, export or manage student interest registrations in your programmes.</p>

        <table id="students-table" class="display nowrap">
            <thead>
                <tr>
                    <th>👤 Name</th>
                    <th>📧 Email</th>
                    <th>🎓 Programme</th>
                    <th>🕒 Registered</th>
                    <th>🗑️ Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "
                    SELECT i.InterestID, i.StudentName, i.StudentEmail, p.ProgrammeName, i.InterestedAt
                    FROM interestedstudents i
                    LEFT JOIN programmes p ON i.ProgrammeID = p.ProgrammeID
                    ORDER BY i.InterestedAt DESC
                ";
                $result = mysqli_query($conn, $query);
                while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['StudentName']); ?></td>
                        <td><?php echo htmlspecialchars($row['StudentEmail']); ?></td>
                        <td><?php echo htmlspecialchars($row['ProgrammeName']); ?></td>
                        <td><?php echo htmlspecialchars($row['InterestedAt']); ?></td>
                        <td>
                            <form method="post" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                <input type="hidden" name="delete_id" value="<?php echo $row['InterestID']; ?>">
                                <button type="submit" class="delete-btn">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function () {
        let table = $('#students-table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'csvHtml5',
                    text: '📤 Export CSV',
                    className: 'export-btn'
                }
            ],
            pageLength: 10,
            responsive: true
        });

        // Fix layout shift issue
        setTimeout(() => {
            table.columns.adjust().responsive.recalc();
        }, 200);
    });
</script>


</body>
</html>
