<?php
require_once '../../config/dbcon.php';

// Get form data
$record_id = $_POST['record_id'];
$name = $_POST['name'];
$birthday = $_POST['birthday'];
$address = $_POST['address'];
$contact = $_POST['contact'];
$email = $_POST['email'];
$viber = $_POST['viber'];
$typeofappoint = $_POST['typeofappoint'];
$consultation = $_POST['consultation'];
$date_sched = $_POST['date_sched'];
$status = $_POST['status'];
$complaint = $_POST['complaint'];
$history = $_POST['history'];
$blood_pressure = $_POST['blood_pressure'];
$temperature = $_POST['temperature'];
$pulse_rate = $_POST['pulse_rate'];
$respiratory_rate = $_POST['respiratory_rate'];

try {
    // Start transaction
    $conn->begin_transaction();

    // Update neurology_records table
    $query1 = "UPDATE neurology_records SET 
               name = ?, 
               birthday = ?, 
               address = ?, 
               contact = ?, 
               email = ?, 
               viber = ? 
               WHERE id = ?";
    
    $stmt1 = $conn->prepare($query1);
    $stmt1->bind_param("ssssssi", $name, $birthday, $address, $contact, $email, $viber, $record_id);
    $stmt1->execute();

    // Update neurology_consultations table
    $query2 = "UPDATE neurology_consultations SET 
               typeofappoint = ?, 
               consultation = ?, 
               date_sched = ?, 
               status = ?, 
               complaint = ?, 
               history = ?, 
               blood_pressure = ?, 
               temperature = ?, 
               pulse_rate = ?, 
               respiratory_rate = ? 
               WHERE record_id = ?";
    
    $stmt2 = $conn->prepare($query2);
    $stmt2->bind_param("ssssssssssi", 
        $typeofappoint, 
        $consultation, 
        $date_sched, 
        $status, 
        $complaint, 
        $history, 
        $blood_pressure, 
        $temperature, 
        $pulse_rate, 
        $respiratory_rate, 
        $record_id
    );
    $stmt2->execute();

    // Commit transaction
    $conn->commit();

    // Return success response
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    
    // Return error response
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

// Close statements and connection
$stmt1->close();
$stmt2->close();
$conn->close();
?> 