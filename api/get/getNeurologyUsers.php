<?php
require '../../config/dbcon.php';

header('Content-Type: application/json');

try {
    // Get users from neurology department (deptid = 63)
    $query = "SELECT u.userid, u.fname, u.lname, u.nameinit, u.username 
              FROM users u 
              INNER JOIN departments d ON u.deptid = d.deptid 
              WHERE d.deptname = 'Neurology' AND u.userstatus = 1 
              ORDER BY u.lname, u.fname";
    
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        throw new Exception("Database query failed: " . mysqli_error($conn));
    }
    
    $users = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = array(
            'userid' => $row['userid'],
            'fullname' => trim($row['fname'] . ' ' . $row['minitial'] . ' ' . $row['lname']),
            'nameinit' => $row['nameinit'],
            'username' => $row['username']
        );
    }
    
    echo json_encode(array(
        'status' => 'success',
        'data' => $users
    ));
    
} catch (Exception $e) {
    echo json_encode(array(
        'status' => 'error',
        'message' => $e->getMessage()
    ));
}

mysqli_close($conn);
?> 