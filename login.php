<?php
session_start();
require 'config/dbcon.php';

if(isset($_SESSION['auth'])) {
    header("Location: index.php");
    exit();
}

if(isset($_POST['login_btn'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Debug: Print the username being checked
    error_log("Attempting login for username: " . $username);

    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Debug: Print the stored hash
        error_log("Stored hash: " . $user['userpass']);
        
        if(password_verify($password, $user['userpass'])) {
            $_SESSION['auth'] = true;
            $_SESSION['auth_user'] = [
                'userid' => $user['userid'],
                'username' => $user['username'],
                'userprivilege' => $user['userprivilege'],
                'fname' => $user['fname']
            ];
            header("Location: index.php");
            exit();
        } else {
            // Debug: Print password verification failure
            error_log("Password verification failed for user: " . $username);
            $_SESSION['message'] = "Invalid password";
            header("Location: login.php");
            exit();
        }
    } else {
        // Debug: Print no user found
        error_log("No user found with username: " . $username);
        $_SESSION['message'] = "Invalid username";
        header("Location: login.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Neurology</title>
    <link rel="icon" href="img/MMWGH_Logo.png" type="images/x-icon">
    <link rel="stylesheet" href="css/mainStyle.css">
    <link rel="stylesheet" href="css/general.css">
    <style>
        .header-container-login {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            display: flex;
            align-items: center;
            padding: 10px;
            padding-left: 40%;
            background-color: var(--primary-color);
            border-bottom: 1px solid #eee;
        }
        .header-container-login img {
            height: 50px;
            margin-right: 15px;
        }
        .header-container-login h1 {
            color: var(--white-color);
            font-size: 1.8rem;
        }
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

        .form-group input {
            text-align: left;
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
    <div class="header-container-login" style="">
        <img src="img/MMWGH_Logo.png" alt="MMWGH Logo" style="height: 50px; margin-right: 15px;">
        <h1>Neurology Unit</h1>
    </div>
    <div class="login-container">
        <div class="login-form">
            <h2>LOGIN</h2>
            
            <?php include('includes/messages/message.php'); ?>

            <form action="login.php" method="POST">
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

            <!-- Add this form for creating admin user if needed -->
            <!-- <form action="login.php" method="POST" style="margin-top: 20px;">
                <button type="submit" name="create_admin" class="login-btn" style="background-color: #2196F3;">Create Admin User</button>
            </form> -->
        </div>
    </div>
</body>
</html> 