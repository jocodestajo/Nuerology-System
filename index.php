<?php
session_start();
require 'config/dbcon.php';
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
</head>
    <!-- <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
        }
        
        .container {
            width: 95%;
            max-width: 1200px;
            margin: 20px auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .tabs-Rep {
            display: flex;
            background: var(--grey-color);
        }
        
        .tabRep {
            padding: 15px 25px;
            color: black;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
            flex: 1;
        }
        
        .tabRep:hover {
            background: #34495e;
        }
        
        .tabRep.active {
            background: #3498db;
            color: 
            font-weight: bold;
        }
        
        .tab-content {
            display: none;
            padding: 20px;
        }
        
        .tab-content.active {
            display: block;
        }
        
        h2 {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            margin-top: 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #3498db;
            color: white;
        }
        
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        
        tr:hover {
            background-color: #e6f7ff;
        }
        
        .chart-container {
            width: 100%;
            height: 400px;
            margin: 20px 0;
        }
        
        .summary-cards {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        
        .card {
            flex: 1;
            background: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin: 0 10px;
            text-align: center;
            border-top: 4px solid #3498db;
        }
        
        .card h3 {
            color: #2c3e50;
            margin-top: 0;
        }
        
        .card .value {
            font-size: 24px;
            font-weight: bold;
            color: #3498db;
            margin: 10px 0;
        }
        
        .filters {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .filter-group {
            flex: 1;
            min-width: 200px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        select, input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        button {
            background: #3498db;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        button:hover {
            background: #2980b9;
        }
    </style> -->
<body>
    
    <!-- HEADER -->
    <?php include('includes/header.php'); ?>

    <div class="container-1" id="container">

        <!-- Messages -->
        <?php include('includes/messages/message.php'); ?>
        <?php include('includes/messages/cancelConfirmation.php'); ?>

        <!-- EDIT RECORDS MODAL-->
        <?php include('includes/editRecords.php'); ?>
        <?php include('includes/vitalSignsConsultModal.php'); ?>

        <!-- navigation bar / TAB -->
        <div class="navbar-2">
            <div class="tab active" onclick="showContent(0)">Inquiry</div>
            <div class="tab" onclick="showContent(1)">Pending</div>
            <div class="tab" onclick="showContent(2)"><span id="tab-face-to-face">Face to face</span><span id="tab-f2f">F2F</span></div>
            <div class="tab" onclick="showContent(3)"><span id="tab-face-to-face">Teleconsultation</span><span id="tab-f2f">Telecon</span></div>
            <div class="tab" onclick="showContent(4)">Calendar</div>
            <div class="tab" onclick="showContent(5)">Search</div>
            <div class="tab" onclick="showContent(6)">Reports</div>
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
                                <option class="select" value="" hidden disabled selected>--- Select Option ---</option>
                                <option value="SMS">SMS</option>
                                <option value="Receive Call">Call</option>
                                <option value="Online">Online</option>
                                <option value="Walk-In">Walk-in</option>
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
                            <!-- <input type="date" id="birthday" name="birthday" class="b_day" require> -->
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
                        
                        <!-- div.box Child element 11 -->
                        <div class="input">
                            <label for="" class="informant_name">Informant's Name: <i class="asterisk">*</i></label>
                            <input type="text" name="informant" class="informant_name" require>
                        </div>
                        
                        <!-- div.box Child element 12 -->
                        <div class="input">
                            <label for="" class="rel_informant">Relation to informant: <i class="asterisk">*</i></label>
                            <input type="text" name="informant_relation" class="rel_informant" require>
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
                                        <div class="radio-div">
                                            <div>
                                                <input type="radio" name="consultation" id="f2f" value="Face to face" class="custom-radio">
                                                <label for="f2f">Face to face</label>
                                            </div>
                                            <div>
                                                <input type="radio" name="consultation" id="teleconsult" value="Teleconsultation" class="custom-radio">
                                                <label for="teleconsult">Teleconsultation</label>
                                            </div>
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
                            <label for="q1">Ano ang ipapakunsulta? <i class="asterisk">*</i></label>
                            <select name="complaint" class="options" required>
                                <option value="" hidden disabled selected>--- Select Option ---</option>
                                
                                <?php
                                    $sql1 = "SELECT id, name FROM neurology_classifications WHERE archived = 0";
                                    $result1 = $conn->query($sql1);
                                    
                                    if ($result1->num_rows > 0) {
                                        while($row = $result1->fetch_assoc()) {
                                            echo "<option value='" . $row['name'] . "'>" . htmlspecialchars($row['name']) . "</option>";
                                        }
                                    } else {
                                        echo "<option disabled>No classifications found</option>";
                                    }
                                ?>
                                
                                <option value="Other">Other</option>
                            </select>
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
                            $query = "SELECT r.id, r.hrn, r.name, r.contact, 
                                     c.consultation, c.date_sched, c.complaint, c.status
                              FROM neurology_records r
                              INNER JOIN neurology_consultations c ON r.id = c.record_id
                              WHERE c.status = 'pending'";

                            $query_run = mysqli_query($conn, $query);

                            if(mysqli_num_rows($query_run) > 0)
                            {
                                foreach($query_run as $records)
                                {
                                    ?>
                                        <tr id="patient_<?=$records['id'];?>">
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
                                                <!-- Action / Mark as Approve -->
                                                <img src="img/check-circle.png" class="action-img update-approve margin-right" alt="image here" data-id="<?=$records['id'];?>">
                                            
                                                <!-- Action / View -->
                                                <img src="img/edit.png" class="action-img view-button margin-right" alt="image here" data-record-id="<?=$records['id'];?>">
                                                
                                                <!-- Action / Mark as Cancelled -->
                                                <img src="img/cancel.png" class="action-img update-cancelled" alt="image here" data-id="<?=$records['id'];?>">
                                            </td>
                                        </tr>
                                    <?php
                                }
                            }
                            // else
                            // {
                            //     // If no records found, display a single row with a "No Records Found" message
                            //     echo "<tr><td colspan='7' style='text-align: center; font-size: 1.5rem; padding: 16px;'>No records found</td></tr>";
                            // }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- TAB 3 / FACE TO FACE CHECK-UP -->
            <div class="content">
                <div class="flex">
                    
                    <select name="" class="sortData-Pending selectDate-border" id="monthFilter2">
                        <option value="" hidden disabled selected>Select Month</option>
                    </select>

                    <select name="" class="sortData-Day selectDate-border" id="dayFilter2">
                        <option value="" hidden disabled selected>Select Day</option>
                    </select>

                    <select name="" class="sortData-Year selectDate-border" id="yearFilter2">
                        <option value="" hidden disabled selected>Select Year</option>
                    </select>

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
                            $query = "SELECT r.id, r.hrn, r.name, r.contact, c.date_sched, c.complaint
                            FROM neurology_records r
                            INNER JOIN neurology_consultations c ON r.id = c.record_id
                            WHERE c.status = 'approved' and c.consultation = 'Face to face' OR c.status = 'follow up' AND c.consultation = 'Face to face'";

                            // Execute query
                            $query_run = mysqli_query($conn, $query);

                            if(mysqli_num_rows($query_run) > 0) {
                                foreach($query_run as $records) {
                                    ?>
                                    <tr id="patient_<?=$records['id'];?>">
                                        <input type="hidden" name="patient_id" value="<?=$records['id'];?>">
                                        <td class="th-check border-left"><input type="checkbox" class="checkbox custom-checkbox"></td>
                                        <td class="th-hrn"><?= $records['hrn']; ?></td>
                                        <td class="th-name"><?= $records['name']; ?></td>
                                        <td class="th-contact"><?= $records['contact']; ?></td>
                                        <td class="th-schedule"><?= $records['date_sched']; ?></td>
                                        <td class="th-complaint"><?= $records['complaint']; ?></td>
                                        <td class="th-action action border-right">
                                            <!-- <a href="">  -->
                                                <img src="img/check-circle.png" class=" action-img margin-right" alt="Approve" data-record-id="<?=$records['id'];?>">
                                            <!-- </a> -->
                                            <!-- update-processed data-id="<?=$records['id'];?>"-->
                                            <img src="img/edit.png" class="action-img view-button margin-right" alt="View" data-record-id="<?=$records['id'];?>">
                                            <img src="img/cancel.png" class="action-img update-cancelled" alt="Cancel" data-id="<?=$records['id'];?>">
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } 
                            // else {
                            //     echo "<tr><td colspan='7' style='text-align: center; font-size: 2rem; padding: 32px 0 32px 0;'>No records found</td></tr>";
                            // }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- TAB 4 / TELECONSULTATION -->
            <div class="content">
                <div class="flex">
                    
                    <select name="" class="sortData-Pending selectDate-border" id="monthFilter3">
                        <option value="" hidden disabled selected>Select Month</option>
                    </select>
    
                    <select name="" class="sortData-Day selectDate-border" id="dayFilter3">
                        <option value="" hidden disabled selected>Select Day</option>
                    </select>

                    <select name="" class="sortData-Year selectDate-border" id="yearFilter3">
                        <option value="" hidden disabled selected>Select Year</option>
                    </select>
               
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
                            $query = "SELECT r.id, r.hrn, r.name, r.contact, c.date_sched, c.complaint
                            FROM neurology_records r
                            INNER JOIN neurology_consultations c ON r.id = c.record_id
                            WHERE c.status = 'approved' and c.consultation = 'Teleconsultation' OR c.status = 'follow up' AND c.consultation = 'Teleconsultation'";

                            // Execute query
                            $query_run = mysqli_query($conn, $query);

                            if(mysqli_num_rows($query_run) > 0) {
                                foreach($query_run as $records) {
                                    ?>
                                    <tr id="patient_<?=$records['id'];?>">
                                        <input type="hidden" name="patient_id" value="<?=$records['id'];?>">
                                        <td class="th-check border-left"><input type="checkbox" class="checkbox custom-checkbox"></td>
                                        <td class="th-hrn"><?= $records['hrn']; ?></td>
                                        <td class="th-name"><?= $records['name']; ?></td>
                                        <td class="th-contact"><?= $records['contact']; ?></td>
                                        <td class="th-schedule"><?= $records['date_sched']; ?></td>
                                        <td class="th-complaint"><?= $records['complaint']; ?></td>
                                        <td class="th-action action border-right">
                                            <!-- <a href="./consultation.php" target="_blank" rel="noopener noreferrer">  -->
                                                <img src="img/check-circle.png" class=" action-img margin-right" alt="Approve" data-record-id="<?=$records['id'];?>">
                                            <!-- </a> -->
                                            <img src="img/edit.png" class="action-img update-processed margin-right" alt="View" data-record-id="<?=$records['id'];?>">
                                            <img src="img/cancel.png" class="action-img update-cancelled" alt="Cancel" data-id="<?=$records['id'];?>">
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } 
                            // else {
                            //     echo "<tr><td colspan='7' style='text-align: center; font-size: 2rem; padding: 32px 0 32px 0;'>No records found</td></tr>";
                            // }
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
                                
                                <!-- <div class="calendar-btn">
                                    <span class="reschedule-calendar-trigger btn btn-blue">Calendar</span>
                                </div> -->
                            </div>
                        </div>
                        
                        <div class="modal-footer">
                            <button class="btn btn-green" id="confirmReschedule">Confirm</button>
                            <button class="btn btn-red" id="cancelReschedule">Cancel</button>
                        </div>
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
                            <form action="" class="limit_form">
                                <div class="space-between">
                                    <label for="onlinef2f">F2F: New</label>
                                    <input type="number" name="online_F2F_limit">
                                    <!-- <select name="" id="onlinef2f">
                                    </select> -->
                                </div>
    
                                <div class="space-between">
                                    <label for="referrals_limit">Referrals:</label>
                                    <input type="number" name="referral_limit">
                                    <!-- <select name="" id="referrals_limit">
                                    </select> -->
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
                <div class="tabs-Rep">
                    <div class="tabRep active" onclick="openTab('patient-reports')">Patient Reports</div>
                    <div class="tabRep" onclick="openTab('medication-consumption')">Medication Consumption</div>
                    <div class="tabRep" onclick="openTab('case-load')">Case Load</div>
                </div>

                <!-- Patient Reports Tab -->
                <div id="patient-reports" class="tab-content active">
                    <h2>Patient Reports</h2>
                    
                    <div class="filters">
                        <div class="filter-group">
                            <label for="timeframe">Timeframe:</label>
                            <select id="timeframe">
                                <option>Last 7 days</option>
                                <option>Last 30 days</option>
                                <option>Last 90 days</option>
                                <option>Last year</option>
                                <option>Custom range</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="department">Department:</label>
                            <select id="department">
                                <option>All Departments</option>
                                <option>Cardiology</option>
                                <option>Neurology</option>
                                <option>Oncology</option>
                                <option>Pediatrics</option>
                                <option>General Medicine</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="patient-type">Patient Type:</label>
                            <select id="patient-type">
                                <option>All Patients</option>
                                <option>Inpatient</option>
                                <option>Outpatient</option>
                                <option>Emergency</option>
                            </select>
                        </div>
                        
                        <div class="filter-group" style="align-self: flex-end;">
                            <button>Apply Filters</button>
                        </div>
                    </div>
                    
                    <div class="summary-cards">
                        <div class="card">
                            <h3>Total Patients</h3>
                            <div class="value">1,248</div>
                            <div>+12% from last period</div>
                        </div>
                        
                        <div class="card">
                            <h3>Average Stay</h3>
                            <div class="value">3.2 days</div>
                            <div>-0.5 days from last period</div>
                        </div>
                        
                        <div class="card">
                            <h3>Readmission Rate</h3>
                            <div class="value">8.5%</div>
                            <div>-1.2% from last period</div>
                        </div>
                        
                        <div class="card">
                            <h3>Satisfaction Score</h3>
                            <div class="value">4.6/5</div>
                            <div>+0.2 from last period</div>
                        </div>
                    </div>
                    
                    <div class="chart-container">
                        <!-- Chart would be rendered here with a library like Chart.js -->
                        <img src="https://via.placeholder.com/1200x400?text=Patient+Admissions+Trend+Chart" alt="Patient Admissions Trend Chart" style="width:100%; height:100%; object-fit: cover;">
                    </div>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>Patient ID</th>
                                <th>Name</th>
                                <th>Admission Date</th>
                                <th>Discharge Date</th>
                                <th>Department</th>
                                <th>Diagnosis</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>PT-1001</td>
                                <td>John Smith</td>
                                <td>2023-05-15</td>
                                <td>2023-05-18</td>
                                <td>Cardiology</td>
                                <td>Hypertension</td>
                                <td>Discharged</td>
                            </tr>
                            <tr>
                                <td>PT-1002</td>
                                <td>Sarah Johnson</td>
                                <td>2023-05-16</td>
                                <td>-</td>
                                <td>Neurology</td>
                                <td>Migraine</td>
                                <td>In Treatment</td>
                            </tr>
                            <tr>
                                <td>PT-1003</td>
                                <td>Michael Brown</td>
                                <td>2023-05-10</td>
                                <td>2023-05-12</td>
                                <td>General Medicine</td>
                                <td>Influenza</td>
                                <td>Discharged</td>
                            </tr>
                            <tr>
                                <td>PT-1004</td>
                                <td>Emily Davis</td>
                                <td>2023-05-17</td>
                                <td>-</td>
                                <td>Pediatrics</td>
                                <td>Asthma</td>
                                <td>In Treatment</td>
                            </tr>
                            <tr>
                                <td>PT-1005</td>
                                <td>Robert Wilson</td>
                                <td>2023-05-05</td>
                                <td>2023-05-09</td>
                                <td>Oncology</td>
                                <td>Follow-up</td>
                                <td>Discharged</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Medication Consumption Tab -->
                <div id="medication-consumption" class="tab-content">
                    <h2>Medication Consumption</h2>
                    
                    <div class="filters">
                        <div class="filter-group">
                            <label for="med-timeframe">Timeframe:</label>
                            <select id="med-timeframe">
                                <option>Last 7 days</option>
                                <option>Last 30 days</option>
                                <option selected>Last 90 days</option>
                                <option>Last year</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="med-category">Medication Category:</label>
                            <select id="med-category">
                                <option>All Categories</option>
                                <option>Analgesics</option>
                                <option>Antibiotics</option>
                                <option>Antihypertensives</option>
                                <option>Psychotropics</option>
                                <option>Other</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="med-sort">Sort By:</label>
                            <select id="med-sort">
                                <option>Highest Consumption</option>
                                <option>Lowest Consumption</option>
                                <option>Alphabetical</option>
                            </select>
                        </div>
                        
                        <div class="filter-group" style="align-self: flex-end;">
                            <button>Apply Filters</button>
                        </div>
                    </div>
                    
                    <div class="summary-cards">
                        <div class="card">
                            <h3>Total Medications</h3>
                            <div class="value">87</div>
                            <div>Different medications used</div>
                        </div>
                        
                        <div class="card">
                            <h3>Most Prescribed</h3>
                            <div class="value">Paracetamol</div>
                            <div>1,245 doses</div>
                        </div>
                        
                        <div class="card">
                            <h3>Average Daily Use</h3>
                            <div class="value">428</div>
                            <div>Doses per day</div>
                        </div>
                        
                        <div class="card">
                            <h3>Controlled Substances</h3>
                            <div class="value">12%</div>
                            <div>Of total medications</div>
                        </div>
                    </div>
                    
                    <div class="chart-container">
                        <!-- Chart would be rendered here with a library like Chart.js -->
                        <img src="https://via.placeholder.com/1200x400?text=Medication+Consumption+Trend+Chart" alt="Medication Consumption Trend Chart" style="width:100%; height:100%; object-fit: cover;">
                    </div>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>Medication ID</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Quantity Used</th>
                                <th>Average Daily Use</th>
                                <th>Stock Level</th>
                                <th>Reorder Needed</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>MED-5001</td>
                                <td>Paracetamol 500mg</td>
                                <td>Analgesic</td>
                                <td>1,245</td>
                                <td>13.8</td>
                                <td>2,100</td>
                                <td>No</td>
                            </tr>
                            <tr>
                                <td>MED-5002</td>
                                <td>Amoxicillin 250mg</td>
                                <td>Antibiotic</td>
                                <td>876</td>
                                <td>9.7</td>
                                <td>1,200</td>
                                <td>No</td>
                            </tr>
                            <tr>
                                <td>MED-5003</td>
                                <td>Lisinopril 10mg</td>
                                <td>Antihypertensive</td>
                                <td>654</td>
                                <td>7.3</td>
                                <td>800</td>
                                <td>Yes</td>
                            </tr>
                            <tr>
                                <td>MED-5004</td>
                                <td>Diazepam 5mg</td>
                                <td>Psychotropic</td>
                                <td>321</td>
                                <td>3.6</td>
                                <td>450</td>
                                <td>Yes</td>
                            </tr>
                            <tr>
                                <td>MED-5005</td>
                                <td>Ibuprofen 400mg</td>
                                <td>Analgesic</td>
                                <td>1,098</td>
                                <td>12.2</td>
                                <td>1,800</td>
                                <td>No</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Case Load Tab -->
                <div id="case-load" class="tab-content">
                    <h2>Case Load Distribution</h2>
                    
                    <div class="filters">
                        <div class="filter-group">
                            <label for="case-timeframe">Timeframe:</label>
                            <select id="case-timeframe">
                                <option>Current</option>
                                <option>Last 7 days</option>
                                <option>Last 30 days</option>
                                <option selected>Last 90 days</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="staff-role">Staff Role:</label>
                            <select id="staff-role">
                                <option>All Staff</option>
                                <option>Doctors</option>
                                <option>Nurses</option>
                                <option>Specialists</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="case-sort">Sort By:</label>
                            <select id="case-sort">
                                <option>Highest Case Load</option>
                                <option>Lowest Case Load</option>
                                <option>Alphabetical</option>
                            </select>
                        </div>
                        
                        <div class="filter-group" style="align-self: flex-end;">
                            <button>Apply Filters</button>
                        </div>
                    </div>
                    
                    <div class="summary-cards">
                        <div class="card">
                            <h3>Total Staff</h3>
                            <div class="value">48</div>
                            <div>Active medical staff</div>
                        </div>
                        
                        <div class="card">
                            <h3>Average Case Load</h3>
                            <div class="value">26</div>
                            <div>Cases per staff</div>
                        </div>
                        
                        <div class="card">
                            <h3>Highest Case Load</h3>
                            <div class="value">Dr. Johnson</div>
                            <div>42 cases</div>
                        </div>
                        
                        <div class="card">
                            <h3>Staff Satisfaction</h3>
                            <div class="value">4.2/5</div>
                            <div>Workload rating</div>
                        </div>
                    </div>
                    
                    <div class="chart-container">
                        <!-- Chart would be rendered here with a library like Chart.js -->
                        <img src="https://via.placeholder.com/1200x400?text=Case+Load+Distribution+Chart" alt="Case Load Distribution Chart" style="width:100%; height:100%; object-fit: cover;">
                    </div>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>Staff ID</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Department</th>
                                <th>Active Cases</th>
                                <th>Completed Cases</th>
                                <th>Total Cases</th>
                                <th>Workload</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>ST-2001</td>
                                <td>Dr. Johnson</td>
                                <td>Cardiologist</td>
                                <td>Cardiology</td>
                                <td>18</td>
                                <td>24</td>
                                <td>42</td>
                                <td>High</td>
                            </tr>
                            <tr>
                                <td>ST-2002</td>
                                <td>Dr. Williams</td>
                                <td>Neurologist</td>
                                <td>Neurology</td>
                                <td>12</td>
                                <td>22</td>
                                <td>34</td>
                                <td>Medium</td>
                            </tr>
                            <tr>
                                <td>ST-2003</td>
                                <td>Nurse Peterson</td>
                                <td>RN</td>
                                <td>General Medicine</td>
                                <td>8</td>
                                <td>28</td>
                                <td>36</td>
                                <td>Medium</td>
                            </tr>
                            <tr>
                                <td>ST-2004</td>
                                <td>Dr. Lee</td>
                                <td>Pediatrician</td>
                                <td>Pediatrics</td>
                                <td>15</td>
                                <td>15</td>
                                <td>30</td>
                                <td>Medium</td>
                            </tr>
                            <tr>
                                <td>ST-2005</td>
                                <td>Nurse Garcia</td>
                                <td>RN</td>
                                <td>Oncology</td>
                                <td>5</td>
                                <td>20</td>
                                <td>25</td>
                                <td>Low</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>



        <div id="footer">
            <h4>&copy; 2024 - MMWGH (IMISU)</h4>
        </div>
    </div>

    <!-- Javascript -->
    <script src="js/mainScript.js"></script>
    <script src="js/approval-f2f-telecon.js"></script>
    <script src="js/modal.js"></script>
    <script src="js/calendar_booking.js"></script>
    <script src="js/calendar_schedule.js"></script>
    <script src="js/searchTab.js"></script>
    <script src="js/consultation.js"></script>
    <script src="js/functions.js"></script>

    <script>
        // // LIMIT PER DAY - APPOINTMENT
        // function populateDropdown(dropdownId) {
        //     let dropdown = document.getElementById(dropdownId);
        //     for (let i = 0; i <= 100; i++) {
        //         let option = document.createElement('option');
        //         option.value = i;
        //         option.textContent = i;
        //         dropdown.appendChild(option);
        //     }
        // }
    
        // populateDropdown('onlinef2f');
        // populateDropdown('referrals_limit');



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

    <script>
        // REPORTS TABS
        function openTab(tabName) {
            // Hide all tab contents
            var tabContents = document.getElementsByClassName('tab-content');
            for (var i = 0; i < tabContents.length; i++) {
                tabContents[i].classList.remove('active');
            }
            
            // Remove active class from all tabs
            var tabs = document.getElementsByClassName('tabRep');
            for (var i = 0; i < tabs.length; i++) {
                tabs[i].classList.remove('active');
            }
            
            // Show the current tab and add active class
            document.getElementById(tabName).classList.add('active');
            event.currentTarget.classList.add('active');
        }
    </script>

</body>
</html>