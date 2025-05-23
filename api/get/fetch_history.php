<?php
require_once '../../config/dbcon.php';

// Get the record ID from the request
$record_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$record_id) {
    echo json_encode(['success' => false, 'message' => 'Record ID is required']);
    exit;
}

// Fetch patient data
$query = "SELECT r.*, c.* 
          FROM neurology_records r 
          LEFT JOIN neurology_consultations c ON r.id = c.record_id 
          WHERE r.id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $record_id);
$stmt->execute();
$result = $stmt->get_result();
$patient = $result->fetch_assoc();

if ($patient) {
    echo json_encode(['success' => true, 'data' => $patient]);
} else {
    echo json_encode(['success' => false, 'message' => 'Patient record not found']);
} 