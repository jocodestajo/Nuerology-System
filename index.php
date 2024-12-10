<?php
session_start();
require 'config/dbcon.php';
require 'dateTime.php';
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
        <?php include('includes/header'); ?>

        <div class="navbar-2">
            <div class="tab active" onclick="showContent(0)">Inquiry</div>
            <div class="tab" onclick="showContent(1)">For Approval</div>
            <div class="tab" onclick="showContent(2)"><span id="tab-face-to-face">Face to face</span><span id="tab-f2f">F2F</span></div>
            <div class="tab" onclick="showContent(3)"><span id="tab-face-to-face">Teleconsultation</span><span id="tab-f2f">Telecon</span></div>
            <div class="tab" onclick="showContent(4)">Calendar</div>
            <div class="tab" onclick="showContent(5)">Search</div>
        </div>

        <div class="container-2">

            <?php include('includes/messages/message.php'); ?>
            <?php include('includes/messages/cancelConfirmation.php'); ?>
            <?php include('includes/editRecords.php'); ?>

            <!-- TAB 1 / INQUIRY -->
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
                            <label for="" class="age">Age:</label>
                            <input type="text" id="age" name="age" class="age">
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
                            <label for="" class="informant_name">Informant's Name:</label>
                            <input type="text" name="informant" class="informant_name">
                        </div>
                        
                        <!-- div.box Child element 12 -->
                        <div class="input">
                            <label for="" class="rel_informant">Relation to informant:</label>
                            <input type="text" name="informant_relation" class="rel_informant">
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
                                        <label for="date">Date Schedule: <i class="asterisk">*</i></label>
                                        <input type="date" id="date" name="date_sched" required>
                                    </div>
                                    <div class="calendar-btn">
                                        <span class="btn btn-blue">Calendar</span>
                                    </div>
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

                    <!-- <label class="label-border"> -->
                        <!-- Month: -->
                    <!-- </label> -->

                    <!-- <label class="label-border"> -->
                        <!-- Day: -->
                    <!-- </label> -->

                    <!-- <label class="label-border"> -->
                        <!-- Year: -->
                    <!-- </label> -->

                    
                    <select name="" class="sortData-Day label-border" id="dayFilter1">
                        <option value="" hidden disabled selected>Select Day</option>
                    </select>
                    
                    <select name="" class="sortData-Pending label-border" id="monthFilter1">
                        <option value="" hidden disabled selected>Select Month</option>
                    </select>

                    <select name="" class="sortData-Year label-border" id="yearFilter1">
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
                            // calling data in db table
                            $query = "SELECT * FROM neurology_records WHERE status = 'pending'";

                            // Para mag work yung $query need niya tawagin yung db kaya may $query_run para ideclare both  $query and $con
                            $query_run = mysqli_query($conn, $query);

                            // condition if what data will be called
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

                    <select name="" class="sortData-Day label-border" id="dayFilter2">
                        <option value="" hidden disabled selected>Select Day</option>
                    </select>

                    <select name="" class="sortData-Pending label-border" id="monthFilter2">
                        <option value="" hidden disabled selected>Select Month</option>
                    </select>

                    <select name="" class="sortData-Year label-border" id="yearFilter2">
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

                <select name="" class="sortData-Day label-border" id="dayFilter3">
                    <option value="" hidden disabled selected>Select Day</option>
                </select>

                <select name="" class="sortData-Pending label-border" id="monthFilter3">
                    <option value="" hidden disabled selected>Select Month</option>
                </select>

                <select name="" class="sortData-Year label-border" id="yearFilter3">
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
                <?php include('calendarTable.php'); ?>
            </div>

             <!-- TAB 6 / SEARCH -->
             <div class="content">
                    THIS IS SEARCH TAB
            </div>
        </div>

        <div class="footer">
            <h4>&copy; 2024 - MMWGH (IMISU)</h4>
        </div>
    </div>

    <!-- Javascript -->
     <!-- <script>
        // Get today's date
        var today = new Date();

        // Function to populate months and years dropdown
        function populateMonthAndYearDropdowns() {
            var currentYear = today.getFullYear();
            var currentMonth = today.getMonth();

            // Populate months (January to December)
            var monthSelect = document.getElementById("monthFilter");
            for (var month = 0; month < 12; month++) {
                var option = document.createElement("option");
                option.value = month + 1; // 1 to 12 for months
                option.textContent = new Date(currentYear, month).toLocaleString("default", { month: "long" }); // Month name
                monthSelect.appendChild(option);
            }

            // Populate years (e.g., 2023, 2024, etc.)
            var yearSelect = document.getElementById("yearFilter");
            for (var year = currentYear - 5; year <= currentYear + 5; year++) {  // Show years from 5 years ago to 5 years ahead
                var option = document.createElement("option");
                option.value = year;
                option.textContent = year;
                yearSelect.appendChild(option);
            }
        }

        // Function to get the first day of the selected month and year (dynamically)
        function getFirstDayOfMonth(month, year) {
            return new Date(year, month - 1, 1); // The first day of the month
        }

        // Function to get the last day of the selected month and year
        function getLastDayOfMonth(month, year) {
            return new Date(year, month, 0); // Last day of the selected month
        }

        // Function to get the number of days in the selected month
        function getDaysInMonth(month, year) {
            return new Date(year, month, 0).getDate(); // Gets the number of days in the selected month
        }

        // Function to update the day dropdown based on selected month and year
        function updateDayDropdown(month, year) {
            var daySelect = document.getElementById("dayFilter");
            daySelect.innerHTML = ""; // Clear existing options

            // Get the number of days in the selected month
            var daysInMonth = getDaysInMonth(month, year);

            // Populate the day dropdown with days from 1 to the last day of the selected month
            for (var day = 1; day <= daysInMonth; day++) {
                var option = document.createElement("option");
                option.value = day;
                option.textContent = day;
                daySelect.appendChild(option);
            }
        }

        // Function to update the date picker to the selected month, year, and day
        function updateDatePicker(month, year, day) {
            var selectedDate = new Date(year, month - 1, day); // Create a date object for the selected day
            var formattedDate = selectedDate.toISOString().split('T')[0]; // Format to YYYY-MM-DD
            
            // Update the date picker input field with the formatted date
            document.querySelector(".dateFilterPending").value = formattedDate;
        }

        // Function to filter the table rows based on the selected day, month, and year
        function filterTableByDayMonthYear(tableClass, selectedDay, selectedMonth, selectedYear) {
            var rows = document.querySelectorAll(tableClass + " tbody tr");
            var selectedDate = new Date(selectedYear, selectedMonth - 1, selectedDay); // Create a date object

            rows.forEach(function (row) {
                var scheduleCell = row.querySelector(".th-schedule");
                if (scheduleCell) {
                    var scheduleDate = new Date(scheduleCell.textContent.trim());

                    // Filter rows by matching the exact selected date
                    if (scheduleDate.toDateString() === selectedDate.toDateString()) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                }
            });

            // Check if no rows are visible (i.e., no matching records)
            var tableBody = document.querySelector(tableClass + " tbody");
            var existingNoRecordsRow = tableBody.querySelector(".no-records");

            var visibleRows = Array.from(rows).filter((row) => row.style.display !== "none");

            if (visibleRows.length === 0) {
                // If "No records found" row doesn't exist, create and append it
                if (!existingNoRecordsRow) {
                    var noRecordsRow = document.createElement("tr");
                    noRecordsRow.classList.add("no-records");
                    noRecordsRow.innerHTML =
                        '<td colspan="7" style="text-align: center; font-size: 2rem; padding: 32px 0 32px 0;">No records found</td>';
                    tableBody.appendChild(noRecordsRow);
                }
            } else {
                // Remove the "No records found" row if matching records are found
                if (existingNoRecordsRow) {
                    existingNoRecordsRow.remove();
                }
            }
        }

        // Event listener for the month change
        document.getElementById("monthFilter").addEventListener("change", function () {
            var selectedMonth = parseInt(this.value);
            var selectedYear = document.getElementById("yearFilter").value;
            var selectedDay = document.getElementById("dayFilter").value;

            if (selectedMonth && selectedYear) {
                // Update day dropdown based on selected month and year
                updateDayDropdown(selectedMonth, selectedYear);

                // Set the date picker to the first day of the selected month
                updateDatePicker(selectedMonth, selectedYear, 1);

                // Apply the filter to the tables
                filterTableByDayMonthYear(".table-pending", selectedDay, selectedMonth, selectedYear);
                filterTableByDayMonthYear(".table-face-to-face", selectedDay, selectedMonth, selectedYear);
                filterTableByDayMonthYear(".table-teleconsultation", selectedDay, selectedMonth, selectedYear);
            }
        });

        // Event listener for the year change
        document.getElementById("yearFilter").addEventListener("change", function () {
            var selectedYear = parseInt(this.value);
            var selectedMonth = document.getElementById("monthFilter").value;
            var selectedDay = document.getElementById("dayFilter").value;

            if (selectedYear && selectedMonth) {
                // Update day dropdown based on selected month and year
                updateDayDropdown(selectedMonth, selectedYear);

                // Set the date picker to the first day of the selected month
                updateDatePicker(selectedMonth, selectedYear, 1);

                // Apply the filter to the tables
                filterTableByDayMonthYear(".table-pending", selectedDay, selectedMonth, selectedYear);
                filterTableByDayMonthYear(".table-face-to-face", selectedDay, selectedMonth, selectedYear);
                filterTableByDayMonthYear(".table-teleconsultation", selectedDay, selectedMonth, selectedYear);
            }
        });

        // Event listener for the day change
        document.getElementById("dayFilter").addEventListener("change", function () {
            var selectedDay = parseInt(this.value);
            var selectedMonth = document.getElementById("monthFilter").value;
            var selectedYear = document.getElementById("yearFilter").value;

            if (selectedDay && selectedMonth && selectedYear) {
                // Set the date picker to the selected day
                updateDatePicker(selectedMonth, selectedYear, selectedDay);

                // Apply the filter to the tables
                filterTableByDayMonthYear(".table-pending", selectedDay, selectedMonth, selectedYear);
                filterTableByDayMonthYear(".table-face-to-face", selectedDay, selectedMonth, selectedYear);
                filterTableByDayMonthYear(".table-teleconsultation", selectedDay, selectedMonth, selectedYear);
            }
        });

        // Event listener for the "Show All" filter option
        document.getElementById("filterOption").addEventListener("change", function () {
            var selectedOption = this.value;
            var selectedDay = document.getElementById("dayFilter").value;
            var selectedMonth = document.getElementById("monthFilter").value;
            var selectedYear = document.getElementById("yearFilter").value;

            if (selectedOption === "showAll") {
                // Show all rows
                document.querySelectorAll(".table-pending tbody tr").forEach(row => row.style.display = "");
                document.querySelectorAll(".table-face-to-face tbody tr").forEach(row => row.style.display = "");
                document.querySelectorAll(".table-teleconsultation tbody tr").forEach(row => row.style.display = "");
            }
        });

        // Initialize dropdowns and date picker on page load
        window.onload = function () {
            populateMonthAndYearDropdowns(); // Populate months and years
        };


     </script> -->
    <script src="js/mainScript.js"></script>
    <script src="js/modal.js"></script>
</body>
</html>