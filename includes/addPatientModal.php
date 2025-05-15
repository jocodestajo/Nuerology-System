<!-- Add Patient Modal -->
<div id="addPatientModal" class="modal">
    <div class="modal-content" style="width: 80%; max-width: 800px; max-height: 90vh; overflow-y: auto;">
        <div class="modal-header">
            <h2 id="addPatientTitle">Add New Patient</h2>
            <span class="close-btn" id="closeAddPatientModal">&times;</span>
        </div>
        <div class="modal-body">
            <form action="api/post/createData.php" method="post" id="addPatientForm" autocomplete="off">
                <input type="hidden" id="consultation_type" name="consultation" value="">
                                
                <div class="input">
                    <label>Name: <i class="asterisk">*</i></label>
                    <input type="text" id="modal_name" name="name" class="name" placeholder="Search or type the name here..." autocomplete="off" required>
                    
                    <div id="modal_searchResult" style="display: none;">
                        <div id="modal_result"></div>
                    </div>
                </div>

                <div class="input">
                    <label>HRN:</label>
                    <input type="text" id="modal_hrn" name="hrn" class="hrn" readonly>
                </div>
                
                <div class="input">
                    <label for="" class="b_day">Birthdate: <i class="asterisk">*</i></label>
                    <input type="date" name="birthday" id="modal_birthday" class="birthdayInput" data-age-output="modal_age" required>
                </div>
                
                <div class="input">
                    <label for="" class="address">Address:</label>
                    <input type="text" id="modal_address" name="address" class="address">
                </div>
                
                <div class="input">
                    <label for="" class="age">Age:</label>
                    <input type="text" id="modal_age" name="age" class="age" readonly>
                </div>

                <div class="input">
                    <label for="" class="email">E-mail:</label>
                    <input type="email" name="email" class="email">
                </div>
                
                <div class="input">
                    <label for="" class="contact">Contact No: <i class="asterisk">*</i></label>
                    <input type="text" id="modal_contact" name="contact" class="contact" required>
                </div>

                <div class="input">
                    <label for="" class="viber">Viber Account:</label>
                    <input type="text" name="viber" class="viber">
                </div>
                
                <div class="input">
                    <label for="" class="informant_name">Informant's Name: <i class="asterisk">*</i></label>
                    <input type="text" name="informant" class="informant_name" required>
                </div>
                
                <div class="input">
                    <label for="" class="rel_informant">Relation to informant: <i class="asterisk">*</i></label>
                    <input type="text" name="informant_relation" class="rel_informant" required>
                </div>
                
                <div class="input">
                    <div class="client-type">
                        <label>Type of Client: <i class="asterisk">*</i></label>
                        <select name="old_new" class="old_new" required>
                            <option value="" class="selectDefault" hidden disabled selected>--- Select Option ---</option>
                            <option value="New">New</option>
                            <option value="Old">Old</option>
                        </select>
                    </div>
                </div>
                
                <div class="input">
                    <label>Type of appointment: <i class="asterisk">*</i></label>
                    <select id="modal_typeOfAppointment" name="typeofappoint" class="appointment" required>
                        <option class="select" value="" hidden disabled selected>--- Select Option ---</option>
                        <option value="SMS">SMS</option>
                        <option value="Receive Call">Call</option>
                        <option value="Online">Online</option>
                        <option value="Walk-In">Walk-in</option>
                        <option value="Follow Up">Follow up</option>
                        <option value="Referral">Referral</option>
                    </select>
                </div>
                
                <div class="input referal" id="modalReferral" style="display: none;">
                    <div id="modalReferralContent">
                        <label for="" class="">Referral Source:</label>
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
                            ?>
                        </select>
                    </div>
                </div>

                <div class="calendar">
                    <div class="calendar-date">
                        <label for="modal_dateSched">Date Schedule:</label>
                        <input type="date" id="modal_dateSched" class="date" name="date_sched" readonly>
                    </div>

                    <div class="calendar-btn">
                        <span class="datePicker btn btn-blue" data-sched-output="modal_dateSched">Calendar</span>
                    </div>
                </div>
                
                <div class="input margin-t-20">
                    <div><label for="">Ano ang ipapakunsulta?</label></div>

                    <!-- Trigger Button -->
                    <button type="button" data-modal-target="modalComplaint" class="btn border width-100">--- Select Option ---</button>

                    <!-- Modal Container -->
                    <div id="modalComplaint" class="complaintShow">
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
                    <label for="" class="">Magbigay ng maikling paglalarawan tungkol sa sakit: <i class="asterisk">*</i></label>
                    <textarea rows="5" name="history" required></textarea>
                </div>

                <div class="div-btn-submit">
                    <button type="button" id="cancelAddPatient" class="btn btn-clearData">Cancel</button>
                    <button type="submit" name="save_btn" class="btn btn-green">Save</button>
                </div>
            </form>
        </div>
    </div>
</div> 