<?php
require_once '../../config/dbcon.php';
session_start();

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
    $vs_start = $_POST['turnaround_vs_start'];
    $vs_end = $_POST['turnaround_vs_end'];
    $consult_start = $_POST['turnaround_consult_start'];
    $consult_end = $_POST['turnaround_consult_end'];
    $educ_start = $_POST['turnaround_briefing_start'];
    $educ_end = $_POST['turnaround_briefing_end'];
    $doctor = $_POST['consultant_1'];
    $nurse = $_POST['consultant_2'];

    // Start transaction
    $conn->begin_transaction();

    // Single UPDATE query using LEFT JOIN
    $sql = "UPDATE neurology_records r 
            LEFT JOIN neurology_consultations c ON r.id = c.record_id 
            SET 
                r.name = ?,
                r.hrn = ?,
                r.birthday = ?,
                r.age = ?,
                r.address = ?,
                r.contact = ?,
                r.email = ?,
                r.viber = ?,
                c.appointment_type = ?,
                c.consultation = ?,
                c.date_sched = ?,
                c.status = ?,
                c.complaint = ?,
                c.history = ?,
                c.blood_pressure = ?,
                c.temperature = ?,
                c.heart_rate = ?,
                c.respiratory_rate = ?,
                c.oxygen_saturation = ?,
                c.height = ?,
                c.weight = ?,
                c.vs_notes = ?,
                c.rx_mc = ?,
                c.classification = ?,
                c.diagnosis = ?,
                c.medication = ?,
                c.remarks = ?,
                c.vs_start = ?,
                c.vs_end = ?,
                c.consult_start = ?,
                c.consult_end = ?,
                c.educ_start = ?,
                c.educ_end = ?,
                c.doctor = ?,
                c.nurse = ?
            WHERE r.id = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Error preparing update query: " . $conn->error);
    }

    $stmt->bind_param("ssssssssssssssssssssssssssssssssssi",
        $name, $hrn, $birthday, $age, $address, $contact, $email, $viber,
        $typeofappoint, $consultation, $date_sched, $status, $complaint,
        $history, $blood_pressure, $temperature, $heart_rate, $respiratory_rate,
        $oxygen_saturation, $height, $weight, $notes, $rx_mc, $classifications,
        $diagnosis, $medication, $remarks, $vs_start, $vs_end, $consult_start,
        $consult_end, $educ_start, $educ_end, $doctor, $nurse, $record_id
    );

    if (!$stmt->execute()) {
        throw new Exception("Error updating record: " . $stmt->error);
    }

    // If we get here, the update was successful
    $conn->commit();
    $_SESSION['message'] = "Successfully Updated";
    header("Location: ../../index.php");
    exit;

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    $_SESSION['message'] = "Update unsuccessful: " . $e->getMessage();
    header("Location: ../../index.php");
    exit;
}

// Close statement and connection
if (isset($stmt)) $stmt->close();
$conn->close();
?> 