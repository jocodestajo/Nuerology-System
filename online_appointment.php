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
    <!-- <link rel="stylesheet" href="css/mediaQuery.css"> -->
</head>
<body>
    <div class="cont">
        <div class="header space-between">
            <h1>Appointment Schedule</h1>
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
                            <input type="text" name="name">
                        </label>
                    </div>

                    <!-- 3 -->
                    <div>
                        <label for="">Birthday:
                            <input type="date" name="birthday" class="birthdayInput" data-age-output="age3">
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
                            <input type="text" name="address">
                        </label>
                    </div>

                    <!-- 6 -->
                    <div>
                        <h2 class="contactDetails">Contact Details</h2>
                    </div>

                    <!-- 7 -->
                    <div>
                        <label for="">Phone:
                            <input type="text" name="contact">
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
                            <input type="text" name="email">
                        </label>
                    </div>

                    <!-- 10 -->
                    <div class="informantDetails">
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

                <div class="appointmentDetails">
                    <div>
                        <h2>Appointment Details</h2>
                    </div>

                    <div>
                        <label for="">
                            Type of Client:
                            <select name="old_new" class="old_new" id="clientSelect3" required>
                                <option value="" hidden disabled selected>--- Select Option ---</option>
                                <option value="New">New</option>
                                <option value="Old">Old</option>
                            </select>
                        </label>
                    </div>
                         
                    <div>
                        <label for="">
                            Type of Consultation:
                            <select name="consultation" id="">
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
                                <span class="datePicker btn-blue" data-sched-output="dateSched3">Calendar</span>
                                <input type="date" id="dateSched3" class="date" name="date_sched" readonly>
                            </span>
                        </div>

                        <?php include('includes/calendarTable_modal.php'); ?>
                    </div>

                    <div class="complaint">
                        <h2>Complaint</h2>
                    </div>

                    <div class="input">
                        <label for="">Ano ang ipapakunsulta?</label>
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


    <script src="js/functions.js"></script>
    <script src="js/calendar_booking.js"></script>

</body>
</html>