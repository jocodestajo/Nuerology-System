<?php
require '../../config/dbcon.php';

header('Content-Type: application/json');

// Get filter parameters
$timeframe = isset($_GET['timeframe']) ? $_GET['timeframe'] : 'Last 7 days';
$patientType = isset($_GET['patient-type']) ? $_GET['patient-type'] : 'All Patients';

// Initialize variables
$whereClauses = [];
$params = [];
$types = '';

// Build WHERE conditions based on filters
if ($timeframe != 'All') {
    $now = new DateTime();
    switch ($timeframe) {
        case 'Last 7 days':
            $date = $now->sub(new DateInterval('P7D'))->format('Y-m-d');
            $whereClauses[] = "c.date_sched >= ?";
            $params[] = $date;
            $types .= 's';
            break;
        case 'Last 30 days':
            $date = $now->sub(new DateInterval('P30D'))->format('Y-m-d');
            $whereClauses[] = "c.date_sched >= ?";
            $params[] = $date;
            $types .= 's';
            break;
        case 'Last 90 days':
            $date = $now->sub(new DateInterval('P90D'))->format('Y-m-d');
            $whereClauses[] = "c.date_sched >= ?";
            $params[] = $date;
            $types .= 's';
            break;
        case 'Last year':
            $date = $now->sub(new DateInterval('P1Y'))->format('Y-m-d');
            $whereClauses[] = "c.date_sched >= ?";
            $params[] = $date;
            $types .= 's';
            break;
        // Custom range would need additional handling
    }
}

if ($patientType != 'All Patients') {
    $whereClauses[] = "c.status = ?";
    $params[] = $patientType;
    $types .= 's';
}

// Build the base query
$query = "SELECT r.hrn, r.name, 
          c.date_sched, c.processed_date, c.department, c.diagnosis, c.status
          FROM neurology_consultations c
          JOIN neurology_records r ON c.record_id = r.id";

// Add WHERE conditions if any
if (!empty($whereClauses)) {
    $query .= " WHERE " . implode(" AND ", $whereClauses);
}

// Prepare and execute the query
$stmt = $conn->prepare($query);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$results = $result->fetch_all(MYSQLI_ASSOC);

// Calculate summary statistics
$totalPatients = count($results);

// For other summary stats, you might need additional queries
$summaryStats = [
    'totalPatients' => $totalPatients,
    'averageStay' => '3.2 days', // You would calculate this from your data
    'readmissionRate' => '8.5%', // You would calculate this from your data
    'satisfactionScore' => '4.6/5' // You would get this from your data
];

$response = [
    'summary' => $summaryStats,
    'data' => $results
];

echo json_encode($response);

// Close connection
$stmt->close();
$conn->close();
?>