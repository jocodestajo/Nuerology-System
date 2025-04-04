<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation</title>
    <link rel="icon" href="img/MMWGH_Logo.png" type="images/x-icon">
    <link rel="stylesheet" href="css/mainStyle.css">
    <link rel="stylesheet" href="css/general.css">
    <link rel="stylesheet" href="css/appointment_form.css">
    <link rel="stylesheet" href="css/mediaQuery.css">
</head>
<style>
</style>
<body>

    <!-- HEADER -->
    <?php include('includes/header.php'); ?>


    <div class="container-3">
        <div class="margin-b-20">
            <!-- <h2 class="consult-h2-header">Consultation</h2> -->
            <a href="./index.php" class="btn btn-grey ">Back</a>
        </div>
        <form action="api/post/updateConsult.php" method="post" class="container3-Content">

            <div class="turnaroundTime">
                <div class="heading1">
                    <h2>Vital Sign</h2>
                    <h2>Doctor</h2>
                    <h2>Nurse</h2>
                </div>

                <div class="turnaroundContent">
                    <div class="nurseVs">
                        <span class="btn btn-green" id="startTimer1">Start</span>
                        <span class="btn" id="endTimer1">End</span>
                        <span class="timer1">00:00:00</span>
                    </div>
                    <div class="doctorConsult">
                        <span class="btn btn-green" id="startTimer2">Start</span>
                        <span class="btn" id="endTimer2">End</span>
                        <span class="timer2">00:00:00</span>
                    </div>
                    <div class="nurseFinal">
                        <span class="btn btn-green" id="startTimer3">Start</span>
                        <span class="btn" id="endTimer3">End</span>
                        <span class="timer3">00:00:00</span>
                    </div>
                </div>
            </div>

            <div class="consultants">
                <div class="heading11">
                    <h2>Consultant</h2>
                </div>
                <div class="pad-hor-20">
                    <label for="docName">Doctor:</label>
                    <select name="doctorName" id="docName" require>
                        <option value="">Doctor I</option>
                        <option value="">Doctor II</option>
                    </select>
                </div>
                <div class="pad-hor-20 pad-b-20">
                    <label for="nurseName">Nurse:</label>
                    <select name="nurseName" id="nurseName" require>
                        <option value="">Nurse I</option>
                        <option value="">Nurse II</option>
                    </select>
                </div>
            </div>
            
            <div id="personalInformation">

                <!-- 1 -->
                <div>
                    <h2>Personal Information</h2>
                </div>

                <!-- 2 -->
                <div>
                    <label for="">Name:
                        <input type="text" name="name">
                    </label>
                </div>

                <!-- 3 -->
                <div>
                    <label for="">Birthday:
                        <input type="date" name="birthday" class="birthdayInput" data-age-output="age2">
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
                        <input type="text" name="address">
                    </label>
                </div>

                <!-- 6 -->
                <div>
                    <h2 id="contactDetails">Contact Details</h2>
                </div>

                <!-- 7 -->
                <div>
                    <label for="">Phone:
                        <input type="text" name="contact">
                    </label>
                </div>

                <!-- 8 -->
                <div>
                    <label for="">Email:
                        <input type="text" name="email">
                    </label>
                </div>

                <!-- 9 -->
                <div>
                    <label for="">Viber:
                        <input type="text" name="viber">
                    </label>
                </div>

                <!-- 10 -->
                <div id="informantDetails">
                    <h2>Informant's Details</h2>
                </div>

                <!-- 11 -->
                <div>
                    <label for="">Name:
                        <input type="text" name="informant">
                    </label>
                </div>

                <!-- 12 -->
                <div>
                    <label for="">Relation:
                        <input type="text" name="informant_relation">
                    </label>
                </div>
            </div>

            <div id="consultation">
                <h2>Consultation</h2>
                <div>
                    <div id="consultTable">
                        <h3>Type of Consult</h3>

                        <div class="space-evenly">
                            <div class="border-right width-100 padding-20">
                                <div class="">
                                    <label for="consultNew">
                                        <input type="radio" value="New" id="consultNew" name="old_new">
                                        New
                                    </label>
                                </div>

                                <div class="">
                                    <label for="consultOld">
                                        <input type="radio" value="Old" id="consultOld" name="old_new">
                                        Old
                                    </label>
                                </div>
                            </div>

                            <div class="width-100 padding-20">
                                <div class="">
                                    <label for="consultFTF">
                                        <input type="radio" value="Face to face" id="consultFTF" name="consultation">
                                        Face to face
                                    </label>
                                </div>

                                <div class="">
                                    <label for="consultTelecon">
                                        <input type="radio" value="Teleconsult" id="consultTelecon" name="consultation">
                                        Teleconsult
                                    </label>
                                </div>
                            </div>

                            <div class="border-left width-100 padding-20">
                                <div class="">
                                    <label for="consultRX">
                                        <input type="radio" value="RX" id="consultRX" name="consultPurpose">
                                        RX
                                    </label>
                                </div>

                                <div class="">
                                    <label for="consultMC">
                                        <input type="radio" value="MC" id="consultMC" name="consultPurpose">
                                        MC
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3>Diagnosis</h3>
                            <textarea name="diagnosis" id=""></textarea>
                        </div>

                        <div class="space-evenly">
                            <div class="classification">
                                <h3>Classification</h3>
                                <select name="" id="consultClassification" class="padding-20 center-text">
                                    <option value=""></option>
                                </select>
                            </div>

                            <div class="consultMed flex-row">
                                <div class="width-100">
                                    <h3>Medication</h3>
                                    <input type="text" name="medication" class="width-100 padding-20 center-text">
                                </div>
                                <div class="width-20">
                                    <h3>QTY</h3>
                                    <input type="text" name="medQty" class="width-100 padding-20 center-text">
                                </div>
                            </div>
                        </div>

                        <div class="flex-row">
                            <div class="width-100 consult-referral">
                                <h3>Referral</h3>
                                <div class="text-left">
                                    <label for="consultReferFrom" class="rfl">
                                        Referred fro:
                                        <input type="text" name="refer_from" id="consultReferFrom" class="width-100 center-text">
                                    </label>
                                </div>
                                    
                                <div class="text-left">   
                                    <label for="consultReferTo" class="rfl">
                                        Referred to:
                                        <input type="text" name="refer_to" id="consultReferTo" class="width-100 center-text">
                                    </label>
                                </div>
                            </div>

                            <div class="width-100 consult-followUp">
                                <h3>Follow Up</h3>
                                <div>
                                    <label for="consultFollowUp">
                                        <input type="checkbox" name="follow_up" value="Follow Up" id="consultFollowUp">
                                        Follow Up
                                    </label>
                                </div>
                                <div class="calendar text-left">
                                    <label for="">
                                        Date:
                                        <!-- <input type="date" id="consultDate" name="date_sched" class="width-100 center-text"> -->
                                        <span class="calendar-flex">
                                            <span class="datePicker btn-blue" data-sched-output="dateSched4">Calendar</span>
                                            <input type="date" id="dateSched4" class="date center-text" name="date_sched" readonly>
                                        </span>
                                    </label>

                                    <?php include('includes/calendarTable_modal.php'); ?>

                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="record_id" >
        
                    <div id="div-consultSubmit">
                        <input type="submit" value="Save" name="save_record" id="consultSubmit" class="btn btn-green">
                    </div>
                    
                </div>
            </div>
        </form>
    </div>

    <!-- <script src="js/mainScript.js"></script> -->
    <script src="js/consultation.js"></script>
    <script src="js/functions.js"></script>
    <script src="js/calendar_booking.js"></script>
    <script>
        // Timer function with HH:MM:SS format using Date for elapsed time
        function startTimer(timerElement, startButton, endButton) {
            let startTime = 0;
            let interval;
            
            // Function to format the elapsed time into HH:MM:SS
            function formatTime(elapsedTime) {
                const hours = Math.floor(elapsedTime / 3600);
                const minutes = Math.floor((elapsedTime % 3600) / 60);
                const seconds = Math.floor(elapsedTime % 60);

                return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            }

            // Update the timer display
            function updateTimer() {
                const elapsedTime = (new Date() - startTime) / 1000; // Elapsed time in seconds
                timerElement.textContent = `${formatTime(elapsedTime)}`;
            }

            // Start the timer
            startButton.addEventListener('click', () => {
                startTime = new Date(); // Record the time when the start button is clicked
                interval = setInterval(updateTimer, 1000); // Update every second
                startButton.disabled = true; // Disable start button after it's clicked
            });

            // Stop the timer
            endButton.addEventListener('click', () => {
                clearInterval(interval); // Stop the timer
                startButton.disabled = false; // Enable start button after stopping
            });
        }

        // Apply the startTimer function to each timer
        startTimer(document.querySelector('.timer1'), document.querySelector('#startTimer1'), document.querySelector('#endTimer1'));
        startTimer(document.querySelector('.timer2'), document.querySelector('#startTimer2'), document.querySelector('#endTimer2'));
        startTimer(document.querySelector('.timer3'), document.querySelector('#startTimer3'), document.querySelector('#endTimer3'));
    </script>
</body>
</html>