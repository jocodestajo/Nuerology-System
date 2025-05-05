<?php
require '../../config/dbcon.php';
session_start();


// Initialize response
$response = ['success' => false, 'message' => ''];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_vital_signs'])) {
        // Sanitize and validate input data
        $record_id = mysqli_real_escape_string($conn, $_POST['record_id']);

        $bp = mysqli_real_escape_string($conn, $_POST['blood_pressure']);
        $temp = mysqli_real_escape_string($conn, $_POST['temperature']);
        $heartRate = mysqli_real_escape_string($conn, $_POST['heart_rate']);
        $respiratoryRate = mysqli_real_escape_string($conn, $_POST['respiratory_rate']);
        $oxygenSat = mysqli_real_escape_string($conn, $_POST['oxygen_saturation']);
        $height = mysqli_real_escape_string($conn, $_POST['height']);
        $weight = mysqli_real_escape_string($conn, $_POST['weight']);
        $notes = mysqli_real_escape_string($conn, $_POST['notes']);
        
        // Get current date for time fields
        $currentDate = date('Y-m-d');
        
        // Format start and end times with current date
        $vs_start = $currentDate . ' ' . mysqli_real_escape_string($conn, $_POST['vs_start']) . ':00';
        $vs_end = $currentDate . ' ' . mysqli_real_escape_string($conn, $_POST['vs_end']) . ':00';

        // Start transaction
        mysqli_begin_transaction($conn);

        try {
            // Update the record in the database
            $query = "UPDATE neurology_records r LEFT JOIN neurology_consultations c ON r.id = c.record_id  SET 
                c.blood_pressure = '$bp',
                c.temperature = '$temp',
                c.heart_rate = '$heartRate',
                c.respiratory_rate = '$respiratoryRate',
                c.oxygen_saturation = '$oxygenSat',
                c.height = '$height',
                c.weight = '$weight',
                c.vs_notes = '$notes',
                c.vs_start = '$vs_start',
                c.vs_end = '$vs_end'
            WHERE r.id = '$record_id'";

            if (!mysqli_query($conn, $query)) {
                throw new Exception("Error updating neurology_consultations: " . mysqli_error($conn));
            }

            // Commit transaction if both queries succeed
            mysqli_commit($conn);
            // echo "Successful!";
            $_SESSION['message'] = "Successful!";
            header("Location: ../../index.php"); // Redirect after success
            exit;
        } catch (Exception $e) {
            mysqli_rollback($conn); // Rollback if any query fails
            // echo "Transaction failed: " . $e->getMessage();
            $_SESSION['message'] = "Transaction failed: " . $e->getMessage();
        }

        // Close connection
        mysqli_close($conn);
    }
?>