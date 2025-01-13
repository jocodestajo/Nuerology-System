<?php
require 'dbcon.php';

// Check if table is empty
$checkQuery = "SELECT COUNT(*) as count FROM weekday_settings";
$checkResult = mysqli_query($conn, $checkQuery);
$row = mysqli_fetch_assoc($checkResult);

if ($row['count'] == 0) {
    // Initialize with all days enabled
    $defaultDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
    
    foreach ($defaultDays as $day) {
        $query = "INSERT INTO weekday_settings (day_name, is_enabled) VALUES ('$day', 1)";
        mysqli_query($conn, $query);
    }
}
?> 