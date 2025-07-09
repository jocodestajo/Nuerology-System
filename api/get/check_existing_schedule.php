<?php
require '../../config/dbcon.php';

$hrn = isset($_GET['hrn']) ? $_GET['hrn'] : '';
$name = isset($_GET['name']) ? $_GET['name'] : '';
$birthday = isset($_GET['birthday']) ? $_GET['birthday'] : '';

header('Content-Type: application/json');

if ($hrn === '' && ($name === '' || $birthday === '')) {
    echo json_encode(['success' => false, 'message' => 'Missing parameters', 'date_sched' => null]);
    exit;
}

$formattedDate = $birthday;
$query = "SELECT nc.id, nc.date_sched, nc.consultation, nr.name FROM neurology_consultations nc 
          JOIN neurology_records nr ON nc.record_id = nr.id 
          WHERE (nr.hrn = ? OR (nr.name = ? AND nr.birthday = ?)) 
          AND nc.status IN ('pending', 'approved')
          ORDER BY nc.date_sched DESC LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("sss", $hrn, $name, $formattedDate);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        'success' => false,
        'message' => 'Patient already has a schedule.',
        'date_sched' => $row['date_sched'],
        'name' => $row['name'],
        'consultation' => $row['consultation']
    ]);
} else {
    echo json_encode([
        'success' => true,
        'message' => '',
        'date_sched' => null,
        'name' => null,
        'consultation' => null
    ]);
}
$stmt->close();
$conn->close();
?> 