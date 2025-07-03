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

    // If "No" is selected for informantQA, set informant and informant_relation to "None"
    if (isset($_POST['informantQA']) && $_POST['informantQA'] === 'no') {
        $informant = 'None';
        $informant_relation = 'None';
    }

    $complaint = '';
        if (isset($_POST['complaint']) && is_array($_POST['complaint'])) {
            $complaintArray = array_map(function($diag) use ($conn) {
                return mysqli_real_escape_string($conn, $diag);
            }, $_POST['complaint']);
            $complaint = implode(', ', $complaintArray);
        }
    $history = mysqli_real_escape_string($conn, $_POST['history']);
    $referal = isset($_POST['referal']) ? mysqli_real_escape_string($conn, $_POST['referal']) : 'N/A';
    $appointment_type = mysqli_real_escape_string($conn, $_POST['typeofappoint']);

    $birthday = mysqli_real_escape_string($conn, $_POST['birthday']);
    $date = new DateTime($birthday);
    $formattedDate = $date->format('Y-m-d');
    
    if ($old_new == "" && $appointment_type == "") {
        $_SESSION['message'] = "Please select 'Old' or 'New' for the client status.";
        header("Location: ../../index.php");
        exit(0);
    }

    // Check for existing pending or approved appointments
    $check_existing_appointment_query = "SELECT nc.id FROM neurology_consultations nc JOIN neurology_records nr ON nc.record_id = nr.id WHERE (nr.hrn = ? OR (nr.name = ? AND nr.birthday = ?)) AND nc.status IN ('pending', 'approved')";
    $stmt_check_existing = mysqli_prepare($conn, $check_existing_appointment_query);
    mysqli_stmt_bind_param($stmt_check_existing, "sss", $hrn, $name, $formattedDate);
    mysqli_stmt_execute($stmt_check_existing);
    $result_check_existing = mysqli_stmt_get_result($stmt_check_existing);

    if (mysqli_num_rows($result_check_existing) > 0) {
        $_SESSION['message'] = "FAILED: Patient already has a scheduled appointment.";
        header("Location: ../../index.php"); // Redirect to a suitable page, e.g., index or patient dashboard
        exit(0);
    }

    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        $record_id = null;
        $patient_data_from_db = []; // Initialize as empty array
        $neurology_data_from_db = []; // Initialize as empty array

        // 1. Check if record exists in neurology_records
        $check_query_neurology = "SELECT * FROM neurology_records WHERE hrn = ? OR (name = ? AND birthday = ?)";
        $stmt_check_neurology = mysqli_prepare($conn, $check_query_neurology);
        mysqli_stmt_bind_param($stmt_check_neurology, "sss", $hrn, $name, $formattedDate);
        mysqli_stmt_execute($stmt_check_neurology);
        $result_check_neurology = mysqli_stmt_get_result($stmt_check_neurology);

        if ($row_neurology = mysqli_fetch_assoc($result_check_neurology)) {
            // Record found in neurology_records
            $record_id = $row_neurology['id'];
            $neurology_data_from_db = $row_neurology;

            // Also check patient_database to get additional data if available
            $check_query_patient = "SELECT * FROM patient_database WHERE hrn = ? OR (TRIM(CONCAT_WS(' ', firstname, NULLIF(middlename, ''), lastname)) = ? AND birthday = ?)";
            $stmt_check_patient = mysqli_prepare($conn, $check_query_patient);
            mysqli_stmt_bind_param($stmt_check_patient, "sss", $hrn, $name, $formattedDate);
            mysqli_stmt_execute($stmt_check_patient);
            $result_check_patient = mysqli_stmt_get_result($stmt_check_patient);
            if ($row_patient = mysqli_fetch_assoc($result_check_patient)) {
                $patient_data_from_db = $row_patient;
            }

            // Prepare data for update based on priority: $_POST > neurology_records existing > patient_database
            $merged_hrn = (!empty($neurology_data_from_db['hrn'])) ? $neurology_data_from_db['hrn'] : (isset($patient_data_from_db['hrn']) ? $patient_data_from_db['hrn'] : $hrn);
            $merged_name = (!empty($neurology_data_from_db['name'])) ? $neurology_data_from_db['name'] : (isset($patient_data_from_db['firstname']) ? trim($patient_data_from_db['firstname'] . ' ' . (isset($patient_data_from_db['middlename']) && !empty($patient_data_from_db['middlename']) ? $patient_data_from_db['middlename'] . ' ' : '') . $patient_data_from_db['lastname']) : $name);
            $merged_birthday = (!empty($neurology_data_from_db['birthday'])) ? $neurology_data_from_db['birthday'] : (isset($patient_data_from_db['birthday']) ? $patient_data_from_db['birthday'] : $formattedDate);

            // These are the columns to be conditionally updated as per user request
            $merged_age = !empty($age) ? $age : (!empty($neurology_data_from_db['age']) ? $neurology_data_from_db['age'] : (isset($patient_data_from_db['age']) ? $patient_data_from_db['age'] : null));
            $merged_contact = !empty($contact) ? $contact : (!empty($neurology_data_from_db['contact']) ? $neurology_data_from_db['contact'] : (isset($patient_data_from_db['contact']) ? $patient_data_from_db['contact'] : null));
            $merged_address = !empty($address) ? $address : (!empty($neurology_data_from_db['address']) ? $neurology_data_from_db['address'] : (isset($patient_data_from_db['address']) ? $patient_data_from_db['address'] : null));
            $merged_email = !empty($email) ? $email : (!empty($neurology_data_from_db['email']) ? $neurology_data_from_db['email'] : (isset($patient_data_from_db['email']) ? $patient_data_from_db['email'] : null));
            $merged_viber = !empty($viber) ? $viber : (!empty($neurology_data_from_db['viber']) ? $neurology_data_from_db['viber'] : (isset($patient_data_from_db['viber']) ? $patient_data_from_db['viber'] : null));
            $merged_informant = !empty($informant) ? $informant : (!empty($neurology_data_from_db['informant']) ? $neurology_data_from_db['informant'] : (isset($patient_data_from_db['informant']) ? $patient_data_from_db['informant'] : null));
            $merged_informant_relation = !empty($informant_relation) ? $informant_relation : (!empty($neurology_data_from_db['informant_relation']) ? $neurology_data_from_db['informant_relation'] : (isset($patient_data_from_db['informant_relation']) ? $patient_data_from_db['informant_relation'] : null));

            // Perform update on neurology_records
            $update_query_neurology = "UPDATE neurology_records SET hrn=?, name=?, age=?, birthday=?, contact=?, address=?, email=?, viber=?, informant=?, informant_relation=? WHERE id=?";
            $stmt_update_neurology = mysqli_prepare($conn, $update_query_neurology);
            mysqli_stmt_bind_param($stmt_update_neurology, "ssssssssssi",
                $merged_hrn, $merged_name, $merged_age, $merged_birthday, $merged_contact, $merged_address,
                $merged_email, $merged_viber, $merged_informant, $merged_informant_relation, $record_id);

            if (!mysqli_stmt_execute($stmt_update_neurology)) {
                throw new Exception("Error updating existing neurology_records: " . mysqli_error($conn));
            }

        } else {
            // Record not found in neurology_records, check patient_database
            $check_query_patient = "SELECT * FROM patient_database WHERE hrn = ? OR (TRIM(CONCAT_WS(' ', firstname, NULLIF(middlename, ''), lastname)) = ? AND birthday = ?)";
            $stmt_check_patient = mysqli_prepare($conn, $check_query_patient);
            mysqli_stmt_bind_param($stmt_check_patient, "sss", $hrn, $name, $formattedDate);
            mysqli_stmt_execute($stmt_check_patient);
            $result_check_patient = mysqli_stmt_get_result($stmt_check_patient);

            if ($row_patient = mysqli_fetch_assoc($result_check_patient)) {
                // Record found in patient_database, insert into neurology_records
                $patient_data_from_db = $row_patient;

                // Prepare data for insert based on priority: $_POST > patient_database
                $insert_hrn = (!empty($patient_data_from_db['hrn'])) ? $patient_data_from_db['hrn'] : $hrn;
                $insert_name = (isset($patient_data_from_db['firstname'])) ? trim($patient_data_from_db['firstname'] . ' ' . (isset($patient_data_from_db['middlename']) && !empty($patient_data_from_db['middlename']) ? $patient_data_from_db['middlename'] . ' ' : '') . $patient_data_from_db['lastname']) : $name;
                $insert_birthday = (!empty($patient_data_from_db['birthday'])) ? $patient_data_from_db['birthday'] : $formattedDate;

                // These are the columns to be conditionally inserted as per user request
                $insert_age = !empty($age) ? $age : (isset($patient_data_from_db['age']) ? $patient_data_from_db['age'] : null);
                $insert_contact = !empty($contact) ? $contact : (isset($patient_data_from_db['contact']) ? $patient_data_from_db['contact'] : null);
                $insert_address = !empty($address) ? $address : (isset($patient_data_from_db['address']) ? $patient_data_from_db['address'] : null);
                $insert_email = !empty($email) ? $email : (isset($patient_data_from_db['email']) ? $patient_data_from_db['email'] : null);
                $insert_viber = !empty($viber) ? $viber : (isset($patient_data_from_db['viber']) ? $patient_data_from_db['viber'] : null);
                $insert_informant = !empty($informant) ? $informant : (isset($patient_data_from_db['informant']) ? $patient_data_from_db['informant'] : null);
                $insert_informant_relation = !empty($informant_relation) ? $informant_relation : (isset($patient_data_from_db['informant_relation']) ? $patient_data_from_db['informant_relation'] : null);

                $query1 = "INSERT INTO neurology_records
                    (hrn, name, age, birthday, contact, address, email, viber, informant, informant_relation)
                    VALUES
                    (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $stmt1 = mysqli_prepare($conn, $query1);
                mysqli_stmt_bind_param($stmt1, "ssssssssss",
                    $insert_hrn, $insert_name, $insert_age, $insert_birthday, $insert_contact, $insert_address,
                    $insert_email, $insert_viber, $insert_informant, $insert_informant_relation);

                if (!mysqli_stmt_execute($stmt1)) {
                    throw new Exception("Error inserting from patient_database to neurology_records: " . mysqli_error($conn));
                }
                $record_id = mysqli_insert_id($conn);

            } else {
                // Record not in either, insert new record into neurology_records using $_POST data
                $query1 = "INSERT INTO neurology_records
                    (hrn, name, age, birthday, contact, address, email, viber, informant, informant_relation)
                    VALUES
                    (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $stmt1 = mysqli_prepare($conn, $query1);
                mysqli_stmt_bind_param($stmt1, "ssssssssss",
                    $hrn, $name, $age, $formattedDate, $contact, $address, $email, $viber, $informant, $informant_relation);

                if (!mysqli_stmt_execute($stmt1)) {
                    throw new Exception("Error inserting new record into neurology_records: " . mysqli_error($conn));
                }
                $record_id = mysqli_insert_id($conn);
            }
        }

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