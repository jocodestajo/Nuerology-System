<?php
require '../../config/dbcon.php';

// Define daily limits
$dailyLimitNew = 5; // Example limit for new consultations
$dailyLimitReferral = 3; // Example limit for referrals

// Fetch appointment counts grouped by date and type, including old_new and status
$query = "
    SELECT 
        date_sched, 
        appointment_type, 
        old_new,
        status,
        COUNT(*) as count 
    FROM 
        neurology_consultations 
    GROUP BY 
        date_sched, 
        appointment_type, 
        old_new,
        status
";

$result = mysqli_query($conn, $query);

$appointmentCounts = [];

while ($row = mysqli_fetch_assoc($result)) {
    $date = $row['date_sched'];
    $type = $row['appointment_type'];
    $oldNew = $row['old_new'];
    $status = $row['status'];
    $count = (int)$row['count'];

    if (!isset($appointmentCounts[$date])) {
        $appointmentCounts[$date] = [];
    }

    $appointmentCounts[$date][] = [
        'type' => $type,
        'old_new' => $oldNew,
        'status' => $status,
        'count' => $count
    ];
}

// Include daily limits in the response
$response = [
    'appointmentCounts' => $appointmentCounts,
    'dailyLimits' => [
        'new' => $dailyLimitNew,
        'referral' => $dailyLimitReferral
    ]
];

echo json_encode($response);
?>
