<?php
    session_start();
    require 'config/dbcon.php';

    // Require appointment login before accessing this page
    if (!isset($_SESSION['appointment_auth'])) {
        header("Location: appointment_login.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neurology Online Schedule</title>
    <link rel="icon" href="img/MMWGH_Logo.png" type="images/x-icon">
    <link rel="stylesheet" href="css/mainStyle.css">
    <link rel="stylesheet" href="css/general.css">
    <link rel="stylesheet" href="css/appointment_form.css">
    <link rel="stylesheet" href="css/modals.css">
</head>
<body>

    <div class="header-1">
        <div class="logo">
            <img src="img/MMWGH_logo.png" alt="mmwgh logo">
            <h2>MMWGH - Neurology</h2>
        </div>
        <div class="navbar">
            <i class="menu-icon" id="menu-toggle">&#9776;</i>
            <div class="nav-list">
                <a href="online_appointment.php" class="btn-navbar">Home</a>
                <a href="#" class="btn-navbar">History</a>
                <a href="profile-users.php" class="btn-navbar">Profile</a>
                <a href="#" id="logout-link" class="btn-navbar">Logout</a>
            </div>
        </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div id="logoutConfirmModal" class="confirmModal" style="display:none;">
        <div class="modal-cancelContent">
            <span class="close" id="closeLogoutModal">&times;</span>
            <p>Are you sure you want to logout?</p>
            <div>
                <button id="confirmLogout" class="btn btn-red">Yes</button>
                <button id="cancelLogout" class="btn btn-blue">No</button>
            </div>
        </div>
    </div>

    <?php include('includes/messages/message.php'); ?>

    <div class="cont">
        <div class="header space-between">
            <h1>Appointment Schedule</h1>
            <!-- <a href="" class="btn btn-grey border">Back</a> -->
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
                            <input type="date" name="birthday" class="birthdayInput" data-age-output="age3" required>
                        </label>
                    </div>
                        
                    <!-- 4 -->
                    <div>
                        <label for="">Age:
                            <input type="text" id="age3" name="age">
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
                    <div class="question-block margin-t-20">
                        <h2>May iba pa bang kasama ang pasyente?</h2>
                        <p><i>*Is there anyone else accompanying the patient?</i></p>
                        <fieldset style="border: none; padding: 0; margin: 0;">
                            <div class="radio-group">
                                <label for="informantQA-true-2">
                                    <input type="radio" name="informantQA" id="informantQA-true-2">
                                    Yes
                                </label>

                                <label for="informantQA-false-2">
                                    <input type="radio" name="informantQA" id="informantQA-false-2">
                                    No
                                </label>
                            </div>
                        </fieldset>
                    </div>

                    <div class="informant-details">
                        <div class="display-informant" style="display: none;">
                            <label for="">Name of Informant:
                                <input type="text" name="informant">
                            </label>
                        </div>

                        <div class="display-informant" style="display: none;">
                            <label for="">Relationship to patient:
                                <input type="text" name="informant_relation">
                            </label>
                        </div>
                    </div>
                </div>

                <div class="appointmentDetails">
                    <div>
                        <h2>Appointment Details</h2>
                    </div>

                    <div>
                        <label for="">
                            Type of Client:
                            <select name="old_new" class="old_new clientSelection" data-consult-type="consultationSelect1" required>
                            <option value="" hidden disabled selected>--- Select Option ---</option>
                            <option value="New">New</option>
                            <option value="Old">Old</option>
                            </select>
                        </label>
                        </div>

                        <div>
                        <label for="">
                            Type of Consultation:
                            <select name="consultation" class="consultationSelect" id="consultationSelect1" required>
                                <option value="" hidden disabled selected>--- Select Option ---</option>
                                <option value="Face to Face">Face to Face</option>
                                <option value="Teleconsultation">Teleconsultation</option>
                            </select>
                        </label>
                    </div>

                    <div class="calendar">
                        <div class="calendar-wrapper">
                            <div class="calendar-date">
                                <label for="dateSched2">Date Schedule:</label>
                                <span class="calendar-flex">
                                    <span class="appointment-calendar-trigger btn-blue datePicker" data-target="dateSched3">Calendar</span>
                                    <input type="date" id="dateSched3" class="date" name="date_sched" data-calendar-type="appointment" readonly required>
                                </span>
                            </div>

                            <?php include('includes/appointment_calendar_modal.php'); ?>
                        </div>
                    </div>

                    <div class="input margin-t-20">
                        <div><label for="">Ano ang ipapakunsulta?</label></div>

                        <!-- Trigger Button -->
                        <button type="button" data-modal-target="complaintModal2" class="btn border width-100">--- Select Option ---</button>

                        <!-- Modal Container -->
                        <div id="complaintModal2" class="complaintShow">
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

                    <div class="input">
                        <label for="" class="">Magbigay ng maikling paglalarawan tungkol sa sakit:</label>
                        <textarea rows="5" name="history" required></textarea>
                    </div>
                    
                    <input type="hidden" name="typeofappoint" value="Online">

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
            <p>Are you sure you want to submit this appointment?</p>
            <div class="modal-buttons">
                <button class="btn btn-red" onclick="closeModal()">Cancel</button>
                <button class="btn btn-blue" onclick="submitForm()">Yes</button>
            </div>
        </div>
    </div>

    <script src="js/mainScript.js"></script>
    <script src="js/functions.js"></script>
    <script src="js/calendar_appointment.js"></script>

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

        // Logout confirmation logic
        const logoutLink = document.getElementById('logout-link');
        const logoutModal = document.getElementById('logoutConfirmModal');
        const closeLogoutModal = document.getElementById('closeLogoutModal');
        const confirmLogout = document.getElementById('confirmLogout');
        const cancelLogout = document.getElementById('cancelLogout');

        logoutLink.addEventListener('click', function(e) {
            e.preventDefault();
            logoutModal.style.display = 'block';
        });

        closeLogoutModal.addEventListener('click', function() {
            logoutModal.style.display = 'none';
        });
        cancelLogout.addEventListener('click', function() {
            logoutModal.style.display = 'none';
        });
        confirmLogout.addEventListener('click', function() {
            window.location.href = 'appointment_login.php?logout=1';
        });
        window.addEventListener('click', function(event) {
            if (event.target === logoutModal) {
                logoutModal.style.display = 'none';
            }
        });
    </script>
</body>
</html>