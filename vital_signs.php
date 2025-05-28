<?php
session_start();
if (!isset($_SESSION['auth'])) {
    header("Location: login.php");
    exit();
}
require 'config/dbcon.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vital Signs</title>
    <link rel="icon" href="img/MMWGH_Logo.png" type="images/x-icon">
    <link rel="stylesheet" href="css/mainStyle.css">
    <link rel="stylesheet" href="css/general.css">
    <link rel="stylesheet" href="css/appointment_form.css">
    <link rel="stylesheet" href="css/mediaQuery.css">
    <link rel="stylesheet" href="css/modals.css">
    <style>
        .container-3 {
            max-width: 800px;
            margin: 80px auto 0 auto;
            padding: 2rem;
        }

        .patient-name {
            font-size: 1.8rem;
            font-weight: bold;
            margin: 2rem 0;
            color: var(--blue-color);
            text-align: center;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .patient-name input {
            width: 100%;
            text-align: center;
            border: none;
            background: transparent;
            font-size: inherit;
            font-weight: inherit;
            color: inherit;
        }

        #vitalSigns {
            width: 100%;
            margin: 0 auto;
        }

        #vitalSigns h2 {
            text-align: center;
            color: #495057;
            margin-bottom: 2rem;
        }

        .vital-signs-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin: 2rem 0;
        }

        .vital-signs-grid > div {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .vital-signs-grid label {
            display: block;
            margin-bottom: 0.5rem;
            color: #495057;
            font-weight: 500;
        }

        .vital-signs-grid input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1rem;
        }
        .vital-signs-grid textarea {
            width: 100%;
            padding: 5px;
        }

        .vital-signs-grid input:focus {
            border-color: var(--blue-color);
            outline: none;
            box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
        }

        .notes-section {
            margin-top: 2rem;
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .notes-section h3 {
            color: #495057;
            margin-bottom: 1rem;
            text-align: center;
        }

        .notes-section textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ced4da;
            border-radius: 4px;
            resize: vertical;
            min-height: 100px;
        }

        .notes-section textarea:focus {
            border-color: var(--blue-color);
            outline: none;
            box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
        }

        #div-vitalSignsSubmit {
            margin-top: 2rem;
            text-align: center;
            display: flex;
            justify-content: center;
            gap: 1.2rem;
        }

        #vitalSignsBack {
            padding: 0.5rem 2rem;
            font-size: 1.1rem;
        }

        #vitalSignsSubmit {
            padding: 0.5rem 2rem;
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <?php include('includes/header.php'); ?>

    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="modal confirmModal">
        <div class="modal-cancelContent">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Confirm Save</h3>
            <p>Are you sure you want to save these vital signs?</p>
            <div class="modal-buttons">
                <button class="btn btn-red" onclick="closeModal()">Cancel</button>
                <button class="btn btn-blue" onclick="submitForm()">Confirm</button>
            </div>
        </div>
    </div>

    <div class="container-3">
        
        <div class="patient-name">
            <input type="text" name="name" readonly>
        </div>
        
        <form action="api/post/updateVitalSigns.php" method="post">
            <div id="vitalSigns">
                <!-- <h2>Vital Signs</h2> -->
                
                <div class="vital-signs-grid">
                    <div>
                        <label for="">Blood Pressure: mmHg</label>
                        <input type="text" name="blood_pressure" placeholder="e.g., 120/80 mmHg" required>
                    </div>

                    <div>
                        <label for="">Temperature: °C</label>
                        <input type="text" name="temperature" placeholder="e.g., 37.0 °C" required>
                    </div>

                    <div>
                        <label for="">Heart Rate: bpm</label>
                        <input type="text" name="heart_rate" placeholder="e.g., 80 bpm" required>
                    </div>

                    <div>
                        <label for="">Respiratory Rate: cpm</label>
                        <input type="text" name="respiratory_rate" placeholder="e.g., 16 cpm" required>
                    </div>

                    <div>
                        <label for="">Height: cm</label>
                        <input type="text" name="height" placeholder="e.g., 170 cm">
                    </div>

                    <div>
                        <label for="">Oxygen Saturation: %</label>
                        <input type="text" name="oxygen_saturation" placeholder="e.g., 98%" required>
                    </div>

                    <div>
                        <label for="">Weight: kg</label>
                        <input type="text" name="weight" placeholder="e.g., 65 kg">
                    </div>

                    <div>
                        <label for="">Notes</label>
                        <textarea name="notes" rows="4" placeholder="Enter any additional notes about the patient's vital signs..."></textarea>
                    </div>
                    
                    <div>
                        <label for="">Start Time</label>
                        <input type="time" name="vs_start" step="1" required>
                    </div>
                    
                    <div>
                        <label for="">End Time</label>
                        <input type="time" name="vs_end" step="1" required>
                    </div>
                    
                </div>

                
                <input type="hidden" name="record_id">
                    
                <div id="div-vitalSignsSubmit">
                    <a href="./index.php" id="vitalSignsBack" class="btn btn-grey">Back</a>
                    <input type="submit" value="Save" name="save_vital_signs" id="vitalSignsSubmit" class="btn btn-green">
                </div>
            </div>
        </form>
    </div>

    <script src="js/vital_signs.js"></script>
    <script src="js/functions.js"></script>
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
            hiddenInput.name = 'save_vital_signs';
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
    </script>
</body>
</html> 