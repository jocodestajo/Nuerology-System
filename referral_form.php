<?php
    session_start();
    require 'config/dbcon.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neurology Referral</title>
    <link rel="icon" href="img/MMWGH_Logo.png" type="images/x-icon">
    <link rel="stylesheet" href="css/mainStyle.css">
    <link rel="stylesheet" href="css/general.css">
    <link rel="stylesheet" href="css/appointment_form.css">
    <link rel="stylesheet" href="css/modals.css">
</head>
<body>
    <?php include('includes/messages/message.php'); ?>

    <div class="cont">
        <div class="header space-between">
            <h1>Neurology Referral Form</h1>
            <a href="" class="btn btn-grey border">Back</a>
        </div>
        <div class="cont-body">
            <form action="api/post/createData.php" method="post" class="appoint_form" autocomplete="off">
                <div class="personalInformation">
                    <!-- 1 -->
                    <div>
                        <h2>Personal Information</h2>
                    </div>

                    <!-- 2 -->
                    <div>
                        <label for="">Name:
                            <input type="text" name="name" required>
                        </label>
                    </div>

                    <!-- 3 -->
                    <div>
                        <label for="">Birthday:
                            <input type="date" name="birthday" class="birthdayInput" data-age-output="age2" required>
                        </label>
                    </div>
                        
                    <!-- 4 -->
                    <div>
                        <label for="">Age:
                            <input type="text" id="age2" name="age">
                        </label>
                    </div>

                    <!-- 5 -->
                    <div>
                        <label for="">Address:
                            <input type="text" name="address" required>
                        </label>
                    </div>

                    <!-- 6 -->
                    <!-- <div>
                        <h2 class="contactDetails">Contact Details</h2>
                    </div> -->

                    <!-- 7 -->
                    <div>
                        <label for="">Phone:
                            <input type="text" name="contact" required>
                        </label>
                    </div>

                    <!-- 8 -->
                    <div>
                    <label for="">Viber:
                            <input type="text" name="viber">
                        </label>
                    </div>
                    
                    <!-- 9 -->
                    <div>
                        <label for="">Email:
                            <input type="text" name="email" required>
                        </label>
                    </div>

                    <!-- 10 -->
                    <div class="informantDetails">
                        <h2>Informant's Details</h2>
                    </div>

                    <!-- 11 -->
                    <div>
                        <label for="">Name:
                            <input type="text" name="informant" value="User Logged In">
                        </label>
                    </div>

                    <!-- 12 -->
                    <!-- <div>
                        <label for="">Relation:
                            <input type="text" name="informant_relation" required>
                        </label>
                    </div> -->
                </div>

                <div class="appointmentDetails">
                    <div>
                        <h2>Appointment Details</h2>
                    </div>

                    <div class="margin-t-10">
                        <label for="">
                            Type of Client:
                            <select name="old_new" class="old_new clientSelection" id="clientSelect" data-consult-type="consultationSelect2" required>
                                <option value="" hidden disabled selected>--- Select Option ---</option>
                                <option value="New">New</option>
                                <option value="Old">Old</option>
                            </select>
                        </label>
                    </div>
                         
                    <div>
                        <label for="">
                            Type of Consultation:
                            <select name="consultation" id="consultationSelect2" required>
                                <option value="" hidden disabled selected>--- Select Option ---</option>
                                <option value="Face to Face">Face to Face</option>
                                <option value="Teleconsultation">Teleconsultation</option>
                            </select>
                        </label>
                    </div>

                    <div class="calendar">
                        <div class="calendar-date">
                            <label for="dateSched2">Date Schedule:</label>
                            <span class="calendar-flex">
                                <span class="datePicker btn-blue" data-sched-output="dateSched2">Calendar</span>
                                <input type="date" id="dateSched2" class="date" name="date_sched" readonly required>
                            </span>
                        </div>

                        <?php include('includes/calendarTable_modal.php'); ?>
                    </div>

                    <!-- <div class="complaint">
                        <h2>Complaint</h2>
                    </div> -->

                    <div class="input margin-t-20">
                        <div><label for="">Ano ang ipapakunsulta?</label></div>

                        <!-- Trigger Button -->
                        <button type="button" data-modal-target="complaintModal3" class="btn border width-100">--- Select Option ---</button>

                        <!-- Modal Container -->
                        <div id="complaintModal3" class="complaintShow">
                            <div class="modal-content" style="width: 400px; margin: 10% auto; position: relative;">
                                <div class="checkbox-group">
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

                    <div class="input">
                        <label for="" class="">Magbigay ng maikling paglalarawan tungkol sa sakit:</label>
                        <textarea rows="5" name="history" required></textarea>
                    </div>

                    <div id="referralContent">
                        <label for="" class="">Referral Source: </label>
                        <select name="refer_from" id="consultReferFrom" class="width-100 center-text">
                            <option value="N/A" hidden disabled selected>--- Select Option ---</option>
                            
                            <?php
                                $sql = "SELECT deptid, deptname FROM departments WHERE deptlocation = 'Medical Service' AND deptstat = 0";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                                        echo "<option value='" . $row['deptid'] . "'>" . htmlspecialchars($row['deptname']) . "</option>";
                                    }
                                } else {
                                    echo "<option disabled>No data found</option>";
                                }

                                // $conn->close();
                            ?>
                        </select>
                    </div>

                    <input type="hidden" name="typeofappoint" value="Referral">

                    <div class="submit">
                        <input type="submit" name="save_btn" value="Submit" class="btn btn-blue">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="modal">
        <div class="modal-con-confirmation">
            <h3>Confirm Submission</h3>
            <p>Are you sure you want to submit this form?</p>
            <div class="modal-buttons">
                <button class="btn btn-red" onclick="closeModal()">Cancel</button>
                <button class="btn btn-blue" onclick="submitForm()">Yes</button>
            </div>
        </div>
    </div>

    <script src="js/mainScript.js"></script>
    <script src="js/functions.js"></script>
    <script src="js/calendar_booking.js"></script>


    <script>
        function showModal() {
            document.getElementById('confirmationModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('confirmationModal').style.display = 'none';
        }

        function submitForm() {
            // Create a hidden input with the button name
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'save_btn';
            hiddenInput.value = '1';
            document.querySelector('form').appendChild(hiddenInput);
            
            // Submit the form
            document.querySelector('form').submit();
        }

        // Prevent form submission on button click
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            showModal();
        });
    </script>
</body>
</html>