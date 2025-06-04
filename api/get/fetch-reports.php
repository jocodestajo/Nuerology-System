<?php
// Database connection
include '../../config/dbcon.php';

// Get filters from AJAX (if any)
$timeframe = $_GET['timeframe'] ?? '';
$patientType = $_GET['patientType'] ?? '';

// Build SQL query
$sql = "SELECT r.hrn, r.name, c.date_sched, c.date_process, c.status
        FROM neurology_records r
        JOIN neurology_consultations c ON r.id = c.record_id
        WHERE 1";

// Timeframe filter
if ($timeframe == "Last 7 days") {
    $sql .= " AND c.date_sched >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
} else if ($timeframe == "Last 30 days") {
    $sql .= " AND c.date_sched >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
} else if ($timeframe == "Last 90 days") {
    $sql .= " AND c.date_sched >= DATE_SUB(CURDATE(), INTERVAL 90 DAY)";
} else if ($timeframe == "Last year") {
    $sql .= " AND c.date_sched >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)";
}

// Patient type filter
if ($patientType && $patientType != "All Patients") {
    $sql .= " AND c.status = '" . $conn->real_escape_string($patientType) . "'";
}

$sql .= " ORDER BY c.date_sched DESC";

$result = $conn->query($sql);

$data = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);
$conn->close();
?>