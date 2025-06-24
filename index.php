<?php
session_start();
require 'config/dbcon.php';

// Check if user is not logged in
if(!isset($_SESSION['auth'])) {
    header("Location: login.php");
    exit();
}

// Add logout functionality
if(isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// require 'includes/dateTime.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neurology</title>
    <link rel="icon" href="img/MMWGH_Logo.png" type="images/x-icon">
    <link rel="stylesheet" href="css/mainStyle.css">
    <link rel="stylesheet" href="css/general.css">
    <link rel="stylesheet" href="css/mediaQuery.css">
    <link rel="stylesheet" href="css/modals.css">
    
    <!-- Javascript -->
    <script src="js/reports.js" defer></script>
    <script src="js/mainScript.js" defer></script>
    <script src="js/approval-f2f-telecon.js" defer></script>
    <script src="js/modal.js" defer></script>
    <script src="js/calendar_booking.js" defer></script>
    <script src="js/calendar_schedule.js" defer></script>
    <script src="js/searchTab.js" defer></script>
    <script src="js/consultation.js" defer></script>
    <script src="js/functions.js" defer></script>
    <script src="js/filterHistory.js" defer></script>
    <script src="js/historyModal.js" defer></script>
</head>

<body>
    <!-- HEADER -->
    <?php include('includes/header.php'); ?>

    <div class="container-1" id="container">

        <!-- Messages -->
        <?php include('includes/messages/message.php'); ?>
        <?php include('includes/messages/cancelConfirmation.php'); ?>
        <?php include('includes/messages/approveConfirmation.php'); ?>
        <?php include('includes/addPatientModal.php'); ?>

        <!-- EDIT RECORDS MODAL-->
        <?php include('includes/editRecords.php'); ?>

        <!-- HISTORY MODAL -->
        <?php include('includes/historyModal.php'); ?>

        <!-- navigation bar / TAB -->
        <div class="navbar-2">
            <div class="tab active" onclick="showContent(0)">Inquiry</div>
            <div class="tab" onclick="showContent(1)">Pending</div>
            <div class="tab" onclick="showContent(2)"><span id="tab-face-to-face">Face to face</span><span id="tab-f2f">F2F</span></div>
            <div class="tab" onclick="showContent(3)"><span id="tab-face-to-face">Teleconsultation</span><span id="tab-f2f">Telecon</span></div>
            <div class="tab" onclick="showContent(4)">Calendar</div>
            <div class="tab" onclick="showContent(5)">Search</div>
            <div class="tab" onclick="showContent(6)">Reports</div>
            <div class="tab" onclick="showContent(7)">History</div>
        </div>
        
        <div class="container-2">
            
            <!-- TAB 1 / INQUIRY -->
            <div class="content active">
                <div class="form-content">
                    <form action="api/post/createData.php" method="post" class="box" autocomplete="off"> 

                        <!-- div.box Child element 1 -->
                        <div class="input appointment-type">
                            <label>Type of appointment: <i class="asterisk">*</i></label>
                            <select id="typeOfAppointment" name="typeofappoint" class="appointment" required>
                                <!-- <option class="select" value="" hidden disabled >--- Select Option ---</option> -->
                                <option value="Walk-In" selected>Walk-in</option>
                                <option value="SMS">SMS</option>
                                <option value="Receive Call">Call</option>
                                <option value="Online">Online</option>
                                <option value="Follow Up">Follow up</option>
                                <option value="Referral">Referral</option>
                            </select>
                        </div>
                        
                        <!-- div.box Child element 2 -->
                        <div class="input referal" id="inquiryReferral">
                            <div id="referralContent">
                                <label for="" class="">Referral Source:</label>
                                <!-- <input type="text" name="referal"> -->
                                <select name="refer_from" class="width-100 center-text">
                                    <option value="N/A" hidden disabled selected>--- Select Option ---</option>
                                    
                                    <?php
                                        $sql = "SELECT deptid, deptname FROM departments WHERE deptlocation = 'Medical Service' AND deptstat = 0";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            while($row = $result->fetch_assoc()) {
                                                echo "<option value='" . $row['deptname'] . "'>" . htmlspecialchars($row['deptname']) . "</option>";
                                            }
                                        } else {
                                            echo "<option disabled>No data found</option>";
                                        }

                                        // $conn->close();
                                    ?>
                                </select>
                            </div>
                        </div>

                        <!-- div.box Child element 3 / HRN -->
                        <div class="input">
                            <label class="hrn">HRN:</label>
                            <input type="text" id="hrn" name="hrn" class="hrn" readonly>
                        </div>


                        <!-- div.box Child element 4 / NAME -->
                        <div class="input">
                            <label>Name: <i class="asterisk">*</i></label>
                            <input type="text" id="name" name="name" class="name" placeholder="Search or type the name here..." autocomplete="off" required>
                            
                            <div id="searchResult-modal">
                                <div id="result"></div>
                            </div>
                        </div>
                        
                        <!-- div.box Child element 5 / BDATE -->
                        <div class="input">
                            <label for="" class="b_day">Birthdate: <i class="asterisk">*</i></label>
                            <input type="date" name="birthday" id="birthday" class="birthdayInput" data-age-output="age1" require>
                        </div>
                        
                        <!-- div.box Child element 6 / ADDRESS -->
                        <div class="input">
                            <label for="" class="address">Address:</label>
                            <input type="text" id="address" name="address" class="address">
                        </div>
                        
                        <!-- div.box Child element 7 / AGE -->
                        <div class="input">
                            <label for="" class="age">Age:</label>
                            <input type="text" id="age1" name="age" class="age">
                        </div>

                        <!-- div.box Child element 8 / EMAIL -->
                        <div class="input">
                            <label for="" class="email">E-mail:</label>
                            <input type="email" name="email" class="email">
                        </div>
                        
                        <!-- div.box Child element 9 / CONTACT-->
                        <div class="input">
                            <label for="" class="contact">Contact No: <i class="asterisk">*</i></label>
                            <input type="text" id="contact" name="contact" class="contact" required>
                        </div>

                        <!-- div.box Child element 10 -->
                        <div class="input">
                            <label for="" class="viber">Viber Account:</label>
                            <input type="text" name="viber" class="viber">
                        </div>

                        <!-- 10 -->
                        <div class="question-block margin-t-20">
                            <label>May iba pa bang kasama ang pasyente?</label>
                            <p><i>*Is there anyone else accompanying the patient?</i></p>
                            <fieldset style="border: none; padding: 0; margin: 0;">
                                <div class="radio-group">
                                    <label for="informantQA-true-1">
                                        <input type="radio" name="informantQA" id="informantQA-true-1" value="yes">
                                        Yes
                                    </label>

                                    <label for="informantQA-false-1">
                                        <input type="radio" name="informantQA" id="informantQA-false-1" value="no">
                                        No
                                    </label>
                                </div>
                            </fieldset>
                        </div>

                        <div class="informant-details">
                            <div class="display-informant" style="display: none;">
                                <label for="">Name of Informant:</label>
                                <input type="text" name="informant" class="width-100">
                            </div>

                            <div class="display-informant" style="display: none;">
                                <label for="">Relationship to patient:</label>
                                <input type="text" name="informant_relation" class="width-100">
                            </div>
                        </div>
                        
                        <!-- div.box Child element 13 -->
                        <div class="input borderbox">
                            <div class="col-2">
                                <div>
                                    <div class="client-type">
                                        <label>Type of Client: <i class="asterisk">*</i></label>
                                        <select name="old_new" class="old_new" id="clientSelect" required>
                                            <option value="" class="selectDefault" hidden disabled selected>--- Select Option ---</option>
                                            <option value="New">New</option>
                                            <option value="Old">Old</option>
                                        </select>
                                    </div>

                                    <div>
                                        <h3>Type of Consultation:</h3>
                                        <div class="radio-div radio-group">
                             
                                                <label for="f2f">
                                                    <input type="radio" name="consultation" id="f2f" value="Face to face" class="custom-radio">
                                                    Face to face
                                                </label>

                                                <label for="teleconsult">
                                                    <input type="radio" name="consultation" id="teleconsult" value="Teleconsultation" class="custom-radio">
                                                    Teleconsultation
                                                </label>

                                        </div>
                                    </div>
                                </div>
                                
                                <div class="calendar">
                                    <div class="calendar-date">
                                        <label for="dateSched1">Date Schedule:</label>
                                        <input type="date" id="dateSched1" class="date" name="date_sched" readonly>
                                    </div>

                                    <div class="calendar-btn">
                                        <span class="datePicker btn btn-blue" data-sched-output="dateSched1">Calendar</span>
                                    </div>
                                    
                                    <?php include('includes/calendarTable_modal.php'); ?>
                                </div>
                            </div>
                        </div>

                        <!-- div.box Child element 14 -->
                        <div id="complaint">
                            <h3>Complaint</h3>
                        </div>

                        <!-- div.box Child element 15 -->
                        <div class="input">
                            <div><label for="">Ano ang ipapakunsulta?</label></div>

                            <!-- Trigger Button -->
                            <button type="button" data-modal-target="complaintModal1" class="btn border width-100">--- Select Option ---</button>

                            <!-- Modal Container -->
                            <div id="complaintModal1" class="complaintShow">
                                <div class="modal-content" style="width: 400px; margin: 10% auto; position: relative;">
                                    <div class="checkbox-complaint checkbox-group">
                                        <?php
                                            $sql1 = "SELECT id, name FROM neurology_classifications WHERE archived = 0";
                                            $result1 = $conn->query($sql1);
                                            
                                            if ($result1->num_rows > 0) {
                                                while($row = $result1->fetch_assoc()) {
                                                    echo "<label><input type='checkbox' name='complaint[]' value='" . htmlspecialchars($row['name']) . "'> " . htmlspecialchars($row['name']) . "</label>";
                                                }
                                            } else {
                                                echo "<label><input type='checkbox' disabled> No classifications found</label>";
                                            }
                                        ?>
                                        <label><input type="checkbox" name="complaint[]" value="Others"> Others</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- div.box Child element 16 -->
                        <div class="input">
                            <label for="" class="">Magbigay ng maikling paglalarawan tungkol sa sakit: <i class="asterisk">*</i></label>
                            <textarea rows="5" name="history" required></textarea>
                        </div>

                        <!-- div.box Child element 18 -->
                        <div class="div-btn-submit">
                            <a href="index.php">
                                <button type="button" name="clear_data_btn" class="btn btn-clearData">Clear Data</button>
                            </a>
                            <button type="submit" name="save_btn" class="btn btn-green">Save</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TAB 2 / For Approval -->
            <div class="content ">
                <div class="flex">

                    <Label>Date Filter:</Label>

                    <select name="" class="sortData-Pending selectDate-border" id="monthFilter1">
                        <option value="" hidden disabled selected>Select Month</option>
                    </select>

                    <select name="" class="sortData-Day selectDate-border" id="dayFilter1">
                        <option value="" hidden disabled selected>Select Day</option>
                    </select>
                    
                    <select name="" class="sortData-Year selectDate-border" id="yearFilter1">
                        <option value="" hidden disabled selected>Select Year</option>
                    </select>

                </div>
                
                <table class="table-pending" id="table1">
                    <thead>
                        <tr>
                            <th class="th-check border-left"><input type="checkbox" class="checkbox checkbox-header custom-checkbox"></th>
                            <th class="th-hrn">HRN</th>
                            <th class="th-name">Name</th>
                            <th class="th-contact">Contact</th>
                            <th class="th-consultation">Consultation</th>
                            <th class="th-schedule">Schedule</th>
                            <th class="th-complaint">Complaint</th>
                            <th class="th-action border-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $query = "
                                SELECT 
                                    r.id, r.hrn, r.name, r.contact,
                                    c.id AS consult_id, c.consultation, c.date_sched, c.complaint, c.status,
                                    (SELECT COUNT(*) FROM neurology_consultations nc WHERE nc.record_id = r.id) AS consultation_count
                                FROM neurology_records r
                                LEFT JOIN neurology_consultations c ON r.id = c.record_id
                                WHERE c.status = 'pending' ORDER BY c.date_sched
                            ";

                            $query_run = mysqli_query($conn, $query);

                            if(mysqli_num_rows($query_run) > 0)
                            {
                                foreach($query_run as $records)
                                    {
                                        $is_new_client = ($records['consultation_count'] == 1);
                                        $row_class = $is_new_client ? 'new-client' : '';
                                    ?>
                                        <tr id="patient_<?=$records['consult_id'];?>" class="<?= $row_class ?>">
                                            <td class="th-check border-left">
                                                <input type="checkbox" class="checkbox custom-checkbox">
                                            </td>
                                            <td class="th-hrn"><?= $records['hrn']; ?></td>
                                            <td class="th-name"><?= $records['name']; ?></td>
                                            <td class="th-contact"><?= $records['contact']; ?></td>
                                            <td class="th-consultation"><?= $records['consultation']; ?></td>
                                            <td class="th-schedule"><?= $records['date_sched']; ?></td>
                                            <td class="th-complaint"><?= $records['complaint']; ?></td>
                                            <td class="th-action action border-right">
                                                <img src="img/check-circle.png" class="action-img trigger-approve-modal margin-right" alt="image here" data-id="<?=$records['consult_id'];?>" data-modal-target="approveConfirmationModal">
                                                <img src="img/edit.png" class="action-img view-button margin-right" alt="image here" data-record-id="<?=$records['consult_id'];?>">
                                                <img src="img/cancel.png" class="action-img update-cancelled" alt="image here" data-id="<?=$records['consult_id'];?>">
                                            </td>
                                        </tr>
                                    <?php
                                }

                            }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- TAB 3 / FACE TO FACE CHECK-UP -->
            <div class="content">
                <div class="flex">
                    <Label>Date Filter:</Label>

                    <select name="" class="sortData-Pending selectDate-border" id="monthFilter2">
                        <option value="" hidden disabled selected>Select Month</option>
                    </select>

                    <select name="" class="sortData-Day selectDate-border" id="dayFilter2">
                        <option value="" hidden disabled selected>Select Day</option>
                    </select>

                    <select name="" class="sortData-Year selectDate-border" id="yearFilter2">
                        <option value="" hidden disabled selected>Select Year</option>
                    </select>

                    <button class="btn btn-green add-patient-btn" data-type="f2f">Add Patient</button>
                </div>
                
                <table class="table-face-to-face" id="table2">
                    <thead>
                        <tr>
                            <th class="th-check border-left"><input type="checkbox" class="checkbox checkbox-header custom-checkbox"></th>
                            <th class="th-hrn">HRN</th>
                            <th class="th-name">Name</th>
                            <th class="th-contact">Contact</th>
                            <th class="th-schedule">Schedule</th>
                            <th class="th-complaint">Complaint</th>
                            <th class="th-action border-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // calling data in db table for Face to Face Check-up
                            $query = "SELECT r.id, r.hrn, r.name, r.contact, c.id AS consult_id, c.date_sched, c.complaint
                            FROM neurology_records r
                            LEFT JOIN neurology_consultations c ON r.id = c.record_id
                            WHERE ((c.status = 'approved' and c.consultation = 'Face to face') OR c.status = 'follow up' AND c.consultation = 'Face to face') ORDER BY c.date_sched";

                            // Execute query
                            $query_run = mysqli_query($conn, $query);

                            if(mysqli_num_rows($query_run) > 0) {
                                foreach($query_run as $records) {
                                    ?>
                                    <tr id="patient_<?=$records['consult_id'];?>">
                                        <input type="hidden" name="patient_id" value="<?=$records['consult_id'];?>">
                                        <td class="th-check border-left"><input type="checkbox" class="checkbox custom-checkbox"></td>
                                        <td class="th-hrn"><?= $records['hrn']; ?></td>
                                        <td class="th-name"><?= $records['name']; ?></td>
                                        <td class="th-contact"><?= $records['contact']; ?></td>
                                        <td class="th-schedule"><?= $records['date_sched']; ?></td>
                                        <td class="th-complaint"><?= $records['complaint']; ?></td>
                                        <td class="th-action action border-right">
                                            <img src="img/check-circle.png" class="action-img trigger-process-modal margin-right" alt="processed" data-record-id="<?=$records['consult_id'];?>" data-modal-target="processConfirmationModal">
                                            <img src="img/vitalSigns.png" class="action-img margin-right" alt="VitalSigns" data-record-id="<?=$records['consult_id'];?>">
                                            <img src="img/chat.png" class=" action-img margin-right" alt="Consultation" data-record-id="<?=$records['consult_id'];?>">
                                            <img src="img/cancel.png" class="action-img update-cancelled" alt="Cancel" data-id="<?=$records['consult_id'];?>">
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } 
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- TAB 4 / TELECONSULTATION -->
            <div class="content">
                <div class="flex">
                    <Label>Date Filter:</Label>
                    
                    <select name="" class="sortData-Pending selectDate-border" id="monthFilter3">
                        <option value="" hidden disabled selected>Select Month</option>
                    </select>
    
                    <select name="" class="sortData-Day selectDate-border" id="dayFilter3">
                        <option value="" hidden disabled selected>Select Day</option>
                    </select>

                    <select name="" class="sortData-Year selectDate-border" id="yearFilter3">
                        <option value="" hidden disabled selected>Select Year</option>
                    </select>
               
                    <button class="btn btn-green add-patient-btn" data-type="telecon">Add Patient</button>
                </div>
                    
                <table class="table-teleconsultation" id="table3">
                    <thead>
                        <tr>
                            <th class="th-check border-left"><input type="checkbox" class="checkbox checkbox-header custom-checkbox"></th>
                            <th class="th-hrn">HRN</th>
                            <th class="th-name">Name</th>
                            <th class="th-contact">Contact</th>
                            <th class="th-schedule">Schedule</th>
                            <th class="th-complaint">Complaint</th>
                            <th class="th-action border-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // calling data in db table for Teleconsultation
                            $query = "SELECT r.id, r.hrn, r.name, r.contact, c.id AS consult_id, c.date_sched, c.complaint
                            FROM neurology_records r
                            LEFT JOIN neurology_consultations c ON r.id = c.record_id
                            WHERE ((c.status = 'approved' and c.consultation = 'Teleconsultation') OR c.status = 'follow up' AND c.consultation = 'Teleconsultation') ORDER BY c.date_sched";

                            // Execute query
                            $query_run = mysqli_query($conn, $query);

                            if(mysqli_num_rows($query_run) > 0) {
                                foreach($query_run as $records) {
                                    ?>
                                    <tr id="patient_<?=$records['consult_id'];?>">
                                        <input type="hidden" name="patient_id" value="<?=$records['consult_id'];?>">
                                        <td class="th-check border-left"><input type="checkbox" class="checkbox custom-checkbox"></td>
                                        <td class="th-hrn"><?= $records['hrn']; ?></td>
                                        <td class="th-name"><?= $records['name']; ?></td>
                                        <td class="th-contact"><?= $records['contact']; ?></td>
                                        <td class="th-schedule"><?= $records['date_sched']; ?></td>
                                        <td class="th-complaint"><?= $records['complaint']; ?></td>
                                        <td class="th-action action border-right">
                                            <img src="img/check-circle.png" class="action-img trigger-process-modal margin-right" alt="processed" data-record-id="<?=$records['consult_id'];?>" data-modal-target="processConfirmationModal">
                                            <img src="img/vitalSigns.png" class="action-img margin-right" alt="VitalSigns" data-record-id="<?=$records['consult_id'];?>">
                                            <img src="img/chat.png" class=" action-img margin-right" alt="Consultation" data-record-id="<?=$records['consult_id'];?>">
                                            <img src="img/cancel.png" class="action-img update-cancelled" alt="Cancel" data-id="<?=$records['consult_id'];?>">
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } 
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- CHECKBOX TRIGGER FOR TAB 2,3,4 -->
            <div class="btn-div-checkbox">
                <button class="btn btn-blue update-reschedule">Reschedule</button>
                <button class="btn btn-red update-cancel">Cancel Appointment</button>
            </div>

            <!-- Reschedule modal -->
            <div id="rescheduleModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <span class="close-btn">&times;</span>
                        <h2>Reschedule Appointment</h2>
                    </div>
                    <div class="modal-body">
                        <div class="calendar">
                            
                            <div id="rescheduleCalendarContainer">
                                <div class="calendar-controls">
                                    <button type="button" class="btn-prev">Previous</button>
                                    <h2 id="rescheduleMonthTitle" class="calendarMonth"></h2>
                                    <button type="button" class="btn-next">Next</button>
                                </div>
                                <table id="rescheduleCalendarTable">
                                    <!-- Calendar will be populated here -->
                                </table>
                                
                            </div>
                            <div class="rescheduleContent">
                                <div class="calendar-date">
                                    <label for="reschedule-date">New Schedule:</label>
                                    <input type="date" id="reschedule-date" class="date" name="reschedule_date" readonly>
                                </div>
                            </div>
                        </div>
                        
                        <div class="modal-footer">
                            <button class="btn btn-green" id="confirmReschedule">Confirm</button>
                            <button class="btn btn-red" id="cancelReschedule">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Process Confirmation Modal -->
            <div id="processConfirmationModal" class="modal">
                <div class="modal-content modal-approveContent">
                    <div class="modal-header">
                        <span class="close-btn">&times;</span>
                        <h2>Confirm Processing</h2>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to mark this record as processed?</p>
                        <input type="hidden" id="processRecordId">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-green" id="confirmProcessBtn">Confirm</button>
                        <button class="btn btn-red" id="cancelProcessBtn">Cancel</button>
                    </div>
                </div>
            </div>

            <!-- TAB 5 / CALENDAR -->
            <div class="content">
                <span id="scheduleSettings" class="btn btn-blue setSched">Schedule Limit</span>
                <div id="setSchedule">
                    <div class="cal_header">
                        <div class="space-between">
                            <h2>Limit Weekly</h2>
                            <span class="close-btn">&times;</span>
                        </div>
                        <div class="weekday-checkboxes">
                            <div class="weekday-title" onclick="scheduleToggleDropdown()">Weekly Schedule ▼</div>
                            <div class="checkbox-group" id="weekdayDropdown">
                                <div class="checkbox-item">
                                    <label for="monday">
                                        <input type="checkbox" id="monday" name="days[]" value="Monday">
                                    Monday</label>
                                </div>
                                <div class="checkbox-item">
                                    <label for="tuesday">
                                        <input type="checkbox" id="tuesday" name="days[]" value="Tuesday">
                                    Tuesday</label>
                                </div>
                                <div class="checkbox-item">
                                    <label for="wednesday">
                                        <input type="checkbox" id="wednesday" name="days[]" value="Wednesday">
                                    Wednesday</label>
                                </div>
                                <div class="checkbox-item">
                                    <label for="thursday">
                                        <input type="checkbox" id="thursday" name="days[]" value="Thursday">
                                    Thursday</label>
                                </div>
                                <div class="checkbox-item">
                                    <label for="friday">
                                        <input type="checkbox" id="friday" name="days[]" value="Friday">
                                    Friday</label>
                                </div>
                                <div class="checkbox-item">
                                    <label for="saturday">
                                        <input type="checkbox" id="saturday" name="days[]" value="Saturday">
                                    Saturday</label>
                                </div>
                                <div class="checkbox-item">
                                    <label for="sunday">
                                        <input type="checkbox" id="sunday" name="days[]" value="Sunday">
                                    Sunday</label>
                                </div>
                            </div>
                        </div>
    
                        <div class="limit">
                            <h2>Limit per day</h2>
                            <form action="api/post/saveLimits.php" method="POST" class="limit_form">
                                <?php
                                    $query = "SELECT DISTINCT dailyLimit_new, dailyLimit_referral, follow_up FROM neurology_weekdaysettings";
                                    $query_run = mysqli_query($conn, $query);

                                    if(mysqli_num_rows($query_run) > 0){
                                        foreach($query_run as $records)
                                        {
                                        }
                                    }
                                ?>
                                <div class="space-between">
                                    <label for="onlinef2f">F2F: New</label>
                                    <input type="number" name="online_F2F_limit" value="<?= $records['dailyLimit_new']; ?>" class="center-text">
                                </div>

                                <div class="space-between">
                                    <label for="">Follow Up</label>
                                    <input type="number" name="follow_up" value="<?= $records['follow_up']; ?>" class="center-text">
                                </div>
    
                                <div class="space-between">
                                    <label for="referrals_limit">Referrals:</label>
                                    <input type="number" name="referral_limit" value="<?= $records['dailyLimit_referral']; ?>" class="center-text">
                                </div>
    
                                <div class="center-flex">
                                    <button type="submit" name="submit_limit" class="btn btn-green">Save</button>
                                </div>
                            </form>
                        </div>  
                    </div>
                </div>

                <div class="flex calendar-button">
                    <span class="calendarMonth-border" onclick="scheduleMonthChange('previous')">Previous Month</span>
                    <span class="calendarMonth-border" onclick="scheduleMonthChange('current')">Current Month</span>
                    <span class="calendarMonth-border" onclick="scheduleMonthChange('next')">Next Month</span>
                </div>
                
                <h2 id="MonthTitle" class="calendarMonth flex"></h2>
                
                <table id="calendarTable_schedule">
                    <!-- calendar here -->
                </table>
            </div>

            <!-- TAB 6 / SEARCH -->
            <div class="content">
                <div class="search_scrolltab">
                    <div class="searchDiv margin-b-20">
                        <input type="text" id="searchQuery" placeholder="Search name, hrn, consultation or status type...." />
                        <button onclick="searchData()">Search</button>
                    </div>
                </div>

                <!-- Placeholder for tables -->
                <div id="pendingTable"></div>
                <div id="faceToFaceTable"></div>
                <div id="teleconsultationTable"></div>
                <div id="processedTable"></div>
                <div id="cancelledTable"></div>
            </div>            
            
            <!-- TAB 7 / REPORTS -->
            <div class="content">
                <?php include('includes/reports.php'); ?>
            </div>

            <!-- TAB 8 / HISTORY -->
            <div class="content">
                <div class="filters">
                    <Label class="filter-group">Filter by:
                        <div>
                            <select name="" class="btn width-100" id="filterDataReport">
                                <option value="All" selected>All</option>
                                <option value="processed">Processed</option>
                                <option value="follow up">Follow up</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </Label>

                    <Label class="filter-group">Sort by:
                        <div>
                            <select name="" class="btn width-100" id="sortDataReport">
                                <option value="All" selected>All</option>
                                <option value="Face to Face">Face to Face</option>
                                <option value="Teleconsultation">Teleconsultation</option>
                            </select>
                        </div>
                    </Label>
    
                    <label class="filter-group"> Date From:
                        <div>
                            <input type="date" id="dateFrom" class="width-100">
                        </div>
                    </label>

                    <label class="filter-group"> Date To:
                        <div>
                            <input type="date" id="dateTo" class="width-100">
                        </div>
                    </label>

                    <label class="filter-group" style="align-self: flex-end;">
                        <button class="btn btn-blue" id="applyFilterBtn">Apply Filter</button>
                    </label>
                </div>
                    
                <table class="table-history" id="table4">
                    <thead>
                        <tr>
                            <th class="th-name">Name</th>
                            <th class="th-consultation">Consultation</th>
                            <th class="th-schedule">Schedule</th>
                            <th class="th-complaint">Complaint</th>
                            <th class="th-status">Status</th>
                            <th class="th-action border-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // Query for history records
                            $query = "SELECT r.id, r.hrn, r.name, r.contact, 
                                     c.consultation, c.date_sched, c.complaint, c.status, c.date_process
                                     FROM neurology_records r
                                     INNER JOIN neurology_consultations c ON r.id = c.record_id
                                     WHERE c.status IN ('processed', 'follow up', 'cancelled')
                                     ORDER BY c.date_process DESC";

                            $query_run = mysqli_query($conn, $query);

                            if(mysqli_num_rows($query_run) > 0) {
                                foreach($query_run as $records) {
                                    ?>
                                    <tr id="patient_<?=$records['id'];?>">
                                        <td class="th-name"><?= $records['name']; ?></td>
                                        <td class="th-consultation"><?= $records['consultation']; ?></td>
                                        <td class="th-schedule"><?= $records['date_sched']; ?></td>
                                        <td class="th-complaint"><?= $records['complaint']; ?></td>
                                        <td class="th-status"><?= $records['status']; ?></td>
                                        <td class="th-action action border-right">
                                            <img src="img/edit.png" class="action-img view-history  margin-right" alt="View" data-record-id="<?=$records['id'];?>">
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <footer id="footer">&copy; 2025 - MMWGH (IMISU)</footer>
    </div>

    <script>
        // Function to display the footer
        function checkScroll() {
            // Get the scroll position, total height, and the viewport height
            let scrollTop = window.scrollY || document.documentElement.scrollTop;
            let docHeight = document.documentElement.scrollHeight;
            let winHeight = window.innerHeight;

            // If the user has scrolled to the bottom, show the footer
            if (scrollTop + winHeight >= docHeight) {
                document.getElementById('footer').style.display = 'block';
            } else {
                document.getElementById('footer').style.display = 'none';
            }
        }

        // Listen for scroll events
        window.addEventListener('scroll', checkScroll);

        // Initial check in case the user is already at the bottom when the page loads
        checkScroll();
    </script>

</body>
</html>