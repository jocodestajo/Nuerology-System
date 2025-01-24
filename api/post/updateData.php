<?php
session_start();
require '../../config/dbcon.php';

// Initialize response
$response = ['success' => false, 'message' => ''];

if (isset($_POST['approve_record'])) {
    // Sanitize the input
    $records_id = mysqli_real_escape_string($conn, $_POST['approve_record']);

    // Update the record status to "Approved"
    $query = "UPDATE neurology_records SET status='approved' WHERE id='$records_id'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        // Success response
        $response['success'] = true;
        $response['message'] = "Appointment Approved";
    } else {
        // Failure response
        $response['message'] = "Appointment Not Approved";
    }
} elseif (isset($_POST['processed_record'])) {
    // Sanitize the input
    $records_id = mysqli_real_escape_string($conn, $_POST['processed_record']);

    // Update the record status to "Cancelled"
    $query = "UPDATE neurology_records SET status='processed' WHERE id='$records_id'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        // Success response
        $response['success'] = true;
        $response['message'] = "Appointment Processed";
    } else {
        // Failure response
        $response['message'] = "Appointment Not Processed";
    }
} elseif (isset($_POST['cancel_record'])) {
    // Sanitize the input
    $records_id = mysqli_real_escape_string($conn, $_POST['cancel_record']);

    // Update the record status to "Cancelled"
    $query = "UPDATE neurology_records SET status='cancelled' WHERE id='$records_id'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        // Success response
        $response['success'] = true;
        $response['message'] = "Appointment Cancelled";
    } else {
        // Failure response
        $response['message'] = "Appointment Not Cancelled";
    }
} 

// MODAL / VIEW RECORDS
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_record'])) {
    // Sanitize and validate input data
    $record_id = mysqli_real_escape_string($conn, $_POST['record_id']);
    $hrn = mysqli_real_escape_string($conn, $_POST['hrn']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $birthday = mysqli_real_escape_string($conn, $_POST['birthday']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $viber = mysqli_real_escape_string($conn, $_POST['viber']);
    $informant = mysqli_real_escape_string($conn, $_POST['informant']);
    $informant_relation = mysqli_real_escape_string($conn, $_POST['informant_relation']);
    $old_new = mysqli_real_escape_string($conn, $_POST['old_new']);
    $date_sched = mysqli_real_escape_string($conn, $_POST['date_sched']);
    $complaint = mysqli_real_escape_string($conn, $_POST['complaint']);
    $history = mysqli_real_escape_string($conn, $_POST['history']);
    $referal = mysqli_real_escape_string($conn, $_POST['referal']);
    $appointment_type = mysqli_real_escape_string($conn, $_POST['typeofappoint']); 
    $consultation = isset($_POST['consultation']) ? mysqli_real_escape_string($conn, $_POST['consultation']) : '';


    // Update the record in the database
    $query = "UPDATE neurology_records SET 
                hrn = '$hrn',
                name = '$name',
                age = '$age',
                address = '$address',
                birthday = '$birthday',
                email = '$email',
                contact = '$contact',
                viber = '$viber',
                informant = '$informant',
                informant_relation = '$informant_relation',
                old_new = '$old_new',
                consultation = '$consultation',
                date_sched = '$date_sched',
                complaint = '$complaint',
                history = '$history',
                referal = '$referal',
                appointment_type = '$appointment_type'
                WHERE id = '$record_id'";



    if (mysqli_query($conn, $query)) {
        echo "Record updated successfully!";
        header("Location: ../../index.php"); // Redirect after success
        exit;
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

// Output the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
exit;
?>



