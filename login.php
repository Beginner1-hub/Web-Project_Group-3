<?php
session_start();
include('connect.php');

// Get user type (Admin or Staff)
$user_type = isset($_GET['user_type']) ? $_GET['user_type'] : 'Admin';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password']; // ✅ lowercase 'password'

    if ($user_type === "Admin" || $user_type === "Staff") {
        $table = ($user_type === "Admin") ? "admins" : "staff";
        $identifier = ($user_type === "Admin") ? "Username" : "Username";
        $passwordField = ($user_type === "Admin") ? "PasswordHash" : "Password";

        $query = "SELECT * FROM $table WHERE $identifier = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row[$passwordField])) {
                $_SESSION['username'] = $row[$identifier];
                $_SESSION['user_type'] = $user_type;
                header("Location: " . strtolower($user_type) . "_dashboard.php");
                exit();
            } else {
                $error = "❌ Incorrect password.";
            }
        } else {
            $error = "❌ Invalid $identifier.";
        }
    } else {
        $error = "Invalid user type.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $user_type ?> Login</title>
    <style>
        body {
            background: url('uploads/stud.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .back-home {
            position: absolute;
            top: 20px;
            left: 20px;
            background: #1e3c72;
            color: #fff;
            padding: 10px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
        }

        .login-box {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 15px;
            width: 400px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            text-align: center;
        }

        h2 {
            margin-bottom: 25px;
            color: #2c3e50;
        }

        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(to right, #43cea2, #185a9d);
            border: none;
            color: white;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-login:hover {
            opacity: 0.9;
        }

        .error {
            color: red;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .forgot-link {
            margin-top: 15px;
            display: block;
            font-size: 14px;
        }
    </style>
</head>
<body>
<a href="index.php" class="back-home">← Back to Home</a>

<div class="login-box">
    <h2><?= $user_type ?> Login</h2>

    <?php if (isset($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
        <div class="input-group">
            <label><?= ($user_type === "Admin") ? "Username" : "Username"; ?></label>
            <input type="text" name="username" placeholder="Enter your username" required>
        </div>
        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter your password" required>
        </div>
        <button type="submit" class="btn-login">Login</button>
    </form>

    <?php if ($user_type === "Staff"): ?>
        <a href="change_password.php?forgot=1" class="forgot-link">Forgot Password?</a>
    <?php endif; ?>
</div>
</body>
</html>
