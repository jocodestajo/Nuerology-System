<?php
require '../../config/dbcon.php';

$year = $_GET['year'] ?? date('Y');
$month = $_GET['month'] ?? date('m');

// Format month with leading zero
$month = str_pad($month, 2, '0', STR_PAD_LEFT);

// Get F2F appointment counts
$f2f_query = "SELECT 
    date_sched,
    COUNT(*) as count
    FROM neurology_records 
    WHERE YEAR(date_sched) = ? 
    AND MONTH(date_sched) = ?
    AND status = 'approved'
    AND consultation = 'face to face'
    GROUP BY date_sched";

// Get Telecon appointment counts
$telecon_query = "SELECT 
    date_sched,
    COUNT(*) as count
    FROM neurology_records 
    WHERE YEAR(date_sched) = ? 
    AND MONTH(date_sched) = ?
    AND status = 'approved'
    AND consultation = 'teleconsultation'
    GROUP BY date_sched";

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