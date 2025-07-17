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
        $consultPurpose = '';
        if (isset($_POST['consultPurpose'])) {
            if (is_array($_POST['consultPurpose'])) {
                $consultPurposeArray = array_map(function($purpose) use ($conn) {
                    return mysqli_real_escape_string($conn, $purpose);
                }, $_POST['consultPurpose']);
                $consultPurpose = implode(', ', $consultPurposeArray);
            } else {
                $consultPurpose = mysqli_real_escape_string($conn, $_POST['consultPurpose']);
            }
        }
        
        $diagnosis = '';
        if (isset($_POST['diagnosis']) && is_array($_POST['diagnosis'])) {
            $diagnosisArray = array_map(function($diag) use ($conn) {
                return mysqli_real_escape_string($conn, $diag);
            }, $_POST['diagnosis']);
            $diagnosis = implode(', ', $diagnosisArray);
        }

        $classification = '';
        if (isset($_POST['classification'])) {
            if (is_array($_POST['classification']) && count($_POST['classification']) > 0) {
                $classificationArray = array_map(function($class) use ($conn) {
                    return mysqli_real_escape_string($conn, $class);
                }, $_POST['classification']);
                $classification = implode(',', $classificationArray); // No space after comma
            } elseif (!is_array($_POST['classification'])) {
                $classification = mysqli_real_escape_string($conn, $_POST['classification']);
            }
        }
        

        // $medicationEntries = [];
        
        // if (isset($_POST['medication'], $_POST['medQty'], $_POST['medicationDosage']) && is_array($_POST['medication']) && is_array($_POST['medQty']) && is_array($_POST['medicationDosage'])) {
        //     for ($i = 0; $i < count($_POST['medication']); $i++) {
        //         $med = trim($_POST['medication'][$i]);
        //         $qty = trim($_POST['medQty'][$i]);
        //         $dosage = isset($_POST['medicationDosage'][$i]) ? trim($_POST['medicationDosage'][$i]) : '';

        //         // Only include non-empty values
        //         if ($med !== '' && $qty !== '') {
        //             $medSanitized = mysqli_real_escape_string($conn, $med);
        //             $qtySanitized = (int)$qty;
        //             $dosageSanitized = mysqli_real_escape_string($conn, $dosage);
        //             $entry = $qtySanitized . ' ' . $medSanitized;
        //             if ($dosageSanitized !== '') {
        //                 $entry .= ' ' . $dosageSanitized;
        //             }
        //             $medicationEntries[] = $entry;
        //         }
        //     }
        // }

        // // Final medication string like "12 ALBENDAZOLE, 10 AMIKACIN"
        // $medication = implode(', ', $medicationEntries);
        
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

        // $type1 = mysqli_real_escape_string($conn, $_POST['consultant_1_type']);
        $consultant_1 = mysqli_real_escape_string($conn, $_POST['consultant_1']);

        // $type2 = mysqli_real_escape_string($conn, $_POST['consultant_2_type']);
        $consultant_2 = mysqli_real_escape_string($conn, $_POST['consultant_2']);

        // Check if follow-up checkbox is checked
        $status = isset($_POST['follow_up']) ? 'follow up' : 'processed';

        // date_sched is only updated if the status is follow up
        $date_sched_sql = mysqli_real_escape_string($conn, $_POST['date_sched_def']);
        // if ($status == 'follow up') {
        //     $date_sched_sql = 'c.date_sched = NOW(),';
        // }

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
                            c.refer_from = '$refer_from',
                            c.refer_to = '$referTo',
                            c.remarks = '$remarks',
                            c.date_process = NOW(),
                            c.consult_start = '$consultStart',
                            c.consult_end = '$consultEnd',
                            c.educ_start = '$educStart',
                            c.educ_end = '$educEnd',
                            c.consultant_1 = '$consultant_1',
                            c.consultant_2 = '$consultant_2'
                        WHERE c.id = '$record_id'";

            if (!mysqli_query($conn, $query)) {
                throw new Exception("Error updating: " . mysqli_error($conn));
            }

            // --- NEW: Update neurology_medication table ---
            // First, delete existing medications for this consultation
            // $deleteMedQuery = "DELETE FROM neurology_medication WHERE patient_id = '$record_id'";
            // if (!mysqli_query($conn, $deleteMedQuery)) {
            //     throw new Exception("Error deleting old medications: " . mysqli_error($conn));
            // }

            // Insert each medication as a new row
            if (isset($_POST['medication'], $_POST['medQty'], $_POST['medicationDosage']) && is_array($_POST['medication']) && is_array($_POST['medQty']) && is_array($_POST['medicationDosage'])) {
                for ($i = 0; $i < count($_POST['medication']); $i++) {
                    $med = trim($_POST['medication'][$i]);
                    $qty = trim($_POST['medQty'][$i]);
                    $dosage = isset($_POST['medicationDosage'][$i]) ? trim($_POST['medicationDosage'][$i]) : '';

                    if ($med !== '' && $qty !== '') {
                        $medSanitized = mysqli_real_escape_string($conn, $med);
                        $qtySanitized = (int)$qty;
                        $dosageSanitized = mysqli_real_escape_string($conn, $dosage);
                        $insertMedQuery = "INSERT INTO neurology_medication (patient_id, medName, medDosage, medQty) VALUES ('$record_id', '$medSanitized', '$dosageSanitized', '$qtySanitized')";
                        if (!mysqli_query($conn, $insertMedQuery)) {
                            throw new Exception('Error inserting medication: ' . mysqli_error($conn));
                        }
                    }
                }
            }
            // --- END NEW ---

            // If follow up, create a new appointment for the same patient
            if ($status == 'follow up') {
                // Fetch complaint, history, remarks from the current consultation
                $fetch_query = "SELECT * FROM neurology_consultations  WHERE id = '$record_id' LIMIT 1";
                $fetch_result = mysqli_query($conn, $fetch_query);
                if (!$fetch_result) {
                    throw new Exception("Error fetching consultation data: " . mysqli_error($conn));
                }
                $consult_data = mysqli_fetch_assoc($fetch_result);

                $rID = mysqli_real_escape_string($conn, $consult_data['record_id']);
                $complaint = mysqli_real_escape_string($conn, $consult_data['complaint']);
                $history = mysqli_real_escape_string($conn, $consult_data['history']);
                // $remarks_followup = isset($consult_data['remarks']) ? mysqli_real_escape_string($conn, $consult_data['remarks']) : '';

                $date_sched = mysqli_real_escape_string($conn, $_POST['date_sched_def']);
                // $date_request = date('Y-m-d');

                // Insert new follow up appointment
                $insert_query = "INSERT INTO neurology_consultations (record_id, old_new, consultation, appointment_type, complaint, history, date_request, date_sched, status, followUp_id)
                    VALUES ('$rID', '$old_new', '$consultation', 'Follow Up', '$complaint', '$history', NOW(), '$date_sched', 'approved', '$record_id')";
                if (!mysqli_query($conn, $insert_query)) {
                    throw new Exception('Error creating follow up appointment: ' . mysqli_error($conn));
                }
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