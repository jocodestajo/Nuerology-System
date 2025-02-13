<?php
session_start();
require '../../config/dbcon.php';

if (isset($_POST['save_btn'])) 
{
    $hrn = mysqli_real_escape_string($conn, $_POST['hrn']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $birthday = mysqli_real_escape_string($conn, $_POST['birthday']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $viber = mysqli_real_escape_string($conn, $_POST['viber']);
    $informant = mysqli_real_escape_string($conn, $_POST['informant']);
    $informant_relation = mysqli_real_escape_string($conn, $_POST['informant_relation']);
    $old_new = mysqli_real_escape_string($conn, $_POST['old_new']);
    $consultation = mysqli_real_escape_string($conn, $_POST['consultation']);
    $date_request = mysqli_real_escape_string($conn, $_POST['date_request']);
    $date_sched = mysqli_real_escape_string($conn, $_POST['date_sched']);
    $complaint = mysqli_real_escape_string($conn, $_POST['complaint']);
    $history = mysqli_real_escape_string($conn, $_POST['history']);
    $referal = mysqli_real_escape_string($conn, $_POST['referal']);
    $appointment_type = mysqli_real_escape_string($conn, $_POST['typeofappoint']);

    if ($old_new == "" && $appointment_type == "") {
        $_SESSION['message'] = "Please select 'Old' or 'New' for the client status.";
        header("Location: ../../index.php");
        exit(0);
    }

    $query = 
    "INSERT INTO neurology_records (hrn, name, age, birthday, contact, address, email, viber, informant, informant_relation, old_new, consultation, date_request, date_sched, complaint, history, referal, appointment_type, status) 
    VALUES ('$hrn', '$name', '$age', '$birthday', '$contact', '$address', '$email', '$viber', '$informant', '$informant_relation', '$old_new', '$consultation', '$date_request', '$date_sched', '$complaint', '$history', '$referal', '$appointment_type', 'pending')";

    $query_run = mysqli_query($conn, $query);
    if($query_run)
    {
        $_SESSION['message'] = "Appoinmtment Created Successfully";
        header("Location: ../../index.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Appointment Unsuccessful!";
        header("Location: ../../index.php");
        exit(0);
    }
}

?>