<?php
?>    
    <!-- Modal (hidden by default) -->
    <div id="myModal" class="viewRecordsModal">
        <div class="modal-content">
            <a href="javascript:void(0);" class="cancelBtn close-btn close-btn-desktop">Close &times;</a>

            <div class="edit-content">
                <div class="form-content">
                    <form action="api/post/updateData.php" method="post" class="box">

                        <!-- div.box Child element 1 / hidden -->
                         <div class="input appointment-type">
                             <label>Type of appointment:</label>
                             <select id="view_appointment" name="typeofappoint" class="appointment">
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
                                <input type="text"  name="referal">
                            </div>
                        </div>

                        <!-- div.box Child element 3 -->
                        <div class="input">
                            <label for="hrn">HRN:</label>
                            <input type="text" name="hrn" class="hrn" id="view-hrn">
                        </div>

                        <!-- div.box Child element 4 -->
                        <div class="input">
                            <label for="name">Name:</label>
                            <input type="text" name="name" class="name" id="view-name">
                        </div>
                        
                        <!-- div.box Child element 5 -->
                        <div class="input">
                            <label for="birthday" class="b_day">Birthdate:</label>
                            <input type="date"  name="birthday" class="birthday birthdayInput" data-age-output="ageOuput3">
                        </div>
                        
                        <!-- div.box Child element 6 -->
                        <div class="input">
                            <label for="address" class="address">Address:</label>
                            <input type="text" name="address" class="address" id="view-address">
                        </div>
                        
                        <!-- div.box Child element 7 -->
                        <div class="input">
                            <label for="age">Age:</label>
                            <input type="text" name="age" class="age" id="ageOuput3">
                        </div>

                        <!-- div.box Child element 8 -->
                        <div class="input">
                            <label for="email" class="email">E-mail:</label>
                            <input type="text" name="email" class="email">
                        </div>
                        
                        <!-- div.box Child element 9 -->
                        <div class="input">
                            <label for="contact">Contact No:</label>
                            <input type="text"  name="contact" class="contact" id="view-contact">
                        </div>

                        <!-- div.box Child element 10 -->
                        <div class="input">
                            <label for="viber">Viber Account:</label>
                            <input type="text" name="viber" class="viber">
                        </div>
                        
                        <!-- div.box Child element 11 -->
                        <div class="input">
                            <label for="informant">Informant's Name:</label>
                            <input type="text"  name="informant" class="informant_name">
                        </div>
                        
                        <!-- div.box Child element 12 -->
                        <div class="input">
                            <label for="informant_relation">Relation to informant:</label>
                            <input type="text" name="informant_relation" class="rel_informant">
                        </div>
                        
                        <!-- div.box Child element 13 -->
                        <div class="input borderbox">
                            <div class="col-2">
                                <div>
                                    <div class="client-type">
                                        <label for="options">Type of Client:</label>
                                        <select name="old_new" class="old_new" id="view-clientSelect" required>
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
                            <select name="complaint" class="options">
                                <option value="" hidden></option>
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
                            <label for="history" class="">Brief history of illness</label>
                            <textarea rows="5"  name="history" class="form-control"></textarea>
                        </div>

                         <!-- div.box Child element 17 -->


                        <!-- div.box Child element 18 -->
                        <input type="hidden" name="record_id" >

                        <!-- div.box Child element 19 -->
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