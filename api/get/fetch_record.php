<?php
require '../../config/dbcon.php';


if(isset($_GET['id'])) {
    $record_id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Query to fetch the record data
    $query = "SELECT * FROM neurology_records r 
    LEFT JOIN neurology_consultations c ON r.id = c.record_id
    LEFT JOIN neurology_medication m ON c.id = m.patient_id
    WHERE c.id='$record_id' AND m.patient_id = '$record_id'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0) {
        $record = mysqli_fetch_assoc($result);
        // Fetch all medications for this patient/consultation
        $medications = [];
        $med_query = "SELECT medName, medDosage, medQty FROM neurology_medication WHERE patient_id = '$record_id'";
        $med_result = mysqli_query($conn, $med_query);
        if ($med_result && mysqli_num_rows($med_result) > 0) {
            while ($med_row = mysqli_fetch_assoc($med_result)) {
                $medications[] = $med_row;
            }
        }
        $record['medications'] = $medications;
        // Return the record as JSON
        echo json_encode($record);
    } else {
        echo json_encode(['error' => 'Record not found']);
    }
} else {
    echo json_encode(['error' => 'Invalid ID']);
}
?>
