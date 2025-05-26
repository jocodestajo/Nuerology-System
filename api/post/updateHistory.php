<?php
require_once '../../config/dbcon.php';

header('Content-Type: application/json');

try {
    // Get form data
    $record_id = $_POST['record_id'];
    $name = $_POST['name'];
    $hrn = $_POST['hrn'];
    $birthday = $_POST['birthday'];
    $age = $_POST['age'];
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
    $heart_rate = $_POST['heart_rate'];
    $respiratory_rate = $_POST['respiratory_rate'];
    $oxygen_saturation = $_POST['oxygen_saturation'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $notes = $_POST['notes'];
    $rx_mc = $_POST['rx_mc'];
    $classifications = $_POST['classifications'];
    $diagnosis = $_POST['diagnosis'];
    $medication = $_POST['medication'];
    $remarks = $_POST['remarks'];

    // Start transaction
    $conn->begin_transaction();

    // First update neurology_records
    $sql1 = "UPDATE neurology_records SET 
            name = ?,
            hrn = ?,
            birthday = ?,
            age = ?,
            address = ?,
            contact = ?,
            email = ?,
            viber = ?
            WHERE id = ?";

    $stmt1 = $conn->prepare($sql1);
    if (!$stmt1) {
        throw new Exception("Error preparing records update: " . $conn->error);
    }

    $stmt1->bind_param("ssssssssi", 
        $name, $hrn, $birthday, $age, $address, $contact, $email, $viber, $record_id
    );

    if (!$stmt1->execute()) {
        throw new Exception("Error updating records: " . $stmt1->error);
    }

    // Then update neurology_consultations
    $sql2 = "UPDATE neurology_consultations SET 
            appointment_type = ?,
            consultation = ?,
            date_sched = ?,
            status = ?,
            complaint = ?,
            history = ?,
            blood_pressure = ?,
            temperature = ?,
            heart_rate = ?,
            respiratory_rate = ?,
            oxygen_saturation = ?,
            height = ?,
            weight = ?,
            vs_notes = ?,
            rx_mc = ?,
            classification = ?,
            diagnosis = ?,
            medication = ?,
            remarks = ?
            WHERE record_id = ?";

    $stmt2 = $conn->prepare($sql2);
    if (!$stmt2) {
        throw new Exception("Error preparing consultations update: " . $conn->error);
    }

    $stmt2->bind_param("sssssssssssssssssssi",
        $typeofappoint, $consultation, $date_sched, $status, $complaint,
        $history, $blood_pressure, $temperature, $heart_rate, $respiratory_rate,
        $oxygen_saturation, $height, $weight, $notes, $rx_mc, $classifications,
        $diagnosis, $medication, $remarks, $record_id
    );

    if (!$stmt2->execute()) {
        throw new Exception("Error updating consultations: " . $stmt2->error);
    }

    // If we get here, both updates were successful
    $conn->commit();
    echo json_encode([
        'success' => true,
        'message' => 'Record updated successfully'
    ]);

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'sql_error' => $conn->error,
        'sql_errno' => $conn->errno
    ]);
}

// Close statements and connection
if (isset($stmt1)) $stmt1->close();
if (isset($stmt2)) $stmt2->close();
$conn->close();
?> 