<?php
require '../../config/dbcon.php';
session_start();


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
        $consultPurpose = isset($_POST['consultPurpose']) ? mysqli_real_escape_string($conn, $_POST['consultPurpose']) : '';
        
        $diagnosis = '';
        if (isset($_POST['diagnosis']) && is_array($_POST['diagnosis'])) {
            $diagnosisArray = array_map(function($diag) use ($conn) {
                return mysqli_real_escape_string($conn, $diag);
            }, $_POST['diagnosis']);
            $diagnosis = implode(', ', $diagnosisArray);
        }

        $classification = mysqli_real_escape_string($conn, $_POST['classification']);
        

        $medicationEntries = [];

        if (isset($_POST['medication'], $_POST['medQty']) && is_array($_POST['medication']) && is_array($_POST['medQty'])) {
            for ($i = 0; $i < count($_POST['medication']); $i++) {
                $med = trim($_POST['medication'][$i]);
                $qty = trim($_POST['medQty'][$i]);

                // Only include non-empty values
                if ($med !== '' && $qty !== '') {
                    $medSanitized = mysqli_real_escape_string($conn, $med);
                    $qtySanitized = (int)$qty;
                    $medicationEntries[] = $qtySanitized . ' ' . $medSanitized;
                }
            }
        }

        // Final medication string like "12 ALBENDAZOLE, 10 AMIKACIN"
        $medication = implode(', ', $medicationEntries);
        
        $refer_from = mysqli_real_escape_string($conn, $_POST['refer_from']);
        $otherInstitute = mysqli_real_escape_string($conn, $_POST['otherInstitute']);
        $refer_to = mysqli_real_escape_string($conn, $_POST['refer_to']);
        if ($refer_to === "Other") {
            $referTo = $otherInstitute;
        } else {
            $referTo = $refer_to;
        }

        $remarks = mysqli_real_escape_string($conn, $_POST['remarks']);

        // Get current date for time fields
        $currentDate = date('Y-m-d');

        $consultStart = $currentDate . ' ' . mysqli_real_escape_string($conn, $_POST['consultStart']) . ':00';
        $consultEnd = $currentDate . ' ' . mysqli_real_escape_string($conn, $_POST['consultEnd']) . ':00';
        $educStart = $currentDate . ' ' . mysqli_real_escape_string($conn, $_POST['educStart']) . ':00';
        $educEnd = $currentDate . ' ' . mysqli_real_escape_string($conn, $_POST['educEnd']) . ':00';

        $type1 = mysqli_real_escape_string($conn, $_POST['consultant_1_type']);
        $consultant_1 = mysqli_real_escape_string($conn, $_POST['consultant_1']);

        $type2 = mysqli_real_escape_string($conn, $_POST['consultant_2_type']);
        $consultant_2 = mysqli_real_escape_string($conn, $_POST['consultant_2']);

        // Check if follow-up checkbox is checked
        $status = isset($_POST['follow_up']) ? 'follow up' : 'processed';

        // date_sched is only updated if the status is follow up
        $date_sched_sql = mysqli_real_escape_string($conn, $_POST['date_sched_def']);
        if ($status == 'follow up') {
            $date_sched_sql = 'c.date_sched = NOW(),';
        }

        // Automatically set appointment_type to 'Follow Up' if status is 'follow up'
        $appointment_type = ($status === 'follow up') 
            ? 'Follow Up' 
            : (isset($_POST['appointment_type']) ? mysqli_real_escape_string($conn, $_POST['appointment_type']) : '');

        // Start transaction
        mysqli_begin_transaction($conn);

        try {
            // Update neurology_records
            $query = "UPDATE neurology_records r LEFT JOIN neurology_consultations c ON r.id = c.record_id SET 
                            r.name = '$name',
                            r.age = '$age',
                            r.birthday = '$birthday',
                            r.contact = '$contact',
                            r.address = '$address',
                            r.email = '$email',
                            r.viber = '$viber',
                            r.informant = '$informant',
                            r.informant_relation = '$informant_relation',
                            c.old_new = '$old_new',
                            c.consultation = '$consultation',
                            c.rx_mc = '$consultPurpose',
                            c.diagnosis = '$diagnosis',
                            c.classification = '$classification',
                            c.medication = '$medication',             
                            c.refer_from = '$refer_from',
                            c.refer_to = '$referTo',
                            c.appointment_type = '$appointment_type',
                            c.remarks = '$remarks',
                            {$date_sched_sql}
                            c.date_process = NOW(),
                            c.consult_start = '$consultStart',
                            c.consult_end = '$consultEnd',
                            c.educ_start = '$educStart',
                            c.educ_end = '$educEnd',
                            c.c1_type = '$type1',
                            c.consultant_1 = '$consultant_1',
                            c.c2_type = '$type2',
                            c.consultant_2 = '$consultant_2',
                            c.status = '$status'
                        WHERE c.id = '$record_id'";

            if (!mysqli_query($conn, $query)) {
                throw new Exception("Error updating: " . mysqli_error($conn));
            }

            // Commit transaction if both queries succeed
            mysqli_commit($conn);
            $_SESSION['message'] = "Successful!";
            header("Location: ../../index.php"); // Redirect after success
            exit;
        } catch (Exception $e) {
            mysqli_rollback($conn); // Rollback if any query fails
            // echo "Transaction failed: " . $e->getMessage();
            $_SESSION['message'] = "Transaction failed: " . $e->getMessage();
        }

        // Close connection
        mysqli_close($conn);
    }
?>