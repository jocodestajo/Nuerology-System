<?php
require '../../config/dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $weekdays = json_decode(file_get_contents('php://input'), true);
    
    // Clear existing weekday settings
    $clearQuery = "DELETE FROM weekday_settings";
    mysqli_query($conn, $clearQuery);
    
    // Insert new settings
    foreach ($weekdays as $day => $isChecked) {
        $day = mysqli_real_escape_string($conn, $day);
        $isChecked = $isChecked ? 1 : 0;
        
        $query = "INSERT INTO weekday_settings (day_name, is_enabled) VALUES ('$day', $isChecked)";
        mysqli_query($conn, $query);
    }
    
    echo json_encode(['success' => true]);
}
?> 