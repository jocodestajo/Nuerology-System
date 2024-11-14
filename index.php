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
    <title>NEUROLOGY UNIT</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container">
            
        <div class="header">
            <div class="logo">
                <img src="img/MMWGH_logo.png" alt="mmwgh logo">
                <h2>Neurology Unit</h2>
            </div>
            <div class="navbar">
                <a href="#home" class="btn-navbar">Schedule</a>    
                <a href="#about" class="btn-navbar">Reports</a>
                <a href="#services" class="btn-navbar">Settings</a>
                <a href="#contact" class="btn-navbar">Logout</a>
            </div>
        </div>

        <div class="container-2">

            <?php include('includes/messages/message.php'); ?>
            <?php include('includes/editRecords.php'); ?>
                
            <!-- TAB 1 / INQUIRY -->
            <input type="radio" class="tabs_radio" name="tabs_example" id="tab1" checked >
            <label for="tab1" class="tabs_label">Inquiry</label>
            <div class="tabs_content">
                <div class="form-content">
                    <form action="api/post/createData.php" method="post" class="box">

                        <!-- div.box Child element 1 -->
                        <div class="currentdate">
                            <div>
                                <label for="" class="date">Date:</label> 
                                <input type="text" id="currentdate" name="date_request" class="datetime" value="<?php echo $currentDate; ?>" readonly>
                            </div>
                        </div>
                        
                        <!-- div.box Child element 2 -->
                        <div class="input appointment-type">
                            <label>Type of appointment:</label>
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
                            <input type="text" id="hrn" name="hrn" class="hrn" disabled>
                        </div>

                        <!-- div.box Child element 4 / NAME -->
                        <div class="input">
                            <label>Name:</label>
                            <input type="text" id="name-search" name="name" class="name" autocomplete="off">
                            <div id="result"></div>
                        </div>
                        
                        <!-- div.box Child element 5 -->
                        <div class="input">
                            <label for="" class="age">Age:</label>
                            <input type="text" id="age" name="age" class="age">
                        </div>
                        
                        <!-- div.box Child element 6 -->
                        <div class="input">
                            <label for="" class="address">Address:</label>
                            <input type="text" id="address" name="address" class="address">
                        </div>
                        
                        <!-- div.box Child element 7 -->
                        <div class="input">
                            <label for="" class="b_day">Birthdate:</label>
                            <input type="text" id="birthday" name="birthday" class="b_day" placeholder="mm/dd/yyyy">
                        </div>

                        <!-- div.box Child element 8 -->
                        <div class="input">
                            <label for="" class="email">E-mail:</label>
                            <input type="text" name="email" class="email">
                        </div>
                        
                        <!-- div.box Child element 9 -->
                        <div class="input">
                            <label for="" class="contact">Contact No:</label>
                            <input type="text" id="contact" name="contact" class="contact">
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
                                        <label>Type of Client:</label>
                                        <select name="old_new" class="old_new" id="clientSelect" required>
                                            <option value="" class="select" hidden disabled selected>--- Select Option ---</option>
                                            <option value="New Patient">New Patient</option>
                                            <option value="Old Patient">Old Patient</option>
                                        </select>
                                    </div>

                                    <div class="radio-div">
                                        <div>
                                            <input type="radio" name="consultation" id="f2f" value="Face to face">
                                            <label for="faceToFace">Face to face consult</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="consultation" id="teleconsult" value="Teleconsultation">
                                            <label for="teleconsultation">Teleconsultation</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="calendar">
                                    <div class="calendar-date">
                                        <label for="date">Date Schedule:</label>
                                        <input type="date" id="date" name="date_sched">
                                    </div>
                                    <div class="calendar-btn">
                                        <span class="btn btn-blue">Calendar</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- div.box Child element 14 -->
                        <div>
                            <h3>Complaint:</h3>
                        </div>

                        <!-- div.box Child element 15 -->
                        <div class="input">
                                <label for="q1">Ano ang ipapakunsulta?</label>
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
                            <label for="" class="">Brief history of illness</label>
                            <textarea rows="5" name="history" class="form-control"></textarea>
                        </div>

                        <!-- div.box Child element 18 -->
                        <div class="input referal">
                            <label for="" class="">Referal Source:</label>
                            <input type="text" name="referal">
                        </div>

                        <!-- div.box Child element 18 -->
                            <div class="div-btn-submit">
                            <button type="button" name="clear_data_btn" class="btn btn-red">Clear Data</button>
                            <button type="submit" name="save_btn" class="btn btn-green">Save</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TAB 2 / APPOINTMENT PENDINGS -->
            <input type="radio" class="tabs_radio" name="tabs_example" id="tab2" > 
            <label for="tab2" class="tabs_label">Pending Appointments</label>
            <div class="tabs_content">

                <table>
                    <thead>
                        <tr>
                            <th>HRN</th>
                            <th>Name</th>
                            <th>Type of Client</th>
                            <th>Consultation</th>
                            <th>Contact</th>
                            <th>Schedule</th>
                            <th>Complaint</th>
                            <th>Status</th>
                            <th>Action</th>
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
                                            <tr>

                                                <td><?= $records['hrn']; ?></td>
                                                <td><?= $records['name']; ?></td>
                                                <td><?= $records['old_new']; ?></td>
                                                <td><?= $records['consultation']; ?></td>
                                                <td><?= $records['contact']; ?></td>
                                                <td><?= $records['date_sched']; ?></td>
                                                <td><?= $records['complaint']; ?></td>
                                                <td><?= $records['status']; ?></td>
                                                <td class="action">
                                                
                                                    <form action="api/post/updateData.php" method="POST">
                                                        <button type="submit" name="approve_record" value="<?=$records['id'];?>" class="btn btn-green">Approve</button>
                                                    </form>

                                                    <a href="javascript:void(0);" class="btn btn-blue view-button" data-record-id="<?=$records['id'];?>">View</a>

                                                    <form action="api/post/deleteData.php" method="POST">
                                                        <button type="submit" name="delete_record" value="<?=$records['id'];?>" class="btn btn-red">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php
                                    }
                                }
                            else
                            {
                            echo"<H5>NO RECORD FOUND</H5>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            
            <!-- TAB 3 / FACE TO FACE CHECK-UP -->
            <input type="radio" class="tabs_radio" name="tabs_example" id="tab3">
            <label for="tab3" class="tabs_label">F2F</label>
            <div class="tabs_content">
                <table>
                    <thead>
                        <tr>
                            <th>HRN</th>
                            <th>Name</th>
                            <th>Type of Client</th>
                            <th>Consultation</th>
                            <th>Contact</th>
                            <th>Schedule</th>
                            <th>Complaint</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // calling data in db table
                            $query = "SELECT * FROM neurology_records WHERE status = 'approved' and consultation = 'face to face' ";


                            // Para mag work yung $query need niya tawagin yung db kaya may $query_run para ideclare both  $query and $con
                            $query_run = mysqli_query($conn, $query);

                            // condition if what data will be called
                            if(mysqli_num_rows($query_run) > 0)
                            {
                            foreach($query_run as $records)
                                {

                                    ?>
                                    <tr>
                                        <!--  -->
                                        <input type="hidden" name="patient_id" value="<?=$records['id'];?>">

                                        <td><?= $records['hrn']; ?></td>
                                        <td><?= $records['name']; ?></td>
                                        <td><?= $records['old_new']; ?></td>
                                        <td><?= $records['consultation']; ?></td>
                                        <td><?= $records['contact']; ?></td>
                                        <td><?= $records['date_sched']; ?></td>
                                        <td><?= $records['complaint']; ?></td>
                                        <td><?= $records['status']; ?></td>
                                        <td class="action">
                                            
                                            <form action="api/post/updateData.php" method="POST">
                                                <button type="submit" name="update_record" value="<?=$records['id'];?>" class="btn btn-green">Done</button>
                                            </form>

                                            <a href="javascript:void(0);" class="btn btn-blue view-button" data-record-id="<?=$records['id'];?>">View</a>

                                            <form action="api/post/deleteData.php" method="POST">
                                                <button type="submit" name="delete_record" value="<?=$records['id'];?>" class="btn btn-red">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            else
                            {
                            echo"<H5>NO RECORD FOUND</H5>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
                
            <!-- TAB 4 / TELECONSULTATION -->
            <input type="radio" class="tabs_radio" name="tabs_example" id="tab4">
            <label for="tab4" class="tabs_label">Teleconsultation</label>
            <div class="tabs_content">
                <table>
                    <thead>
                        <tr>
                            <th>HRN</th>
                            <th>Name</th>
                            <th>Type of Client</th>
                            <th>Consultation</th>
                            <th>Contact</th>
                            <th>Schedule</th>
                            <th>Complaint</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // calling data in db table
                            $query = "SELECT * FROM neurology_records WHERE status = 'approved' and consultation = 'teleconsultation' ";

                            // Para mag work yung $query need niya tawagin yung db kaya may $query_run para ideclare both  $query and $con
                            $query_run = mysqli_query($conn, $query);

                            // condition if what data will be called
                            if(mysqli_num_rows($query_run) > 0)
                            {
                            foreach($query_run as $records)
                                {

                                    ?>
                                    <tr>
                                        <input type="hidden" name="patient_id" value="<?=$records['id'];?>">

                                        <td><?= $records['hrn']; ?></td>
                                        <td><?= $records['name']; ?></td>
                                        <td><?= $records['old_new']; ?></td>
                                        <td><?= $records['consultation']; ?></td>
                                        <td><?= $records['contact']; ?></td>
                                        <td><?= $records['date_sched']; ?></td>
                                        <td><?= $records['complaint']; ?></td>
                                        <td><?= $records['status']; ?></td>
                                        <td class="action">
                                            <form action="api/post/updateData.php" method="POST">
                                                <button type="submit" name="update_record" value="<?=$records['id'];?>" class="btn btn-green">Done</button>
                                            </form>

                                            <a href="javascript:void(0);" class="btn btn-blue view-button" data-record-id="<?=$records['id'];?>">View</a>

                                            <form action="api/post/deleteData.php" method="POST">
                                                <button type="submit" name="delete_record" value="<?=$records['id'];?>" class="btn btn-red">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            else
                            {
                            echo"<H5>NO RECORD FOUND</H5>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- TAB 5 / EEG -->
            <input type="radio" class="tabs_radio" name="tabs_example" id="tab5">
            <label for="tab5" class="tabs_label">EEG</label>
            <div class="tabs_content">
                content5
            </div>

            <!-- TAB 6 / CALENDAR -->
            <input type="radio" class="tabs_radio" name="tabs_example" id="tab6" >
            <label for="tab6" class="tabs_label">Calendar</label>
            <div class="tabs_content">
                <div class="card">
                    <h1>Schedule</h1>
                    <div class="card-body">
                        <table>
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Sun</th>
                                    <th>Mon</th>
                                    <th>Tue</th>
                                    <th>Wed</th>
                                    <th>Thu</th>
                                    <th>Fri</th>
                                    <th>Sat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>8:00 AM</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>9:00 AM</td>
                                </tr>
                                <tr>
                                    <td>10:00 AM</td>
                                </tr>
                                <tr>
                                    <td>11:00 AM</td>
                                </tr>
                                <tr>
                                    <td>12:00 AM</td>
                                </tr>
                                <tr>
                                    <td>1:00 PM</td>
                                </tr>
                                <tr>
                                    <td>2:00 PM</td>
                                </tr>
                                <tr>
                                    <td>3:00 PM</td>
                                </tr>
                                <tr>
                                    <td>4:00 PM</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="container-3">
            
        </div> -->
    </div>

    <div class="footer">
        <h4>&copy; 2024 - MMWGH (IMISU)</h4>
    </div>

    <script src="scripts/mainScript.js"></script>
    <script src="scripts/modal.js"></script>
</body>
</html>