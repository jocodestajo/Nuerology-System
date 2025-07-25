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
              WHERE c.id = '$records_id'";

    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        // Set session message instead of returning in response
        $_SESSION['message'] = "Appointment Approved";
        $response['success'] = true;
    } else {
        $_SESSION['message'] = "Unssuccessful Process";
        $response['message'] = "Database error occurred";
    }
}


if (isset($_POST['processed_record'])) {
    // Sanitize the input
    $records_id = mysqli_real_escape_string($conn, $_POST['processed_record']);

    // Update the record status to "Cancelled"
    $query = "UPDATE neurology_records r LEFT JOIN neurology_consultations c ON r.id = c.record_id
    SET c.status = 'processed' WHERE c.id = '$records_id'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        // Set session message instead of returning in response
        $_SESSION['message'] = "Appointment Processed";
        $response['success'] = true;
    } else {
        $_SESSION['message'] = "Unssuccessful Process";
        $response['message'] = "Database error occurred";
    }
} 

if (isset($_POST['cancel_record'])) {
    // Sanitize the input
    $records_id = mysqli_real_escape_string($conn, $_POST['cancel_record']);

    // Update the record status to "Cancelled"
    $query = "UPDATE neurology_records r LEFT JOIN neurology_consultations c ON r.id = c.record_id
    SET c.status = 'cancelled' WHERE c.id = '$records_id'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        // Set session message instead of returning in response
        $_SESSION['message'] = "Appointment Cancelled";
        $response['success'] = true;
    } else {
        $_SESSION['message'] = "Failed Cancellation";
        $response['message'] = "Database error occurred";
    }
}

if (isset($_POST['reschedule_record']) && isset($_POST['new_date'])) {
    $records_id = mysqli_real_escape_string($conn, $_POST['reschedule_record']);
    $new_date = mysqli_real_escape_string($conn, $_POST['new_date']);

    $query = "UPDATE neurology_records r LEFT JOIN neurology_consultations c ON r.id = c.record_id
    SET c.date_sched='$new_date' WHERE c.id='$records_id'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        // Set session message instead of returning in response
        $_SESSION['message'] = "Appointment Cancelled";
        $response['success'] = true;
    } else {
        $_SESSION['message'] = "Failed Rescheduling";
        $response['message'] = "Database error occurred";
    }
}

// MODAL / VIEW RECORDS
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_record'])) {
    // Sanitize and validate input data
    $record_id = mysqli_real_escape_string($conn, $_POST['record_id']);
    $hrn = mysqli_real_escape_string($conn, $_POST['view_hrn']);
    $name = mysqli_real_escape_string($conn, $_POST['view_name']);
    $age = mysqli_real_escape_string($conn, $_POST['view_age']);
    $address = mysqli_real_escape_string($conn, $_POST['view_address']);
    $birthday = mysqli_real_escape_string($conn, $_POST['view_birthday']);
    $email = mysqli_real_escape_string($conn, $_POST['view_email']);
    $contact = mysqli_real_escape_string($conn, $_POST['view_contact']);
    $viber = mysqli_real_escape_string($conn, $_POST['view_viber']);
    $informant = mysqli_real_escape_string($conn, $_POST['view_informant']);
    $informant_relation = mysqli_real_escape_string($conn, $_POST['view_informant_relation']);
    $old_new = mysqli_real_escape_string($conn, $_POST['view_old_new']);
    $date_sched = mysqli_real_escape_string($conn, $_POST['view_date_sched']);
    $history = mysqli_real_escape_string($conn, $_POST['view_history']);
    $referal = mysqli_real_escape_string($conn, $_POST['view_referal']);
    $appointment_type = mysqli_real_escape_string($conn, $_POST['view_typeofappoint']); 
    $consultation = isset($_POST['consultation']) ? mysqli_real_escape_string($conn, $_POST['consultation']) : '';

    // Handle complaints array
    $complaint = '';
    if (isset($_POST['view_complaint']) && is_array($_POST['view_complaint'])) {
        $complaintArray = array_map(function($item) use ($conn) {
            return mysqli_real_escape_string($conn, $item);
        }, $_POST['view_complaint']);
        $complaint = implode(', ', $complaintArray);
    }

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
    WHERE c.id = '$record_id'";
    
    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        // Set session message instead of returning in response
        $_SESSION['message'] = "Successfully Updated";
        $response['success'] = true;
        header("Location: ../../index.php"); // Redirect after success
        exit;
    } else {
        $_SESSION['message'] = "Unsuccessful Update";
        $response['message'] = "Database error occurred";
    }
}

// Output the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
exit;
?>



