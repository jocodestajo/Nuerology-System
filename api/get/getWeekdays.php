<?php
require '../../config/dbcon.php';

// First, check if any settings exist
$checkQuery = "SELECT COUNT(*) as count FROM neurology_weekdaySettings";
$checkResult = mysqli_query($conn, $checkQuery);
$row = mysqli_fetch_assoc($checkResult);

if ($row['count'] == 0) {
    // If no settings exist, create default settings (all days enabled)
    $defaultDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
    foreach ($defaultDays as $day) {
        $query = "INSERT INTO weekday_settings (day_name, is_enabled) VALUES ('$day', 1)";
        mysqli_query($conn, $query);
    }
}

// Get the settings
$query = "SELECT * FROM neurology_weekdaySettings";
$result = mysqli_query($conn, $query);

$weekdays = [];
while ($row = mysqli_fetch_assoc($result)) {
    $weekdays[$row['day_name']] = (bool)$row['is_enabled'];
}

echo json_encode($weekdays);
?> 