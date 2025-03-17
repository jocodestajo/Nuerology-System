<?php
require '../../config/dbcon.php';


if(isset($_GET['id'])) {
    $record_id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Query to fetch the record data
    $query = "SELECT * FROM neurology_records r LEFT JOIN neurology_consultations c ON r.id = c.record_id WHERE r.id='$record_id'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0) {
        $record = mysqli_fetch_assoc($result);
        // Return the record as JSON
        echo json_encode($record);
    } else {
        // echo json_encode(['error' => 'Record not found']);
    }
} else {
    echo json_encode(['error' => 'Invalid ID']);
}
?>
