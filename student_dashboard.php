<?php
session_start();
include "connect.php"; 

// Fetch available levels (Undergraduate/Postgraduate)
$levels_query = "SELECT * FROM levels";
$levels_result = mysqli_query($conn, $levels_query);

// Fetch all programmes along with their levels
$programmes_query = "SELECT p.*, l.LevelName 
                     FROM programmes p
                     JOIN levels l ON p.LevelID = l.LevelID";
$programmes_result = mysqli_query($conn, $programmes_query);

$defaultImage = "uploads/default.jpg"; // Default image if not found
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Programmes</title>
    <style>
        body {
            background: url('uploads/a.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            color: white;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            margin: auto;
            text-align: center;
        }
        .top-nav {
            text-align: left;
            margin: 15px;
        }
        .back-btn {
            background-color: rgba(0, 0, 0, 0.7);
            color: #00d4ff;
            padding: 10px 20px;
            border: 2px solid #00d4ff;
            border-radius: 8px;
            font-size: 16px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }
        .back-btn:hover {
            background-color: #00d4ff;
            color: black;
        }
        h1 {
            margin-top: 10px;
        }
        .filter-section {
            margin-bottom: 20px;
            font-size: 18px;
        }
        .programme-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .programme-card {
            width: 250px;
            height: 250px;
            background-size: cover;
            background-position: center;
            border-radius: 10px;
            position: relative;
            cursor: pointer;
            transition: 0.3s;
        }
        .programme-card:hover {
            transform: scale(1.05);
        }
        .programme-card .info {
            position: absolute;
            bottom: 0;
            width: 100%;
            background: black;
            padding: 10px;
            color: white;
            text-align: center;
            border-radius: 0 0 10px 10px;
            font-weight: bold;
        }
        .hidden {
            display: none !important;
        }
    </style>
</head>
<body>
    <!-- Back to Home Button -->
    <div class="top-nav">
        <a href="index.php" class="back-btn">🏠 Back to Home</a>
    </div>

    <div class="container">
        <h1>Available Programmes</h1>
        
        <!-- Filter Section -->
        <div class="filter-section">
            <label for="level">Filter by Level:</label>
            <select id="level" onchange="filterProgrammes()" style="padding: 10px; font-size: 16px;">
                <option value="all">All Levels</option>
                <?php while ($level = mysqli_fetch_assoc($levels_result)) { ?>
                    <option value="<?= $level['LevelName']; ?>"><?= $level['LevelName']; ?></option>
                <?php } ?>
            </select>
        </div>

        <!-- Programmes Display -->
        <div class="programme-grid">
            <?php while ($programme = mysqli_fetch_assoc($programmes_result)) { 
                $programmeName = trim($programme['ProgrammeName']);
                $imageName = $programme['Image'];

                // Specific workaround for BSc Artificial Intelligence
                if ($programmeName === "BSc Artificial Intelligence") {
                    $image = "uploads/ai3.avif";
                } else {
                    $image = (!empty($imageName) && file_exists("uploads/$imageName")) ? "uploads/$imageName" : $defaultImage;
                }
            ?>
                <div class="programme-card" data-level="<?= $programme['LevelName']; ?>" onclick="redirectToDetails(<?= $programme['ProgrammeID']; ?>)" style="background-image: url('<?= $image; ?>');">
                    <div class="info"><?= $programme['ProgrammeName']; ?></div>
                </div>
            <?php } ?>
        </div>
    </div>

    <script>
        function filterProgrammes() {
            let level = document.getElementById("level").value;
            let cards = document.querySelectorAll(".programme-card");

            cards.forEach(card => {
                if (level === "all" || card.getAttribute("data-level") === level) {
                    card.classList.remove("hidden");
                } else {
                    card.classList.add("hidden");
                }
            });
        }

        function redirectToDetails(programmeID) {
            window.location.href = "programme_details.php?id=" + programmeID;
        }
    </script>
</body>
</html>
