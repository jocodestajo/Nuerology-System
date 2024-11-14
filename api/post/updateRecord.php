<?php
require '../../config/dbcon.php';

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
    $complaint = mysqli_real_escape_string($conn, $_POST['complaint']);
    $history = mysqli_real_escape_string($conn, $_POST['history']);
    $referal = mysqli_real_escape_string($conn, $_POST['referal']);
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
                complaint = '$complaint',
                history = '$history',
                referal = '$referal',
                consultation = '$consultation'
              WHERE id = '$record_id'";

    if (mysqli_query($conn, $query)) {
        echo "Record updated successfully!";
        // header("Location: index.php"); // Redirect after success
        exit;
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>
