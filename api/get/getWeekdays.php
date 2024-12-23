<?php
require '../../config/dbcon.php';

$query = "SELECT * FROM weekday_settings";
$result = mysqli_query($conn, $query);

$weekdays = [];
while ($row = mysqli_fetch_assoc($result)) {
    $weekdays[$row['day_name']] = (bool)$row['is_enabled'];
}

echo json_encode($weekdays);
?> 