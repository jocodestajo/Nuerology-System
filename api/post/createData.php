<?php
session_start();
require '../../config/dbcon.php';

if (isset($_POST['save_btn']))
{
    $hrn = mysqli_real_escape_string($conn, $_POST['hrn']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $viber = mysqli_real_escape_string($conn, $_POST['viber']);
    $informant = mysqli_real_escape_string($conn, $_POST['informant']);
    $informant_relation = mysqli_real_escape_string($conn, $_POST['informant_relation']);
    $old_new = mysqli_real_escape_string($conn, $_POST['old_new']);
    $consultation = mysqli_real_escape_string($conn, $_POST['consultation']);
    $date_sched = mysqli_real_escape_string($conn, $_POST['date_sched']);
    // $complaint = mysqli_real_escape_string($conn, $_POST['complaint']);
    $complaint = '';
        if (isset($_POST['complaint']) && is_array($_POST['complaint'])) {
            $complaintArray = array_map(function($diag) use ($conn) {
                return mysqli_real_escape_string($conn, $diag);
            }, $_POST['complaint']);
            $complaint = implode(', ', $complaintArray);
        }
    $history = mysqli_real_escape_string($conn, $_POST['history']);
    $referal = mysqli_real_escape_string($conn, $_POST['referal']);
    $appointment_type = mysqli_real_escape_string($conn, $_POST['typeofappoint']);

    $birthday = mysqli_real_escape_string($conn, $_POST['birthday']);
    $date = new DateTime($birthday);
    $formattedDate = $date->format('Y-m-d');
    
    if ($old_new == "" && $appointment_type == "") {
        $_SESSION['message'] = "Please select 'Old' or 'New' for the client status.";
        header("Location: ../../index.php");
        exit(0);
    }


    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        // First query - Insert into neurology_records
        $query1 = "INSERT INTO neurology_records 
            (hrn, name, age, birthday, contact, address, email, viber, informant, informant_relation) 
            VALUES 
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
        $stmt1 = mysqli_prepare($conn, $query1);
        mysqli_stmt_bind_param($stmt1, "ssssssssss", 
            $hrn, $name, $age, $formattedDate, $contact, $address, $email, $viber, $informant, $informant_relation);
        
        if (!mysqli_stmt_execute($stmt1)) {
            throw new Exception("Error updating neurology_records: " . mysqli_error($conn));
        }

        // Get the ID of the last inserted record
        $record_id = mysqli_insert_id($conn);

        // Second query - Insert into neurology_consultations with record_id
        $query2 = "INSERT INTO neurology_consultations 
            (record_id, old_new, consultation, date_request, date_sched, complaint, history, refer_from, appointment_type, status) 
            VALUES 
            (?, ?, ?, NOW(), ?, ?, ?, ?, ?, 'pending')";

        $stmt2 = mysqli_prepare($conn, $query2);
        mysqli_stmt_bind_param($stmt2, "isssssss",
            $record_id, $old_new, $consultation, $date_sched, $complaint, $history, $referal, $appointment_type);
        
        if (!mysqli_stmt_execute($stmt2)) {
            throw new Exception("Error updating neurology_consultations: " . mysqli_error($conn));
        }

        // Commit transaction if both queries succeed
        mysqli_commit($conn);
        $_SESSION['message'] = "Appointment Created Successfully";
        
        // Get the referring page
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        
        // Extract the page name from the referer URL
        $page = basename(parse_url($referer, PHP_URL_PATH));
        
        // Redirect based on the source page
        switch($page) {
            case 'referral_form.php':
                header("Location: ../../referral_form.php");
                break;
            case 'online_appointment.php':
                header("Location: ../../online_appointment.php");
                break;
            default:
                header("Location: ../../index.php");
        }
        exit(0);
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $_SESSION['message'] = "Error: " . $e->getMessage();
        
        // Get the referring page for error case too
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        $page = basename(parse_url($referer, PHP_URL_PATH));
        
        // Redirect based on the source page
        switch($page) {
            case 'referral_form.php':
                header("Location: ../../referral_form.php");
                break;
            case 'online_appointment.php':
                header("Location: ../../online_appointment.php");
                break;
            default:
                header("Location: ../../index.php");
        }
        exit(0);
    }

    // Close connection
    mysqli_close($conn);

    // prevent double saving data
    unset($_POST);
}

?>