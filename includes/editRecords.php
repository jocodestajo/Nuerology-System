<?php
?>    
    <!-- Modal (hidden by default) -->
    <div id="myModal" class="viewRecordsModal">
        <div class="modal-content">
            <!-- <a href="javascript:void(0);" class="cancelBtn close-btn close-btn-desktop">Close &times;</a> -->
            <span class="cancelBtn close-btn close-btn-desktop">&times;</span>

            <div class="edit-content">
                <div class="form-content">
                    <form action="api/post/updateData.php" method="post" class="box">

                        <!-- div.box Child element 1 / hidden -->
                         <div class="input appointment-type">
                             <label>Type of appointment:</label>
                             <select id="view_appointment" name="view_typeofappoint" class="appointment">
                                 <option class="select" hidden disabled selected>---Select Option---</option>
                                 <option value="SMS">SMS</option>
                                 <option value="Recveive Call">Call</option>
                                 <option value="Online">Online</option>
                                 <option value="Walk-In">Walk-in</option>
                                 <option value="Follow Up">Follow up</option>
                                 <option value="Referral">Referral</option>
                             </select>
                         </div>

                        <!-- div.box Child element 2 -->
                        <div class="input referal" id="viewReferal">
                            <div id="viewReferalContent">
                                <label for="referal" >Referral Source:</label>
                                <input type="text"  name="view_referal">
                            </div>
                        </div>

                        <!-- div.box Child element 3 -->
                        <div class="input">
                            <label for="hrn">HRN:</label>
                            <input type="text" name="view_hrn" class="hrn" id="view-hrn">
                        </div>

                        <!-- div.box Child element 4 -->
                        <div class="input">
                            <label for="name">Name:</label>
                            <input type="text" name="view_name" class="name" id="view-name">
                        </div>
                        
                        <!-- div.box Child element 5 -->
                        <div class="input">
                            <label for="birthday" class="b_day">Birthdate:</label>
                            <input type="date"  name="view_birthday" class="birthday birthdayInput" data-age-output="ageOuput3">
                        </div>
                        
                        <!-- div.box Child element 6 -->
                        <div class="input">
                            <label for="address" class="address">Address:</label>
                            <input type="text" name="view_address" class="address" id="view-address">
                        </div>
                        
                        <!-- div.box Child element 7 -->
                        <div class="input">
                            <label for="age">Age:</label>
                            <input type="text" name="view_age" class="age" id="ageOuput3">
                        </div>

                        <!-- div.box Child element 8 -->
                        <div class="input">
                            <label for="email" class="email">E-mail:</label>
                            <input type="text" name="view_email" class="email">
                        </div>
                        
                        <!-- div.box Child element 9 -->
                        <div class="input">
                            <label for="contact">Contact No:</label>
                            <input type="text"  name="view_contact" class="contact" id="view-contact">
                        </div>

                        <!-- div.box Child element 10 -->
                        <div class="input">
                            <label for="viber">Viber Account:</label>
                            <input type="text" name="view_viber" class="viber">
                        </div>
                        
                        <!-- div.box Child element 11 -->
                        <div class="input">
                            <label for="informant">Informant's Name:</label>
                            <input type="text"  name="view_informant" class="informant_name">
                        </div>
                        
                        <!-- div.box Child element 12 -->
                        <div class="input">
                            <label for="informant_relation">Relation to informant:</label>
                            <input type="text" name="view_informant_relation" class="rel_informant">
                        </div>
                        
                        <!-- div.box Child element 13 -->
                        <div class="input borderbox">
                            <div class="col-2">
                                <div>
                                    <div class="client-type">
                                        <label for="options">Type of Client:</label>
                                        <select name="view_old_new" class="old_new" id="view-clientSelect" required>
                                            <option class="select" hidden disabled selected>---Select Option---</option>
                                            <option value="New">New</option>
                                            <option value="Old">Old</option>
                                        </select>
                                    </div>

                                    <div>
                                        <h3>Type of Consultation:</h3>
                                        <div class="radio-div">
                                            <div>
                                                <input type="radio" name="consultation" id="faceToFace" value="Face to face">
                                                <label for="faceToFace">Face to face</label>
                                            </div>
                                            <div>
                                                <input type="radio" name="consultation" id="teleconsultation" value="Teleconsultation">
                                                <label for="teleconsultation">Teleconsultation</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="calendar">
                                    <div class="calendar-date">
                                        <label for="date">Date Schedule:</label>
                                        <input type="date" id="dateSched5" name="view_date_sched">
                                    </div>
                                    <!-- <?php include('calendarTable_modal.php'); ?> -->
                                </div>
                            </div>
                        </div>
                        
                        <!-- div.box Child element 14 -->
                        <div id="complaint">
                            <h3>Complaint</h3>
                        </div>

                        <!-- div.box Child element 15 -->
                        <div class="input">
                            <div><label for="">Ano ang ipapakunsulta?</label></div>

                            <!-- Trigger Button -->
                            <button type="button" data-modal-target="complaintModal_edit" class="btn border width-100">--- Select Option ---</button>

                            <!-- Modal Container -->
                            <div id="complaintModal_edit" class="complaintShow">
                                <div class="modal-content" style="width: 400px; margin: 10% auto; position: relative;">
                                    <div class="checkbox-complaint checkbox-group">
                                        <?php
                                            $sql1 = "SELECT id, name FROM neurology_classifications WHERE archived = 0 ORDER BY name ASC";
                                            $result1 = $conn->query($sql1);
                                            
                                            if ($result1->num_rows > 0) {
                                                while($row = $result1->fetch_assoc()) {
                                                    echo "<label><input type='checkbox' name='view_complaint[]' value='" . htmlspecialchars($row['name']) . "'> " . htmlspecialchars($row['name']) . "</label>";
                                                }
                                            } else {
                                                echo "<label><input type='checkbox' disabled> No classifications found</label>";
                                            }
                                        ?>
                                        <label><input type="checkbox" name="view_complaint[]" value="Others"> Others</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                         <!-- div.box Child element 16 -->
                         <div class="input">
                            <label for="history" class="">Brief history of illness</label>
                            <textarea rows="5"  name="view_history" class="form-control"></textarea>
                        </div>

                         <!-- div.box Child element 17 -->
                         <input type="hidden" name="record_id" >

                        <!-- div.box Child element 18 -->
                        <div class="div-btn-submit">
                            <a href="javascript:void(0);" class="cancelBtn btn btn-red">Cancel</a>
                            <button type="submit" name="update_record" class="btn btn-green">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php
?> 