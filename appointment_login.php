<?php
session_start();
require 'config/dbcon.php';

if (isset($_SESSION['appointment_auth'])) {
    header("Location: online_appointment.php");
    exit();
}

if (isset($_POST['login_btn'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['userpass'])) {
            $_SESSION['appointment_auth'] = true;
            $_SESSION['appointment_user'] = [
                'id' => $user['userid'],
                'username' => $user['username'],
                'name' => $user['fname']
            ];
            header("Location: online_appointment.php");
            exit();
        } else {
            $_SESSION['message'] = "Invalid password";
        }
    } else {
        $_SESSION['message'] = "Invalid username";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Login</title>
    <link rel="icon" href="img/MMWGH_Logo.png" type="images/x-icon">
    <link rel="stylesheet" href="css/mainStyle.css">
    <link rel="stylesheet" href="css/general.css">
    <style>
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f5f5f5;
        }
        .login-form {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-form h2 {
            text-align: center;
            margin-bottom: 2rem;
            color: #333;
            font-weight: bold;
        }
        .form-group {
            margin-bottom: 1.2rem;
        }
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <h2>Appointment Login</h2>
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
            <?php endif; ?>
            <form action="appointment_login.php" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" class="btn width-100" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="btn width-100" required>
                </div>
                <button type="submit" name="login_btn" class="btn btn-green width-100">Log-in</button>
            </form>
        </div>
    </div>
</body>
</html> 