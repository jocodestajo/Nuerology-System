<?php
// Database connection
include '../../config/dbcon.php';

// Get filters from AJAX (if any)
$patientType = $_GET['patientType'] ?? '';
$reportType = $_GET['reportType'] ?? 'patient'; // Default to 'patient'

$data = [];

if ($reportType === 'patient') {
    // Build SQL query for patient reports
    $sql = "SELECT r.hrn, r.name, c.date_sched, c.date_process, c.status
            FROM neurology_records r
            JOIN neurology_consultations c ON r.id = c.record_id
            WHERE 1";

    // Patient type filter
    if ($patientType && $patientType != "All Patients") {
        $pendingProcessedCancelled = ['Pending', 'Processed', 'Cancelled'];
        $faceToFaceTele = ['Face to Face', 'Teleconsultion'];
        if (in_array($patientType, $pendingProcessedCancelled)) {
            $sql .= " AND c.status = '" . $conn->real_escape_string($patientType) . "'";
        } elseif (in_array($patientType, $faceToFaceTele)) {
            $sql .= " AND c.consultation = '" . $conn->real_escape_string($patientType) . "'";
        }
    }

    // Date range filter
    $dateFrom = $_GET['dateFrom'] ?? '';
    $dateTo = $_GET['dateTo'] ?? '';
    if ($dateFrom && $dateTo) {
        $sql .= " AND c.date_sched BETWEEN '" . $conn->real_escape_string($dateFrom) . "' AND '" . $conn->real_escape_string($dateTo) . "'";
    } elseif ($dateFrom) {
        $sql .= " AND c.date_sched >= '" . $conn->real_escape_string($dateFrom) . "'";
    } elseif ($dateTo) {
        $sql .= " AND c.date_sched <= '" . $conn->real_escape_string($dateTo) . "'";
    }

    $sql .= " ORDER BY c.date_sched DESC";

    $result = $conn->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
} elseif ($reportType === 'medication') {
    $month = $_GET['month'] ?? '';
    $year = $_GET['year'] ?? '';

    // Corrected: patient_id in neurology_medication refers to neurology_consultations.id
    $sql = "SELECT m.medName, m.medQty, m.medDosage, r.hrn, c.date_process
            FROM neurology_medication m
            JOIN neurology_consultations c ON m.patient_id = c.id
            JOIN neurology_records r ON c.record_id = r.id
            WHERE m.medName IS NOT NULL AND m.medName != ''";

    if ($month !== '' && is_numeric($month) && $month >= 0 && $month <= 11) {
        $sql_month = (int)$month + 1; // JS month is 0-11, SQL is 1-12
        $sql .= " AND MONTH(c.date_process) = {$sql_month}";
    }
    if ($year !== '' && is_numeric($year)) {
        $sql .= " AND YEAR(c.date_process) = {$year}";
    }

    $result = $conn->query($sql);
    
    $medicationData = [];
    if ($result) {
        while($row = $result->fetch_assoc()) {
            $medName = trim($row['medName']);
            $medQty = (int)$row['medQty'];
            $medDosage = trim($row['medDosage']);
            $hrn = $row['hrn'];
            
            if (!empty($medName)) {
                $key = $medName . '||' . $medDosage;
                if (!isset($medicationData[$key])) {
                    $medicationData[$key] = [
                        'name' => $medName,
                        'dosage' => $medDosage,
                        'quantity_used' => 0,
                        'users' => []
                    ];
                }
                $medicationData[$key]['quantity_used'] += $medQty;
                $medicationData[$key]['users'][] = $hrn;
            }
        }
    }

    foreach ($medicationData as $info) {
        $data[] = [
            'name' => $info['name'],
            'dosage' => $info['dosage'],
            'quantity_used' => $info['quantity_used'],
            'total_users' => count(array_unique($info['users']))
        ];
    }
} 

// Add this block for case-load with monthly filter
elseif ($reportType === 'case-load') {
    $month = $_GET['month'] ?? '';
    $year = $_GET['year'] ?? '';
    $sql = "SELECT cl.name as classification_name, COUNT(c.id) as case_count
            FROM neurology_classifications cl 
            LEFT JOIN neurology_consultations c ON cl.id = c.classification
            WHERE c.classification IS NOT NULL AND c.classification != ''";
    if ($month !== '' && is_numeric($month) && $month >= 1 && $month <= 12) {
        $sql .= " AND MONTH(c.date_process) = " . intval($month);
    }
    if ($year !== '' && is_numeric($year)) {
        $sql .= " AND YEAR(c.date_process) = {$year}";
    }
    $sql .= " GROUP BY cl.name ORDER BY case_count DESC";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
}

echo json_encode($data);
$conn->close();
?>