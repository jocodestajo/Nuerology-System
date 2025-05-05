<?php
require '../../config/dbcon.php';

$year = $_GET['year'] ?? date('Y');
$month = $_GET['month'] ?? date('m');

// Format month with leading zero
$month = str_pad($month, 2, '0', STR_PAD_LEFT);

// Get F2F appointment counts
$f2f_query = "SELECT 
    c.date_sched,
    COUNT(*) AS count
FROM neurology_consultations c
LEFT JOIN neurology_records r ON c.record_id = r.id
WHERE YEAR(c.date_sched) = ? 
    AND MONTH(c.date_sched) = ?
    AND c.status = 'approved'
    AND c.consultation = 'face to face' OR c.status = 'follow up' AND c.consultation = 'face to face'
GROUP BY c.date_sched";


// Get Telecon appointment counts
$telecon_query = "SELECT 
    c.date_sched,
    COUNT(*) AS count
FROM neurology_consultations c
LEFT JOIN neurology_records r ON c.record_id = r.id
WHERE YEAR(c.date_sched) = ? 
    AND MONTH(c.date_sched) = ?
    AND c.status = 'approved'
    AND c.consultation = 'teleconsultation' OR c.status = 'follow up' AND c.consultation = 'teleconsultation'
    GROUP BY c.date_sched";

$counts = [];

// Get F2F counts
$stmt = mysqli_prepare($conn, $f2f_query);
mysqli_stmt_bind_param($stmt, "ss", $year, $month);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($result)) {
    if (!isset($counts[$row['date_sched']])) {
        $counts[$row['date_sched']] = ['f2f' => 0, 'telecon' => 0];
    }
    $counts[$row['date_sched']]['f2f'] = (int)$row['count'];
}

// Get Telecon counts
$stmt = mysqli_prepare($conn, $telecon_query);
mysqli_stmt_bind_param($stmt, "ss", $year, $month);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($result)) {
    if (!isset($counts[$row['date_sched']])) {
        $counts[$row['date_sched']] = ['f2f' => 0, 'telecon' => 0];
    }
    $counts[$row['date_sched']]['telecon'] = (int)$row['count'];
}

header('Content-Type: application/json');
echo json_encode($counts);
?> 