<?php
require '../../config/dbcon.php';

// Initialize response
$response = ['success' => false, 'message' => ''];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_record'])) {
        // Sanitize and validate input data
        $record_id = mysqli_real_escape_string($conn, $_POST['record_id']);

        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $age = mysqli_real_escape_string($conn, $_POST['age']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $birthday = mysqli_real_escape_string($conn, $_POST['birthday']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $contact = mysqli_real_escape_string($conn, $_POST['contact']);
        $viber = mysqli_real_escape_string($conn, $_POST['viber']);
        $informant = mysqli_real_escape_string($conn, $_POST['informant']);
        $informant_relation = mysqli_real_escape_string($conn, $_POST['informant_relation']);
        $old_new = isset($_POST['old_new']) ? mysqli_real_escape_string($conn, $_POST['old_new']) : '';
        $consultation = isset($_POST['consultation']) ? mysqli_real_escape_string($conn, $_POST['consultation']) : '';
        $date_sched = mysqli_real_escape_string($conn, $_POST['date_sched']);

        $refer_from = mysqli_real_escape_string($conn, $_POST['refer_from']);
        $refer_to = mysqli_real_escape_string($conn, $_POST['refer_to']);
        
        $diagnosis = '';
        if (isset($_POST['diagnosis']) && is_array($_POST['diagnosis'])) {
            $diagnosisArray = array_map(function($diag) use ($conn) {
                return mysqli_real_escape_string($conn, $diag);
            }, $_POST['diagnosis']);
            $diagnosis = implode(', ', $diagnosisArray);
        }
        $classification = mysqli_real_escape_string($conn, $_POST['classification']);
        $medication = isset($_POST['medication']) ? implode(', ', array_map(function($item) use ($conn) {
            return mysqli_real_escape_string($conn, $item);
        }, $_POST['medication'])) : '';
        

        $doctorName = mysqli_real_escape_string($conn, $_POST['doctorName']);
        $nurseName = mysqli_real_escape_string($conn, $_POST['nurseName']);
        $consultPurpose = isset($_POST['consultPurpose']) ? mysqli_real_escape_string($conn, $_POST['consultPurpose']) : '';

        // Check if follow-up checkbox is checked
        $status = isset($_POST['follow_up']) ? 'follow up' : 'processed';

        // Automatically set appointment_type to 'Follow Up' if status is 'follow up'
        $appointment_type = ($status === 'follow up') ? 'Follow Up' : mysqli_real_escape_string($conn, $_POST['appointment_type']);

        // Start transaction
        mysqli_begin_transaction($conn);

        try {
            // Update neurology_records
            $query1 = "UPDATE neurology_records SET 
                            name = '$name',
                            age = '$age',
                            birthday = '$birthday',
                            contact = '$contact',
                            address = '$address',
                            email = '$email',
                            viber = '$viber',
                            informant = '$informant',
                            informant_relation = '$informant_relation'
                        WHERE id = '$record_id'";

            if (!mysqli_query($conn, $query1)) {
                throw new Exception("Error updating neurology_records: " . mysqli_error($conn));
            }

            // Update neurology_consultations
            $query2 = "UPDATE neurology_consultations SET 
                            old_new = '$old_new',
                            consultation = '$consultation',
                            rx_mc = '$consultPurpose',
                            refer_from = '$refer_from',
                            refer_to = '$refer_to',
                            appointment_type = '$appointment_type',
                            diagnosis = '$diagnosis',
                            classification = '$classification',
                            medication = '$medication',
                            doctor = '$docName',
                            nurse = '$nurseName',
                            date_request = NOW(),
                            date_sched = '$date_sched',
                            status = '$status'
                        WHERE record_id = '$record_id'";

            if (!mysqli_query($conn, $query2)) {
                throw new Exception("Error updating neurology_consultations: " . mysqli_error($conn));
            }

            // Commit transaction if both queries succeed
            mysqli_commit($conn);
            echo "Records updated successfully!";
            header("Location: ../../index.php"); // Redirect after success
            exit;
        } catch (Exception $e) {
            mysqli_rollback($conn); // Rollback if any query fails
            echo "Transaction failed: " . $e->getMessage();
        }

        // Close connection
        mysqli_close($conn);
    }
?>