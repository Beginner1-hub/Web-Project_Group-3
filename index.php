<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_course_hub";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Student Course Hub - Home</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #eef5ff;
      color: #333;
    }

    /* ✅ Professional Header Background */
    header {
      background: #102a43;
      color: white;
      padding: 15px 5%;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    nav {
      display: flex;
      gap: 20px;
    }

    /* ✅ Updated Home & Program Buttons */
    nav a {
      color: white;
      text-decoration: none;
      padding: 12px 22px;
      border-radius: 6px;
      transition: background 0.3s ease;
      font-weight: 600;
      font-size: 18px;
      letter-spacing: 0.5px;
    }

    nav a:hover {
      background: rgba(255, 255, 255, 0.2);
    }

    .dropdown {
      position: relative;
    }

    .dropbtn {
      background: linear-gradient(90deg, #00b4d8, #0077b6);
      color: white;
      padding: 10px 22px;
      font-size: 16px;
      border: none;
      border-radius: 30px;
      cursor: pointer;
      font-weight: bold;
      box-shadow: 0 4px 10px rgba(0, 114, 255, 0.3);
      transition: all 0.3s ease;
    }

    .dropbtn:hover {
      transform: scale(1.05);
      box-shadow: 0 6px 16px rgba(0, 114, 255, 0.5);
    }

    .dropdown-content {
      display: none;
      position: absolute;
      right: 0;
      background-color: white;
      min-width: 200px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      border-radius: 8px;
      z-index: 1;
    }

    .dropdown-content a {
      color: #333;
      padding: 12px 20px;
      text-decoration: none;
      display: block;
      border-bottom: 1px solid #eee;
      font-weight: 500;
    }

    .dropdown-content a:hover {
      background-color: #f9f9f9;
    }

    .dropdown:hover .dropdown-content {
      display: block;
    }

    .hero {
      background: url('uploads/9.jpg') no-repeat center center/cover;
      height: 500px;
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .hero::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.3); /* ← made lighter for better contrast */
}

    .hero-content {
      position: relative;
      z-index: 2;
      text-align: center;
      padding: 0 20px;
      max-width: 800px;
    }

    .hero-content h2 {
      font-size: 3.8rem;
      font-weight: 800;
      margin-bottom: 10px;
      color: #ffffff;
      text-shadow: 3px 3px 12px rgba(255, 255, 255, 0.6);
    }

    .hero-content h3 {
      font-size: 3.2rem;
      font-weight: 800;
      margin-bottom: 25px;
      color: #00d4ff;
      text-shadow: 2px 2px 10px rgba(0, 212, 255, 0.6);
    }

    .hero-content p {
      font-size: 1.4rem;
      color: white;
      background: rgba(0, 0, 0, 0.5);
      padding: 15px 25px;
      border-radius: 8px;
      display: inline-block;
      margin-bottom: 40px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    }

    .btn-explore {
      background: linear-gradient(90deg, #ff9800, #ff5722);
      color: white;
      font-size: 1.3rem;
      padding: 15px 45px;
      border-radius: 50px;
      font-weight: bold;
      box-shadow: 0 6px 18px rgba(255, 87, 34, 0.4);
      transition: 0.3s ease;
      display: inline-block;
      text-decoration: none;
    }

    .btn-explore:hover {
      transform: scale(1.06);
      box-shadow: 0 8px 24px rgba(255, 87, 34, 0.6);
    }

    .intro {
      padding: 60px 20px;
      background-color: #ffffff;
      text-align: center;
    }

    .intro h2 {
      font-size: 2.5rem;
      color: #003366;
      margin-bottom: 20px;
    }

    .intro p {
      font-size: 1.2rem;
      color: #444;
      max-width: 700px;
      margin: 0 auto;
    }

    footer {
      background: #102a43;
      color: white;
      text-align: center;
      padding: 20px 0;
      font-size: 1rem;
      margin-top: 60px;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<header>
  <nav>
    <a href="index.php">Home</a>
    <a href="student_dashboard.php">Programs</a>
  </nav>

  <div class="dropdown">
    <button class="dropbtn">🔐 Login</button>
    <div class="dropdown-content">
      <a href="login.php?user_type=Staff">Login as Staff</a>
      <a href="login.php?user_type=Admin">Login as Admin</a>
    </div>
  </div>
</header>

<!-- Hero Section -->
<section class="hero">
  <div class="hero-content">
    <h2>Welcome to</h2>
    <h3>Student Course Hub</h3>
    <p>Explore top-notch programs and begin your academic adventure with confidence.</p>
    <a href="student_dashboard.php" class="btn-explore">🚀 Explore Programs</a>
  </div>
</section>

<!-- Intro Section -->
<section class="intro">
  <h2>Start Your Learning Journey</h2>
  <p>Discover courses that align with your passion and career goals. Whether you’re looking for hands-on technical training or deep academic knowledge, we’ve got something for every learner.</p>
</section>

<!-- Footer -->
<footer>
  <p>&copy; 2025 Student Course Hub | All Rights Reserved</p>
</footer>

</body>
</html>
