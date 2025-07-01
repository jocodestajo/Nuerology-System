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

    $sql = "SELECT c.medication, r.hrn, c.date_process
            FROM neurology_consultations c
            JOIN neurology_records r ON c.record_id = r.id
            WHERE c.medication IS NOT NULL AND c.medication != ''";

    if ($month !== '' && is_numeric($month) && $month >= 0 && $month <= 11) {
        $sql_month = (int)$month + 1; // JS month is 0-11, SQL is 1-12
        $sql .= " AND MONTH(c.date_process) = {$sql_month}";
    }

    $result = $conn->query($sql);
    
    $medicationData = [];
    if ($result) {
        while($row = $result->fetch_assoc()) {
            $hrn = $row['hrn'];
            // e.g., "12 ALBENDAZOLE, 10 AMIKACIN"
            $medications = explode(',', $row['medication']);
            foreach ($medications as $med) {
                $med = trim($med);
                if (empty($med)) continue;
                
                // e.g., "12 ALBENDAZOLE"
                $parts = explode(' ', $med, 2);
                if (count($parts) === 2 && is_numeric($parts[0])) {
                    $qty = (int)$parts[0];
                    $name = trim($parts[1]);
                    
                    if (!isset($medicationData[$name])) {
                        $medicationData[$name] = [
                            'quantity_used' => 0,
                            'users' => []
                        ];
                    }
                    $medicationData[$name]['quantity_used'] += $qty;
                    $medicationData[$name]['users'][] = $hrn;
                }
            }
        }
    }

    foreach ($medicationData as $name => $info) {
        $data[] = [
            'name' => $name, 
            'quantity_used' => $info['quantity_used'],
            'total_users' => count(array_unique($info['users']))
        ];
    }
} 

// Add this block for case-load with monthly filter
elseif ($reportType === 'case-load') {
    $month = $_GET['month'] ?? '';
    $sql = "SELECT cl.name as classification_name, COUNT(c.id) as case_count
            FROM neurology_classifications cl 
            LEFT JOIN neurology_consultations c ON cl.id = c.classification
            WHERE c.classification IS NOT NULL AND c.classification != ''";
    if ($month !== '' && is_numeric($month) && $month >= 1 && $month <= 12) {
        $sql .= " AND MONTH(c.date_process) = " . intval($month);
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