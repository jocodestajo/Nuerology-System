<?php
require '../../config/dbcon.php';
session_start();

// Initialize response
$response = ['success' => false, 'message' => ''];

if (isset($_POST['submit_limit'])) {
    
    $limit_new = mysqli_real_escape_string($conn, $_POST['online_F2F_limit']);
    $followUp = mysqli_real_escape_string($conn, $_POST['follow_up']);
    $limit_referral = mysqli_real_escape_string($conn, $_POST['referral_limit']);

    // Update the limit
    $query = "UPDATE neurology_weekdaysettings
              SET dailyLimit_new = '$limit_new', dailyLimit_referral = '$limit_referral', follow_up = '$followUp'";

    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        // Set session message instead of returning in response
        $_SESSION['message'] = "Saved Successfully!";
        $response['success'] = true;
        header("Location: ../../index.php");
    } else {
        $_SESSION['message'] = "Unsuccessful!";
        $response['message'] = "Database error occurred";
        header("Location: ../../index.php"); // Redirect after success
    }
}

// Output the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
exit;

?>