<?php
session_start();
require '../../config/dbcon.php';

// Initialize response
$response = ['success' => false, 'message' => ''];

if (isset($_POST['approve_record'])) {
    // Sanitize the input
    $records_id = mysqli_real_escape_string($conn, $_POST['approve_record']);

    // Update the record status to "Approved"
    $query = "UPDATE neurology_consultations c 
    SET c.status = 'approved' 
    WHERE c.record_id = '$records_id'";

    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        // Success response
        $response['success'] = true;
        $response['message'] = "Appointment Approved";
    } else {
        // Failure response
        $response['message'] = "Appointment Not Approved";
    }
}

if (isset($_POST['processed_record'])) {
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
} 

if (isset($_POST['cancel_record'])) {
    // Sanitize the input
    $records_id = mysqli_real_escape_string($conn, $_POST['cancel_record']);

    // Update the record status to "Cancelled"
    $query = "UPDATE neurology_records r LEFT JOIN neurology_consultations c ON r.id = c.record_id
    SET c.status = 'cancelled' WHERE r.id = '$records_id'";
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

if (isset($_POST['reschedule_record']) && isset($_POST['new_date'])) {
    $records_id = mysqli_real_escape_string($conn, $_POST['reschedule_record']);
    $new_date = mysqli_real_escape_string($conn, $_POST['new_date']);

    $query = "UPDATE neurology_records r LEFT JOIN neurology_consultations c ON r.id = c.record_id
    SET c.date_sched='$new_date' WHERE r.id='$records_id'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        $response['success'] = true;
        $response['message'] = "Appointment Rescheduled";
    } else {
        $response['message'] = "Failed to Reschedule Appointment";
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
    $query = "UPDATE neurology_records r
    LEFT JOIN neurology_consultations c ON r.id = c.record_id
    SET 
        r.hrn = '$hrn',
        r.name = '$name',
        r.age = '$age',
        r.address = '$address',
        r.birthday = '$birthday',
        r.email = '$email',
        r.contact = '$contact',
        r.viber = '$viber',
        r.informant = '$informant',
        r.informant_relation = '$informant_relation',
        c.old_new = '$old_new',
        c.consultation = '$consultation',
        c.date_sched = '$date_sched',
        c.complaint = '$complaint',
        c.history = '$history',
        c.refer_from = '$referal',
        c.appointment_type = '$appointment_type'
    WHERE r.id = '$record_id'";
    



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



