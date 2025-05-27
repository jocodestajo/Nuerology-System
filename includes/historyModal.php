<?php
// The modal will be included in the main page
// Data will be loaded via AJAX when the modal is opened
?>

<div id="historyModal" class="modal">
    <div class="modal-content" style="width: 80%; max-width: 1000px;">
        <div class="modal-header">
            <span class="close-btn">&times;</span>
            <h2>Patient History</h2>
        </div>
        <div class="modal-body">
            <form id="historyForm" action="api/post/updateHistory.php" method="POST">
                <input type="hidden" name="record_id" value="">
                
                <!-- Personal Information Section -->
                <div class="section">
                    <h3>Personal Information</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Name:</label>
                            <input type="text" name="name" class="width-100" required>
                        </div>

                        <div class="form-group">
                            <label>HRN:</label>
                            <input type="text" name="hrn" class="width-100" readonly>
                        </div>

                        <div class="form-group">
                            <label>Birthdate:</label>
                            <input type="date" name="birthday" class="width-100" required>
                        </div>

                        <div class="form-group">
                            <label>Age:</label>
                            <input type="text" name="age" class="width-100" readonly>
                        </div>

                        <div class="form-group">
                            <label>Address:</label>
                            <input type="text" name="address" class="width-100">
                        </div>

                        <div class="form-group">
                            <label>Contact:</label>
                            <input type="text" name="contact" class="width-100" required>
                        </div>

                        <div class="form-group">
                            <label>Email:</label>
                            <input type="email" name="email" class="width-100">
                        </div>

                        <div class="form-group">
                            <label>Viber:</label>
                            <input type="text" name="viber" class="width-100">
                        </div>
                    </div>
                </div>

                <!-- Medical Information Section -->
                <div class="section">
                    <h3>Medical Information</h3>
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label>Complaint:</label>
                            <textarea name="complaint" rows="3" class="width-100" required></textarea>
                        </div>

                        <div class="form-group full-width">
                            <label>History of Illness:</label>
                            <textarea name="history" rows="3" class="width-100" required></textarea>
                        </div>

                        <div class="form-group full-width">
                            <label>Vital Signs:</label>
                            <div class="vital-signs-grid">
                                <div>
                                    <label>Blood Pressure:</label>
                                    <input type="text" name="blood_pressure" class="width-100">
                                </div>
                                <div>
                                    <label>Temperature:</label>
                                    <input type="text" name="temperature" class="width-100">
                                </div>
                                <div>
                                    <label>Pulse Rate:</label>
                                    <input type="text" name="heart_rate" class="width-100">
                                </div>
                                <div>
                                    <label>Respiratory Rate:</label>
                                    <input type="text" name="respiratory_rate" class="width-100">
                                </div>
                                <div>
                                    <label>Oxygen Saturation:</label>
                                    <input type="text" name="oxygen_saturation" class="width-100">
                                </div>
                                <div>
                                    <label>Height:</label>
                                    <input type="text" name="height" class="width-100">
                                </div>
                                <div>
                                    <label>Weight:</label>
                                    <input type="text" name="weight" class="width-100">
                                </div>
                                <div>
                                    <label>Notes:</label>
                                    <textarea name="notes" rows="1" class="width-100"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group full-width">
                            <label>Consultations:</label>
                            <div class="vital-signs-grid">
                                <div class="form-group">
                                    <label>RX / MC:</label>
                                    <select name="rx_mc" class="width-100" required>
                                        <option value="MC">MC</option>
                                        <option value="RX">RX</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <h3>Classification</h3>
                                    <select name="classification" id="consultClassification" class="center-text">
                                        <option value="" hidden disabled selected>--- Select Option ---</option>
                                        
                                        <?php
                                            $sql1 = "SELECT id, name FROM neurology_classifications WHERE archived = 0";
                                            $result1 = $conn->query($sql1);
                                            
                                            if ($result1->num_rows > 0) {
                                                while($row = $result1->fetch_assoc()) {
                                                    echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['name']) . "</option>";
                                                }
                                            } else {
                                                echo "<option disabled>No classifications found</option>";
                                            }
                                        ?>
                                        <option value="Other">Other</option>

                                    </select>
                                </div>
                                <div>
                                    <label>Diagnosis:</label>
                                    <textarea rows="3" name="diagnosis" class="width-100"></textarea>
                                </div>
                                <div>
                                    <label>Medications:</label>
                                    <textarea rows="3" name="medication" class="width-100"></textarea>
                                </div>
                                <div>
                                    <label>Remarks:</label>
                                    <textarea name="remarks" class="width-100"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Consultation Details Section -->
                <div class="section">
                    <h3>Consultation Details</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Type of Appointment:</label>
                            <select name="typeofappoint" class="width-100" required>
                                <option value="Walk-In">Walk-in</option>
                                <option value="SMS">SMS</option>
                                <option value="Receive Call">Call</option>
                                <option value="Online">Online</option>
                                <option value="Follow Up">Follow up</option>
                                <option value="Referral">Referral</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Consultation Type:</label>
                            <select name="consultation" class="width-100" required>
                                <option value="Face to face">Face to face</option>
                                <option value="Teleconsultation">Teleconsultation</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Date Schedule:</label>
                            <input type="date" name="date_sched" class="width-100" required>
                        </div>

                        <div class="form-group">
                            <label>Status:</label>
                            <select name="status" class="width-100" required>
                                <option value="processed">Processed</option>
                                <option value="follow up">Follow Up</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Turnaround Time VS Start:</label>
                            <input type="time" name="turnaround_vs_start" class="width-100">
                        </div>

                        <div class="form-group">
                            <label>Turnaround Time VS End:</label>
                            <input type="time" name="turnaround_vs_end" class="width-100">
                        </div>

                        <div class="form-group">
                            <label>Turnaround Time Consultation Start:</label>
                            <input type="time" name="turnaround_consult_start" class="width-100">
                        </div>

                        <div class="form-group">
                            <label>Turnaround Time Consultation End:</label>
                            <input type="time" name="turnaround_consult_end" class="width-100">
                        </div>

                        <div class="form-group">
                            <label>Turnaround Time Briefing Start:</label>
                            <input type="time" name="turnaround_briefing_start" class="width-100">
                        </div>

                        <div class="form-group">
                            <label>Turnaround Time Briefing End:</label>
                            <input type="time" name="turnaround_briefing_end" class="width-100">
                        </div>

                        <div class="form-group">
                            <label>Consultant I:</label>
                            <input type="text" name="consultant_1" class="width-100">
                        </div>

                        <div class="form-group">
                            <label>Consultant II:</label>
                            <input type="text" name="consultant_2" class="width-100">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-green">Save Changes</button>
                    <button type="button" class="btn btn-red" onclick="closeHistoryModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    overflow-y: auto;
}

.modal-content {
    margin: 2.5% auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
}

.modal-header {
    margin-bottom: 20px;
}

.modal-header > h2,
.modal-header > span {
    font-size: 1.5rem;
    font-weight: bold;
}

.section {
    margin-bottom: 2rem;
    padding: 1rem;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.section h3 {
    font-size: 1.2rem;
    font-weight: bold;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.vital-signs-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.modal-footer {
    margin-top: 2rem;
    text-align: right;
}
</style> 