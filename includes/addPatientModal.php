<!-- Add Patient Modal -->
<div id="addPatientModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="addPatientTitle">Add New Patient</h2>
            <span class="close-btn" id="closeAddPatientModal">&times;</span>
        </div>
        <div class="modal-body">
            <form action="api/post/createData.php" method="post" id="addPatientForm" autocomplete="off">
                             
                <!-- 1 -->
                <div class="input">
                    <label>Name:</label>
                    <input type="text" id="modal_name" name="name" class="name width-100" placeholder="Search or type the name here..." autocomplete="off" required>
                    
                    <div id="modal_searchResult" style="display: none;">
                        <div id="modal_result"></div>
                    </div>
                </div>

                <!-- 2 -->
                <div class="input">
                    <label>HRN:</label>
                    <input type="text" id="modal_hrn" name="hrn" class="hrn width-100" readonly>
                </div>
                
                <!-- 3 -->
                <div class="input">
                    <label for="" class="b_day">Birthdate:</label>
                    <input type="date" name="birthday" id="modal_birthday" class="birthdayInput width-100" data-age-output="modal_age" required>
                </div>
                
                <!-- 4 -->
                <div class="input">
                    <label for="" class="age">Age:</label>
                    <input type="text" id="modal_age" name="age" class="age width-100" readonly>
                </div>
                
                <!-- 5 -->
                <div class="input">
                    <label for="" class="email">E-mail:</label>
                    <input type="email" name="email" class="email width-100">
                </div>
                
                <!-- 6 -->
                <div class="input">
                    <label for="" class="contact">Contact No:</label>
                    <input type="text" id="modal_contact" name="contact" class="contact width-100" required>
                </div>
                
                <!-- 7 -->
                <div class="input">
                    <label for="" class="viber">Viber Account:</label>
                    <input type="text" name="viber" class="viber width-100">
                </div>
                
                <!-- 8 -->
                <div class="input">
                    <label for="" class="address">Address:</label>
                    <input type="text" id="modal_address" name="address" class="address width-100">
                </div>
                
                <!-- 9 -->
                <div class="question-block margin-t-20">
                    <label>May iba pa bang kasama ang pasyente?</label>
                    <p><i>*Is there anyone else accompanying the patient?</i></p>
                    <fieldset style="border: none; padding: 0; margin: 0;">
                        <div class="radio-group">
                            <label for="modal_informantQA-true">
                                <input type="radio" name="informantQA" id="modal_informantQA-true" value="yes">
                                Yes
                            </label>

                            <label for="modal_informantQA-false">
                                <input type="radio" name="informantQA" id="modal_informantQA-false" value="no">
                                No
                            </label>
                        </div>
                    </fieldset>
                </div>
                
                <!-- 10 -->
                <div>
                    <div class="informant-details" style="display: none;">
                        <div class="input">
                            <label for="" class="informant_name">Informant's Name:</label>
                            <input type="text" name="informant" class="informant_name width-100">
                        </div>

                        <div class="input">
                            <label for="" class="rel_informant">Relation to informant:</label>
                            <input type="text" name="informant_relation" class="rel_informant width-100">
                        </div>
                    </div>
                </div>

                <!-- 11 -->
                <div class="input margin-t-20">
                    <label>Type of appointment:</label>
                    <select id="modal_typeOfAppointment" name="typeofappoint" class="appointment width-100" required>
                        <option class="select" value="" hidden disabled selected>--- Select Option ---</option>
                        <option value="SMS">SMS</option>
                        <option value="Receive Call">Call</option>
                        <option value="Online">Online</option>
                        <option value="Walk-In">Walk-in</option>
                        <option value="Follow Up">Follow up</option>
                        <option value="Referral">Referral</option>
                    </select>
                </div>
                
                <!-- 12 -->
                <div class="input margin-t-20">
                    <div class="client-type">
                        <label>Type of Client:</label>
                        <select name="old_new" class="old_new width-100" required>
                            <option value="" class="selectDefault" hidden disabled selected>--- Select Option ---</option>
                            <option value="New">New</option>
                            <option value="Old">Old</option>
                        </select>
                    </div>
                </div>

                <!-- 13 -->
                <div class="margin-t-20">
                    <label for="">
                        Type of Consultation:
                        <select name="consultation" id="consultationSelect2" class="width-100" required>
                            <option value="" hidden disabled selected>--- Select Option ---</option>
                            <option value="Face to Face">Face to Face</option>
                            <option value="Teleconsultation">Teleconsultation</option>
                        </select>
                    </label>
                </div>
                
                <!-- 14 -->
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

                            <option value="Others">Others</option>
                        </select>
                    </div>
                </div>

                <!-- 15 -->
                <div class="calendar">
                    <label for="modal_dateSched">Date Schedule:</label>
                    <div class="calendar-date flex" style="margin-top: 0;">
                        <!-- <button class="datePicker btn btn-blue width-100" data-sched-output="modal_dateSched">Calendar</button> -->
                        <input type="date" id="modal_dateSched" class="date width-100" name="date_sched">
                    </div>
                </div>
            
                <!-- 16 -->
                <div class="input margin-t-20">
                    <div><label for="">Ano ang ipapakunsulta?</label></div>

                    <!-- Trigger Button -->
                    <button type="button" id="complaintBtn" data-modal-target="complaintModal3" class="btn border width-100">--- Select Option ---</button>

                    <!-- Modal Container -->
                    <div id="complaintModal3" class="complaintShow">
                        <div class="modal-content">
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
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- 17 -->
                <div class="input">
                    <label for="" class="">Magbigay ng maikling paglalarawan tungkol sa sakit:</label>
                    <textarea rows="5" name="history" class="width-100" required></textarea>
                </div>

                <!-- 18 -->
                <input type="hidden" id="consultation_type" name="consultation" value="">

                <!-- 19 -->
                <div class="div-btn-submit">
                    <button type="button" id="cancelAddPatient" class="btn btn-clearData">Cancel</button>
                    <button type="submit" name="save_btn" class="btn btn-green">Save</button>
                </div>
            </form>
        </div>
    </div>
</div> 