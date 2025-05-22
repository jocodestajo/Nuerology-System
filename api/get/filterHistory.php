<?php
require_once '../../config/dbcon.php';

// Get filter parameters
$status = $_POST['status'] ?? 'All';
$consultation = $_POST['consultation'] ?? 'All';
$fromDate = $_POST['fromDate'] ?? '';
$toDate = $_POST['toDate'] ?? '';

// Base query
$query = "SELECT r.id, r.hrn, r.name, r.contact, 
          c.consultation, c.date_sched, c.complaint, c.status, c.date_process
          FROM neurology_records r
          INNER JOIN neurology_consultations c ON r.id = c.record_id
          WHERE 1=1";

// Modify the status filter if "all" is selected
if ($status === 'All') {
    // Exclude 'pending' status and include only 'processed', 'follow up', 'cancelled'
    $query .= " AND c.status IN ('processed', 'follow up', 'cancelled')";
} else {
    // If specific status is selected, apply the filter
    $query .= " AND c.status = ?";
}

// Add consultation type filter
if ($consultation !== 'All') {
    $query .= " AND c.consultation = ?";
}

// Add date range filter
if (!empty($fromDate) && !empty($toDate)) {
    $query .= " AND c.date_sched BETWEEN ? AND ?";
}

// Add order by clause
$query .= " ORDER BY c.date_process DESC";

// Prepare and execute the query
$stmt = $conn->prepare($query);

// Bind parameters based on which filters are active
$types = '';
$params = [];

if ($status !== 'All') {
    $types .= 's';
    $params[] = $status;
}

if ($consultation !== 'All') {
    $types .= 's';
    $params[] = $consultation;
}

if (!empty($fromDate) && !empty($toDate)) {
    $types .= 'ss';
    $params[] = $fromDate;
    $params[] = $toDate;
}

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$records = [];
while ($row = $result->fetch_assoc()) {
    $records[] = $row;
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($records);

$stmt->close();
$conn->close();
?> 