<?php
error_reporting (0);

// This is a connection that is always require in every page everytime may db connection.
$conn = mysqli_connect("localhost","root","","ihoms_inventory");

if(!$conn){
    die('Connection Failed'. mysqli_connect_error());
}

$AppID = 7;

// echo $_SESSION['auth_user']['userid'];

if($activpage != "index") {
    if($_SESSION['auth_user']['userid'] > 0) {
        $privcheck = $conn->query("SELECT * FROM userpriv WHERE userid = " . $_SESSION['auth_user']['userid'] . " AND appid = $AppID");
        // echo $privcheck;
        if(!$privrow = $privcheck->fetch_assoc()) {
            echo '<div style="text-align: center; margin-top: 100px; font-size: 28px; font-weight: bold;">You are not allowed to view this page. Try <a href="logout.php">logging in</a> a different account.</div>';
            exit;
        }
    } else {
        header('location: login.php');
        exit;
    }
}
?>

