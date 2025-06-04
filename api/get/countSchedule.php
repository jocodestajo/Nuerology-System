<?php
require '../../config/dbcon.php';

// Define daily limits
$dailyLimitNew = 5; // Example limit for new consultations
$dailyLimitReferral = 3; // Example limit for referrals

// Fetch appointment counts grouped by date and type
$query = "
    SELECT 
        date_sched, 
        old_new, 
        typeofappoint, 
        COUNT(*) as count 
    FROM 
        neurology_consultations 
    WHERE 
        status IN ('pending', 'approved', 'follow up') 
    GROUP BY 
        date_sched, 
        old_new, 
        typeofappoint
";

$result = mysqli_query($conn, $query);

$appointmentCounts = [];

while ($row = mysqli_fetch_assoc($result)) {
    $date = $row['date_sched'];
    $oldNew = $row['old_new'];
    $typeofappoint = $row['typeofappoint'];
    $count = (int)$row['count'];

    if (!isset($appointmentCounts[$date])) {
        $appointmentCounts[$date] = ['new' => 0, 'referral' => 0];
    }

    if ($oldNew === 'New') {
        $appointmentCounts[$date]['new'] += $count;
    } else if ($typeofappoint === 'Referral') {
        $appointmentCounts[$date]['referral'] += $count;
    }
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
