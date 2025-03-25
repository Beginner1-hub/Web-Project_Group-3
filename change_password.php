<?php
session_start();
include("connect.php");

$user_type = $_GET['user_type'] ?? null;
$token = $_GET['token'] ?? null;
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword !== $confirmPassword) {
        $message = "❌ Passwords do not match.";
    } elseif (strlen($newPassword) < 6) {
        $message = "❌ Password must be at least 6 characters.";
    } else {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Case 1: Reset via Token
        if ($token) {
            $stmt = $conn->prepare("SELECT StaffID FROM staff WHERE ResetToken = ? AND TokenExpiry > NOW()");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($user = $result->fetch_assoc()) {
                $staffID = $user['StaffID'];

                $update = $conn->prepare("UPDATE staff SET Password = ?, ResetToken = NULL, TokenExpiry = NULL WHERE StaffID = ?");
                $update->bind_param("si", $hashedPassword, $staffID);
                $update->execute();

                $message = "✅ Password reset successful. You can now login.";
                header("refresh:2;url=login.php?user_type=Staff");
                exit();
            } else {
                $message = "❌ Invalid or expired token.";
            }
        }

        // Case 2: Logged in Staff
        elseif (isset($_SESSION['username']) && $_SESSION['user_type'] === 'Staff') {
            $username = $_SESSION['username'];
            $update = $conn->prepare("UPDATE staff SET Password = ? WHERE Username = ?");
            $update->bind_param("ss", $hashedPassword, $username);
            $update->execute();

            $message = "✅ Password updated successfully.";
            header("refresh:2;url=staff_dashboard.php");
            exit();
        } else {
            $message = "Unauthorized access.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #0f2027, #203a43, #2c5364);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background: #1f2b3a;
            padding: 30px 40px;
            border-radius: 10px;
            width: 400px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .btn {
            width: 100%;
            padding: 12px;
            background: #00d4ff;
            color: black;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        .btn:hover {
            background: #00aacc;
        }
        .message {
            text-align: center;
            color: yellow;
            margin-bottom: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2><?= $token ? "Reset Password" : "Change Password" ?></h2>
        <?php if ($message): ?>
            <p class="message"><?= $message ?></p>
        <?php endif; ?>
        <form method="POST">
            <label>New Password:</label>
            <input type="password" name="new_password" required>

            <label>Confirm Password:</label>
            <input type="password" name="confirm_password" required>

            <button type="submit" class="btn">Submit</button>
        </form>
    </div>
</body>
</html>
