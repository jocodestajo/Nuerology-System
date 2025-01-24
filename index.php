<?php
session_start();
require 'config/dbcon.php';
require 'includes/dateTime.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neurology Unit</title>
    <link rel="stylesheet" href="css/mainStyle.css">
    <link rel="stylesheet" href="css/mediaQuery.css">
    <link rel="stylesheet" href="css/modals.css">
</head>
<body>
    <div class="container-1" id="container">
        
        <!-- HEADER -->
        <?php include('includes/header.php'); ?>

        <!-- navigation bar / TAB -->
        <div class="navbar-2">
            <div class="tab active" onclick="showContent(0)">Inquiry</div>
            <div class="tab" onclick="showContent(1)">For Approval</div>
            <div class="tab" onclick="showContent(2)"><span id="tab-face-to-face">Face to face</span><span id="tab-f2f">F2F</span></div>
            <div class="tab" onclick="showContent(3)"><span id="tab-face-to-face">Teleconsultation</span><span id="tab-f2f">Telecon</span></div>
            <div class="tab" onclick="showContent(4)">Calendar</div>
            <div class="tab" onclick="showContent(5)">Search</div>
        </div>

        <div class="container-2">

            <!-- MODAL and Alert messages -->
            <?php include('includes/messages/message.php'); ?>
            <?php include('includes/messages/cancelConfirmation.php'); ?>
            <?php include('includes/editRecords.php'); ?>

            <!-- TAB 1 / INQUIRY -->
            <!-- pag isahin na lang yung form ng inquiry at edit form sa f2f at telecon -->
            <div class="content active">
                <div class="form-content">
                    <form action="api/post/createData.php" method="post" class="box" autocomplete="off"> 

                        <!-- div.box Child element 1 -->
                        <div class="currentdate">
                            <div>
                                <label for="" class="date">Date:</label> 
                                <input type="text" id="currentdate" name="date_request" class="datetime" value="<?php echo $currentDate; ?>" readonly>
                            </div>
                        </div>
                        
                        <!-- div.box Child element 2 -->
                        <div class="input appointment-type">
                            <label>Type of appointment: <i class="asterisk">*</i></label>
                            <select name="typeofappoint" class="appointment" required>
                                <option class="select" value="" hidden disabled selected>--- Select Option ---</option>
                                <option value="SMS">SMS</option>
                                <option value="Receive Call">Call</option>
                                <option value="Online">Online</option>
                                <option value="Walk-In">Walk-in</option>
                                <option value="Follow Up">Follow up</option>
                            </select>
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
                        
                        <!-- div.box Child element 5 / AGE -->
                        <div class="input">
                            <label for="" class="age">Age: <i class="asterisk">*</i></label>
                            <input type="text" id="age" name="age" class="age" require>
                        </div>
                        
                        <!-- div.box Child element 6 / ADDRESS -->
                        <div class="input">
                            <label for="" class="address">Address:</label>
                            <input type="text" id="address" name="address" class="address">
                        </div>
                        
                        <!-- div.box Child element 7 / BDATE -->
                        <div class="input">
                            <label for="" class="b_day">Birthdate:</label>
                            <input type="text" id="birthday" name="birthday" class="b_day" placeholder="mm/dd/yyyy">
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
                                            <option value="" class="select" hidden disabled selected>--- Select Option ---</option>
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
                                        <label for="date-sched">Date Schedule: <i class="asterisk">*</i></label>
                                        <input type="date" id="date-sched" class="date" name="date_sched" readonly>
                                    </div>

                                    <div class="calendar-btn">
                                        <span class="btn-trigger btn btn-blue">Calendar</span>
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
                                <option value="Epilepsy / Seisure (Kombulsyon)">Epilepsy / Seisure (Kombulsyon)</option>
                                <option value="Dementia (pagkalimot)">Dementia (pagkalimot)</option>
                                <option value="Stroke">Stroke</option>
                                <option value="Pananakit ng ulo">Pananakit ng ulo</option>
                                <option value="Panghihina / Pamamanhid ng isang bahagi ng katawan">Panghihina / Pamamanhid ng isang bahagi ng katawan</option>
                                <option value="Iba pang karamdaman">Iba pang karamdaman</option>
                            </select>
                        </div>
                        
                        <!-- div.box Child element 16 -->
                        <div class="input">
                            <label for="" class="">Magbigay ng maikling paglalarawan tungkol sa sakit: <i class="asterisk">*</i></label>
                            <textarea rows="5" name="history" class="form-control" required></textarea>
                        </div>

                        <!-- div.box Child element 18 -->
                        <div class="input referal">
                            <label for="" class="">Referral Source:</label>
                            <input type="text" name="referal">
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
                            <th class="th-schedule">Schedule</th>
                            <th class="th-complaint">Complaint</th>
                            <th class="th-action border-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $query = "SELECT * FROM neurology_records WHERE status = 'pending'";

                            // Para mag work yung $query need niya tawagin yung db kaya may $query_run para ideclare both  $query and $con
                            $query_run = mysqli_query($conn, $query);

                            if(mysqli_num_rows($query_run) > 0)
                            {
                                foreach($query_run as $records)
                                {
                                    ?>
                                        <tr id="patient_<?=$records['id'];?>">

                                            <td class="th-check border-left"><input type="checkbox" class="checkbox custom-checkbox"></td>
                                            <td class="th-hrn"><?= $records['hrn']; ?></td>
                                            <td class="th-name"><?= $records['name']; ?></td>
                                            <td class="th-contact"><?= $records['contact']; ?></td>
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
                            //     echo "<tr><td colspan='7' style='text-align: center; font-size: 2rem; padding: 32px 0 32px 0;'>No records found</td></tr>";
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
                            $query = "SELECT * FROM neurology_records WHERE status = 'approved' and consultation = 'face to face' ";

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
                                            <img src="img/check-circle.png" class="action-img update-processed margin-right" alt="Approve" data-id="<?=$records['id'];?>">
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
                            $query = "SELECT * FROM neurology_records WHERE status = 'approved' and consultation = 'teleconsultation'";

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
                                            <img src="img/check-circle.png" class="action-img update-processed margin-right" alt="Approve" data-id="<?=$records['id'];?>">
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

            <!-- TAB 5 / CALENDAR -->
            <div class="content">
                <div class="cal_header">
                    <div class="weekday-checkboxes">
                        <div class="weekday-title" onclick="toggleDropdown()">Weekly Schedule ▼</div>
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
                        <form action="" class="limit_form">
                            <label for="onlinef2f">Online F2F:</label>
                            <select name="" id="onlinef2f">
                                <!-- <option value=""></option> -->
                            </select>

                            <label for="referrals_limit">Referral:</label>
                            <select name="" id="referrals_limit">
                                <!-- <option value="0">0</option> -->
                            </select>

                            <button type="submit" name="submit_limit" class="btn btn-green">Save</button>
                        </form>
                    </div>  
                </div>

                <div class="flex">
                    <span class="calendarMonth-border" onclick="monthChange('previous')">Previous Month</span>
                    <span class="calendarMonth-border" onclick="monthChange('current')">Current Month</span>
                    <span class="calendarMonth-border" onclick="monthChange('next')">Next Month</span>
                </div>
                
                <h2 id="MonthTitle" class="calendarMonth flex"></h2>
                
                <table id="calendarTable_schedule">
                    <!-- calendar here -->
                </table>
            </div>

            <!-- TAB 6 / SEARCH -->
            <div class="content">
                <div class="search_scrolltab">
                    <div class="searchDiv">
                        <input type="text" id="searchQuery" placeholder="Enter name or hrn to search..." />
                        <button onclick="searchData()">Search</button>
                    </div>

                    <div class="scrollTab">
                        <a href="#pendingTable" class="scrollBtn">Pending</a>
                        <a href="#faceToFaceTable" class="scrollBtn">Face to Face</a>
                        <a href="#teleconsultationTable" class="scrollBtn">Teleconsultation</a>
                        <a href="#processedTable" class="scrollBtn">Processed</a>
                        <a href="#cancelledTable" class="scrollBtn">Cancelled</a>
                    </div>
                </div>

                <!-- Placeholder for tables -->
                <div id="pendingTable"></div>
                <div id="faceToFaceTable"></div>
                <div id="teleconsultationTable"></div>
                <div id="processedTable"></div>
                <div id="cancelledTable"></div>
            </div>
        </div>

        <div class="footer">
            <h4>&copy; 2024 - MMWGH (IMISU)</h4>
        </div>
    </div>

    <!-- Javascript -->
    <script src="js/mainScript.js"></script>
    <script src="js/modal.js"></script>
    <script src="js/approval-f2f-telecon.js"></script>
    <script src="js/calendar_booking.js"></script>
    <script src="js/calendar_schedule.js"></script>

    <script>
        function populateDropdown(dropdownId) {
            let dropdown = document.getElementById(dropdownId);
            for (let i = 0; i <= 100; i++) {
                let option = document.createElement('option');
                option.value = i;
                option.textContent = i;
                dropdown.appendChild(option);
            }
        }
    
        populateDropdown('onlinef2f');
        populateDropdown('referrals_limit');
    </script>

<script>
    async function searchData() {
        const query = document.getElementById('searchQuery').value;

        // Clear previous results
        document.getElementById('pendingTable').innerHTML = '';
        document.getElementById('faceToFaceTable').innerHTML = '';
        document.getElementById('teleconsultationTable').innerHTML = '';
        document.getElementById('processedTable').innerHTML = '';
        document.getElementById('cancelledTable').innerHTML = '';

        const response = await fetch(`searchQuery.php?query=${query}`);
        const appointments = await response.json();

        const pendingAppointments = appointments.filter(app => app.status === 'pending');
        const faceToFaceAppointments = appointments.filter(app => app.status === 'approved' && app.consultation === 'Face to face');
        const teleconsultationAppointments = appointments.filter(app => app.status === 'approved' && app.consultation === 'Teleconsultation');
        const processedAppointments = appointments.filter(app => app.status === 'processed');
        const cancelledAppointments = appointments.filter(app => app.status === 'cancelled');

        displayTable('pendingTable', 'Pending Appointments', pendingAppointments);
        displayTable('faceToFaceTable', 'Face to Face Appointments', faceToFaceAppointments);
        displayTable('teleconsultationTable', 'Teleconsultation Appointments', teleconsultationAppointments);
        displayTable('processedTable', 'Processed Appointments', processedAppointments);
        displayTable('cancelledTable', 'Cancelled Appointments', cancelledAppointments);
    }

    // Function to display the results in a table format
    function displayTable(tableId, title, appointments) {
        if (appointments.length > 0) {

            let tableHtml = `<h3 class="tableResultTitle">${title}</h3><table border="1"><thead><tr><th>HRN</th><th>Name</th><th>Schedule</th><th>Consultation</th><th>Status</th><th>Action</th></tr></thead><tbody>`;

            appointments.forEach(app => {
                tableHtml += `<tr><td>${app.hrn}</td><td>${app.name}</td><td>${app.date_sched}</td><td>${app.consultation}</td><td>${app.status}</td>
                <td class="th-action action border-right">
                    <img src="img/check-circle.png" class="action-img update-processed margin-right" alt="Approve" data-id="<?=$records['id'];?>">
                    <img src="img/edit.png" class="action-img view-button margin-right" alt="View" data-record-id="<?=$records['id'];?>">
                    <img src="img/cancel.png" class="action-img update-cancelled" alt="Cancel" data-id="<?=$records['id'];?>">
                </td></tr>`;
            });

            tableHtml += '</tbody></table>';
            document.getElementById(tableId).innerHTML = tableHtml;
        }
    }
</script>
</body>
</html>