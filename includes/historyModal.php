<?php
require_once '../config/dbcon.php';

// Get the record ID from the URL parameter
$record_id = isset($_GET['id']) ? $_GET['id'] : null;

// Fetch patient data
$query = "SELECT r.*, c.* 
          FROM neurology_records r 
          INNER JOIN neurology_consultations c ON r.id = c.record_id 
          WHERE r.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $record_id);
$stmt->execute();
$result = $stmt->get_result();
$patient = $result->fetch_assoc();
?>

<div id="historyModal" class="modal">
    <div class="modal-content" style="width: 80%; max-width: 1000px;">
        <div class="modal-header">
            <span class="close-btn">&times;</span>
            <h2>Patient History</h2>
        </div>
        <div class="modal-body">
            <form id="historyForm" action="api/post/updateHistory.php" method="POST">
                <input type="hidden" name="record_id" value="<?php echo $record_id; ?>">
                
                <!-- Personal Information Section -->
                <div class="section">
                    <h3>Personal Information</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>HRN:</label>
                            <input type="text" name="hrn" value="<?php echo $patient['hrn']; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Name:</label>
                            <input type="text" name="name" value="<?php echo $patient['name']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Birthdate:</label>
                            <input type="date" name="birthday" value="<?php echo $patient['birthday']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Age:</label>
                            <input type="text" name="age" value="<?php echo $patient['age']; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Address:</label>
                            <input type="text" name="address" value="<?php echo $patient['address']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Contact:</label>
                            <input type="text" name="contact" value="<?php echo $patient['contact']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Email:</label>
                            <input type="email" name="email" value="<?php echo $patient['email']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Viber:</label>
                            <input type="text" name="viber" value="<?php echo $patient['viber']; ?>">
                        </div>
                    </div>
                </div>

                <!-- Consultation Details Section -->
                <div class="section">
                    <h3>Consultation Details</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Type of Appointment:</label>
                            <select name="typeofappoint" required>
                                <option value="Walk-In" <?php echo ($patient['typeofappoint'] == 'Walk-In') ? 'selected' : ''; ?>>Walk-in</option>
                                <option value="SMS" <?php echo ($patient['typeofappoint'] == 'SMS') ? 'selected' : ''; ?>>SMS</option>
                                <option value="Receive Call" <?php echo ($patient['typeofappoint'] == 'Receive Call') ? 'selected' : ''; ?>>Call</option>
                                <option value="Online" <?php echo ($patient['typeofappoint'] == 'Online') ? 'selected' : ''; ?>>Online</option>
                                <option value="Follow Up" <?php echo ($patient['typeofappoint'] == 'Follow Up') ? 'selected' : ''; ?>>Follow up</option>
                                <option value="Referral" <?php echo ($patient['typeofappoint'] == 'Referral') ? 'selected' : ''; ?>>Referral</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Consultation Type:</label>
                            <select name="consultation" required>
                                <option value="Face to face" <?php echo ($patient['consultation'] == 'Face to face') ? 'selected' : ''; ?>>Face to face</option>
                                <option value="Teleconsult" <?php echo ($patient['consultation'] == 'Teleconsult') ? 'selected' : ''; ?>>Teleconsult</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Date Schedule:</label>
                            <input type="date" name="date_sched" value="<?php echo $patient['date_sched']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Status:</label>
                            <select name="status" required>
                                <option value="processed" <?php echo ($patient['status'] == 'processed') ? 'selected' : ''; ?>>Processed</option>
                                <option value="follow up" <?php echo ($patient['status'] == 'follow up') ? 'selected' : ''; ?>>Follow Up</option>
                                <option value="cancelled" <?php echo ($patient['status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Medical Information Section -->
                <div class="section">
                    <h3>Medical Information</h3>
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label>Complaint:</label>
                            <textarea name="complaint" rows="3" required><?php echo $patient['complaint']; ?></textarea>
                        </div>
                        <div class="form-group full-width">
                            <label>History of Present Illness:</label>
                            <textarea name="history" rows="3" required><?php echo $patient['history']; ?></textarea>
                        </div>
                        <div class="form-group full-width">
                            <label>Vital Signs:</label>
                            <div class="vital-signs-grid">
                                <div>
                                    <label>Blood Pressure:</label>
                                    <input type="text" name="blood_pressure" value="<?php echo $patient['blood_pressure']; ?>">
                                </div>
                                <div>
                                    <label>Temperature:</label>
                                    <input type="text" name="temperature" value="<?php echo $patient['temperature']; ?>">
                                </div>
                                <div>
                                    <label>Pulse Rate:</label>
                                    <input type="text" name="pulse_rate" value="<?php echo $patient['pulse_rate']; ?>">
                                </div>
                                <div>
                                    <label>Respiratory Rate:</label>
                                    <input type="text" name="respiratory_rate" value="<?php echo $patient['respiratory_rate']; ?>">
                                </div>
                            </div>
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
.section {
    margin-bottom: 2rem;
    padding: 1rem;
    border: 1px solid #ddd;
    border-radius: 5px;
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

label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: bold;
}

input, select, textarea {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 4px;
}

textarea {
    resize: vertical;
}

.modal-footer {
    margin-top: 2rem;
    text-align: right;
}

.btn {
    padding: 0.5rem 1rem;
    margin-left: 1rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.btn-green {
    background-color: #4CAF50;
    color: white;
}

.btn-red {
    background-color: #f44336;
    color: white;
}
</style> 