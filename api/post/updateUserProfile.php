<?php
session_start();
require_once '../../config/dbcon.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if (!isset($_SESSION['auth_user']['userid'])) {
    $response['message'] = 'Unauthorized.';
    echo json_encode($response);
    exit;
}

$userid = $_SESSION['auth_user']['userid'];
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');
$fname = trim($_POST['fname'] ?? '');
$lname = trim($_POST['lname'] ?? '');

if ($username === '' || $fname === '' || $lname === '') {
    $response['message'] = 'All fields except password are required.';
    echo json_encode($response);
    exit;
}

try {
    if ($password !== '') {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET username = ?, password = ?, fname = ?, lname = ? WHERE userid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssi', $username, $hashedPassword, $fname, $lname, $userid);
    } else {
        $sql = "UPDATE users SET username = ?, fname = ?, lname = ? WHERE userid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssi', $username, $fname, $lname, $userid);
    }
    if ($stmt->execute()) {
        // Update session data
        $_SESSION['auth_user']['username'] = $username;
        $_SESSION['auth_user']['fname'] = $fname;
        $_SESSION['auth_user']['lname'] = $lname;
        $response['success'] = true;
        $_SESSION['message'] = 'Profile updated successfully.';
    } else {
        $response['message'] = 'Failed to update profile.';
    }
    $stmt->close();
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
}

$conn->close();
echo json_encode($response);
exit; 