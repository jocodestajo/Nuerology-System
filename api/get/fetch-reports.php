<?php
// Database connection
include '../../config/dbcon.php';

// Get filters from AJAX (if any)
$timeframe = $_GET['timeframe'] ?? '';
$patientType = $_GET['patientType'] ?? '';
$reportType = $_GET['reportType'] ?? 'patient'; // Default to 'patient'

$data = [];

if ($reportType === 'patient') {
    // Build SQL query for patient reports
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

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
} elseif ($reportType === 'medication') {
    $sql = "SELECT c.medication, r.hrn
            FROM neurology_consultations c
            JOIN neurology_records r ON c.record_id = r.id
            WHERE c.medication IS NOT NULL AND c.medication != ''";

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


echo json_encode($data);
$conn->close();
?>