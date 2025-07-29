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
    <title>Consultation</title>
    <link rel="icon" href="img/MMWGH_Logo.png" type="images/x-icon">
    <link rel="stylesheet" href="css/mainStyle.css">
    <link rel="stylesheet" href="css/general.css">
    <link rel="stylesheet" href="css/appointment_form.css">
    <link rel="stylesheet" href="css/mediaQuery.css">
</head>
<style>
    .tags-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin: 5px;
        /* min-height: 12px; */
    }
    
    .tag {
        display: flex;
        align-items: center;
        background-color: var(--lightgray-color);
        border: 1px solid var(--blue-color);
        border-radius: 20px;
        padding: 3px 5px;
        margin-right: 5px;
        margin-bottom: 5px;
        font-size: 14px;
    }
    
    .tag span {
        margin-right: 5px;
    }
    
    .remove-tag {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 18px;
        height: 18px;
        background-color: var(--grey-color);
        color: white;
        border-radius: 50%;
        cursor: pointer;
        font-size: 12px;
        font-weight: bold;
        margin-left: 5px;
        transition: background-color 0.2s;
    }
    
    .remove-tag:hover {
        background-color: var(--red-color);
    }
    
    .diagnosis {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }
    
    /* Medication styling */
    .medication-entry {
        margin: 5px 0;
        position: relative;
    }
    
    .remove-medication {
        padding: 2px 15px;
        font-size: 24px;
    }
    
    #add-medication {
        font-size: 24px;
        padding: 2px 12px;
    }
    
    /* Medicine search results styling */
    .medicine-search-results {
        position: absolute;
        width: fit-content;
        max-height: 200px;
        overflow-y: auto;
        background-color: white;
        border: 1px solid #ddd;
        border-radius: 4px;
        z-index: 1000;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .medicine-item {
        padding: 8px 12px;
        cursor: pointer;
        border-bottom: 1px solid #eee;
    }
    
    .medicine-item:hover {
        background-color: #f5f5f5;
    }
    
    .medicine-item .generic-name {
        font-weight: bold;
    }
    
    .medicine-item .brand-name {
        color: #666;
        font-style: italic;
    }
    
    .medicine-item .strength {
        color: #333;
    }
    
    /* Consultant styles */
    .flex-row {
        display: flex;
        flex-direction: row;
    }
    
    .align-items-center {
        align-items: center;
    }
    
    .margin-r-5 {
        margin-right: 5px;
    }
    
    .margin-b-5 {
        margin-bottom: 5px;
    }
</style>
<body>

    <!-- HEADER -->
    <?php include('includes/header.php'); ?>


    <div class="container-3">
        <div class="margin-b-20">
            <!-- <h2 class="consult-h2-header">Consultation</h2> -->
            <a href="./index.php" class="btn btn-grey ">Back</a>
        </div>
        <form action="api/post/updateConsult.php" method="post" id="diagnosisForm" class="container3-Content">

            <div class="turnaroundTime">
                <div class="heading1">
                    <h2>Doctor</h2>
                    <h2>Nurse</h2>
                </div>

                <div class="turnaroundContent">
                    <div class="doctorConsult">
                        <div>
                            <label for="">Start Time</label>
                            <input type="time" name="consultStart" step="1" required>
                        </div>
                        
                        <div>
                            <label for="">End Time</label>
                            <input type="time" name="consultEnd" step="1" >
                        </div>
                    </div>
                    <div class="nurseFinal">
                        <div>
                            <label for="">Start Time</label>
                            <input type="time" name="educStart" step="1" >
                        </div>
                        
                        <div>
                            <label for="">End Time</label>
                            <input type="time" name="educEnd" step="1" >
                        </div>
                    </div>
                </div>
            </div>

            <div class="consultants">
                <h2 class="center-text">Consultant</h2>
                <div class="consultsss pad-hor-20 margin-b-20 width-100">
                        <!-- <div class="margin-b-5 width-100">
                            <select name="consultant_1_type" id="consultant1_type" class="margin-r-5 width-100 center-text">
                                <option value="Doctor" selected>Doctor</option>
                                <option value="Nurse">Nurse</option>
                                <option value="Nurse Attendant">Nurse Attendant</option>
                            </select>
                        </div> -->
                        <div>
                            <input name="consultant_1" id="consultant1" class="width-100" placeholder="Doctor" require>
                        </div>
                   
                        <!-- <div class="margin-b-5 width-100">
                            <select name="consultant_2_type" id="consultant2_type" class="margin-r-5 width-100 center-text">
                                <option value="Doctor">Doctor</option>
                                <option value="Nurse" selected>Nurse</option>
                                <option value="Nurse Attendant">Nurse Attendant</option>
                            </select>
                        </div> -->
                        <div class="width-100">
                            <input name="consultant_2" id="consultant2" class="width-100" placeholder="Nurse" require>
                        </div> 
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
                                        <input type="radio" value="Teleconsultation" id="consultTelecon" name="consultation">
                                        Teleconsultation
                                    </label>
                                </div>
                            </div>

                            <div class="border-left width-100 padding-20">
                                <div class="">
                                    <label for="consultRX">
                                        <input type="checkbox" value="RX" id="consultRX" name="consultPurpose[]">
                                        RX
                                    </label>
                                </div>

                                <div class="">
                                    <label for="consultMC">
                                        <input type="checkbox" value="MC" id="consultMC" name="consultPurpose[]">
                                        MC
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="width-100">
                            <h3>Diagnosis</h3>
                            <div class="input-container padding-inline-10 margin-t-10 margin-b-10" id="inputContainer">
                                <div class="tags-wrapper" id="tagsWrapper"></div>
                                <input type="text" id="diagnosis" class="diagnosis" placeholder="Write down diagnosis and press Enter"/>
                            </div>
                        </div>
                        
                        <div class="space-evenly">
                            <div class="classification">
                                <h3>Classification</h3>
                                <!-- Modal Trigger Button -->
                                <button type="button" data-modal-target="classificationModal1" id="classificationSelectBtn" class="btn border width-100">--- Select Option ---</button>
                                <!-- Modal Container -->
                                <div id="classificationModal1" class="classificationShow" style="display:none;">
                                    <div class="modal-content" style="width: 400px; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                        <div class="checkbox-classification checkbox-group">
                                            <?php
                                                $sql1 = "SELECT id, name FROM neurology_classifications WHERE archived = 0";
                                                $result1 = $conn->query($sql1);
                                                if ($result1->num_rows > 0) {
                                                    while($row = $result1->fetch_assoc()) {
                                                        echo "<label><input type='checkbox' name='classification[]' value='" . htmlspecialchars($row['id']) . "'> " . htmlspecialchars($row['name']) . "</label>";
                                                    }
                                                } else {
                                                    echo "<label><input type='checkbox' disabled> No classifications found</label>";
                                                }
                                            ?>
                                            <div style="text-align:right; margin-top:10px;">
                                                <button type="button" id="closeClassificationModal" class="btn btn-blue">Done</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="consultMed flex-row">
                                <div class="width-100">
                                    <h3 class="margin-b-10">Medication</h3>
                                    <div id="medication-container">
                                        <div class="medication-entry flex-row padding-inline-10 ">
                                            <div class="width-60">
                                                <input type="text" name="medication[]" class="width-100 center-text" placeholder="Medication">
                                            </div>
                                            <div class="width-20">
                                                <input type="text" name="medicationDosage[]" class="width-100 center-text" placeholder="Dosage">
                                            </div>
                                            <div class="width-10">
                                                <input type="number" name="medQty[]" class="width-100 center-text" placeholder="Qty">
                                            </div>
                                            <button type="button" id="add-medication" class="btn btn-blue">
                                                <i class="fas fa-plus"></i> &#43;
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex-row">
                            <div class="width-100 consult-referral margin-b-20">
                                <h3>Referral</h3>
                                <?php
                                    $sql_depts = "SELECT deptid, deptname FROM departments WHERE deptlocation = 'Medical Service' AND deptstat = 0";
                                    $result1 = $conn->query($sql_depts);

                                    $departments = [];

                                    if ($result1 && $result1->num_rows > 0) {
                                        while($row = $result1->fetch_assoc()) {
                                            $departments[] = $row;
                                        }
                                    }

                                ?>
                                
                                <div class="text-left padding-inline-10">
                                    <label for="consultReferFrom" class="rfl">
                                        Referred fro:
                                        <select name="refer_from" id="consultReferFrom" class="width-100 center-text">
                                            <option value="N/A" hidden selected>N/A</option>
                                            <?php foreach ($departments as $dept): ?>
                                                <option value="<?= $dept['deptid']; ?>"><?= htmlspecialchars($dept['deptname']); ?></option>
                                            <?php endforeach; ?>
                                            <option value="Other">Other</option>
                                        </select>
                                    </label>
                                </div>
                                    
                                <div class="text-left padding-inline-10">   
                                    <label for="consultReferTo" class="rfl">
                                        Refer to:
                                        <select name="refer_to" id="consultReferTo" class="width-100 center-text">
                                            <option value="N/A" hidden selected>N/A</option>
                                            <?php foreach ($departments as $dept): ?>
                                                <option value="<?= $dept['deptname']; ?>"><?= htmlspecialchars($dept['deptname']); ?></option>
                                            <?php endforeach; ?>
                                            <option value="Other">Other</option>
                                        </select>
                                        <input type="text" name="otherInstitute" class="otherInstitute width-100 center-text" placeholder="Other Institute">
                                    </label>
                                </div>
                            </div>

                            <div class="width-100 consult-followUp">
                                <h3>Follow Up</h3>
                                <div class="padding-inline-10">
                                    <label for="consultFollowUp">
                                        <input type="checkbox" name="follow_up" value="follow up" id="consultFollowUp">
                                        Follow Up
                                    </label>
                                </div>
                                <div class="calendar text-left padding-inline-10">
                                    <label for="">
                                        Date:
                                        <!-- <input type="date" id="consultDate" name="date_sched" class="width-100 center-text"> -->
                                        <span class="calendar-flex">
                                            <span class="datePicker btn-blue" data-sched-output="dateSched4">Calendar</span>
                                            <input type="date" id="dateSched4" class="date center-text" name="date_sched" readonly>
                                            <!-- date na kukunin if hindi follow up ang status -->
                                            <input type="hidden" name="date_sched_def">
                                        </span>
                                    </label>

                                    <?php include('includes/calendarTable_modal.php'); ?>

                                </div>
                            </div>
                        </div>

                        <div class="padding-inline-10">
                            <h3>Remarks</h3>
                            <textarea rows="4" name="remarks" class="padding-5"></textarea>
                        </div>
                    </div>

                    <input type="hidden" name="appointment_type" >
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
    <script src="js/vital_signs.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('diagnosisForm');
            const inputField = document.getElementById('diagnosis');
            const tagsWrapper = document.getElementById("tagsWrapper");

            // Handle Enter keypress to create a tag
            inputField.addEventListener("keypress", function (event) {
                if (event.key === "Enter" && inputField.value.trim() !== "") {
                    event.preventDefault(); // Prevent form submission

                    // Create tag element
                    const tag = document.createElement("div");
                    tag.classList.add("tag");

                    // Tag text
                    const text = document.createElement("span");
                    text.textContent = inputField.value.trim();
                    tag.appendChild(text);

                    // Remove button
                    const removeBtn = document.createElement("span");
                    removeBtn.textContent = "x";
                    removeBtn.classList.add("remove-tag");
                    removeBtn.onclick = () => {
                        tag.remove();
                        inputField.focus();
                    };
                    tag.appendChild(removeBtn);

                    // Add to wrapper
                    tagsWrapper.appendChild(tag);
                    inputField.value = ""; // Clear input
                }
            });

            // Before submitting, create hidden inputs from tags
            form.addEventListener('submit', function (e) {
                // Clean up any existing hidden inputs
                form.querySelectorAll('input[name="diagnosis[]"]').forEach(el => el.remove());

                // For each tag, create a hidden input
                const tags = tagsWrapper.querySelectorAll('.tag span:first-child');
                tags.forEach(tag => {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'diagnosis[]';
                    hiddenInput.value = tag.textContent;
                    form.appendChild(hiddenInput);
                });

                // Include current input field value if not empty
                if (inputField.value.trim() !== "") {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'diagnosis[]';
                    hiddenInput.value = inputField.value.trim();
                    form.appendChild(hiddenInput);
                }
            });
        });

        // MEDICATION handling
        document.addEventListener('DOMContentLoaded', function() {
            const addMedicationBtn = document.getElementById('add-medication');
            const medicationContainer = document.getElementById('medication-container');
            
            // Function to create a medicine search results container
            function createMedicineSearchResults() {
                const resultsContainer = document.createElement('div');
                resultsContainer.className = 'medicine-search-results';
                return resultsContainer;
            }
            
            // Function to handle medicine search
            function setupMedicineSearch(inputElement) {
                let searchTimeout;
                const resultsContainer = createMedicineSearchResults();
                inputElement.parentNode.appendChild(resultsContainer);
                
                inputElement.addEventListener('keyup', function() {
                    clearTimeout(searchTimeout);
                    const searchTerm = this.value.trim();
                    
                    // Hide results if search term is empty
                    if (searchTerm === '') {
                        resultsContainer.innerHTML = '';
                        resultsContainer.style.display = 'none';
                        return;
                    }
                    
                    // Set a timeout to avoid too many requests while typing
                    searchTimeout = setTimeout(function() {
                        // Fetch medicine data from the server
                        fetch(`api/get/search_medicines.php?search=${encodeURIComponent(searchTerm)}`)
                            .then(response => response.json())
                            .then(data => {
                                resultsContainer.innerHTML = '';
                                
                                if (data.length > 0) {
                                    data.forEach(medicine => {
                                        const medicineItem = document.createElement('div');
                                        medicineItem.className = 'medicine-item';
                                        
                                        // Create medicine display with generic name, strength, and strength_description only (no brand_name)
                                        const displayText = `${medicine.generic_name} ${medicine.strength} ${medicine.strength_description || ''}`;
                                        
                                        medicineItem.innerHTML = `
                                            <div class="generic-name">${medicine.generic_name}</div>
                                            <div class="strength">${medicine.strength} ${medicine.strength_description || ''}</div>
                                        `;
                                        
                                        // Add click event to select the medicine
                                        medicineItem.addEventListener('click', function() {
                                            inputElement.value = displayText;
                                            resultsContainer.style.display = 'none';
                                            
                                            // Store the medicine ID in a hidden input
                                            const medicineIdInput = inputElement.parentNode.querySelector('input[name="medicine_id[]"]');
                                            if (medicineIdInput) {
                                                medicineIdInput.value = medicine.medicine_id;
                                            }
                                        });
                                        
                                        resultsContainer.appendChild(medicineItem);
                                    });
                                    
                                    resultsContainer.style.display = 'block';
                                } else {
                                    resultsContainer.style.display = 'none';
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching medicines:', error);
                                resultsContainer.style.display = 'none';
                            });
                    }, 300); // 300ms delay
                });
                
                // Hide results when clicking outside
                document.addEventListener('click', function(event) {
                    if (!inputElement.contains(event.target) && !resultsContainer.contains(event.target)) {
                        resultsContainer.style.display = 'none';
                    }
                });
            }
            
            // Setup search for the initial medication input
            const initialMedicationInput = medicationContainer.querySelector('input[name="medication[]"]');
            setupMedicineSearch(initialMedicationInput);
            
            // Add hidden input for medicine ID
            const medicineIdInput = document.createElement('input');
            medicineIdInput.type = 'hidden';
            medicineIdInput.name = 'medicine_id[]';
            initialMedicationInput.parentNode.appendChild(medicineIdInput);
            
            addMedicationBtn.addEventListener('click', function() {
                const medicationEntry = document.createElement('div');
                medicationEntry.className = 'medication-entry flex-row padding-inline-10';

                const medicationInput = document.createElement('div');
                medicationInput.className = 'width-60';
                medicationInput.innerHTML = `
                    <input type="text" name="medication[]" class="width-100 center-text" placeholder="Enter medication">
                `;

                const dosageInput = document.createElement('div');
                dosageInput.className = 'width-20';
                dosageInput.innerHTML = `
                    <input type="text" name="medicationDosage[]" class="width-100 center-text" placeholder="Dosage">
                `;

                const qtyInput = document.createElement('div');
                qtyInput.className = 'width-10';
                qtyInput.innerHTML = `
                    <input type="number" name="medQty[]" class="width-100 center-text" placeholder="Qty">
                `;

                const removeBtn = document.createElement('div');
                removeBtn.className = 'width-10';
                removeBtn.innerHTML = `
                    <button type="button" class="btn btn-red remove-medication">
                        &#45;
                    </button>
                `;

                medicationEntry.appendChild(medicationInput);
                medicationEntry.appendChild(dosageInput);
                medicationEntry.appendChild(qtyInput);
                medicationEntry.appendChild(removeBtn);

                medicationContainer.appendChild(medicationEntry);

                // Setup search for the new medication input
                const newMedicationInput = medicationInput.querySelector('input[name="medication[]"]');
                setupMedicineSearch(newMedicationInput);

                // Add event listener to the new remove button
                const newRemoveBtn = medicationEntry.querySelector('.remove-medication');
                newRemoveBtn.addEventListener('click', function() {
                    medicationEntry.remove();
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
        const referToSelect = document.getElementById('consultReferTo');
        const otherInstituteInput = document.querySelector('.otherInstitute');

        function toggleOtherInstitute() {
            if (referToSelect.value === 'Other') {
                otherInstituteInput.style.display = 'block';
            } else {
                otherInstituteInput.style.display = 'none';
            }
        }

        // Initial check in case of form repopulation
        toggleOtherInstitute();

        referToSelect.addEventListener('change', toggleOtherInstitute);
    });

        // Classification Modal Logic
        document.addEventListener('DOMContentLoaded', function() {
            const classificationBtn = document.getElementById('classificationSelectBtn');
            const classificationModal = document.getElementById('classificationModal1');
            const closeClassificationModal = document.getElementById('closeClassificationModal');
            const classificationCheckboxes = classificationModal.querySelectorAll('input[type="checkbox"][name="classification[]"]');

            // Open modal
            classificationBtn.addEventListener('click', function() {
                classificationModal.style.display = 'block';
            });
            // Close modal
            closeClassificationModal.addEventListener('click', function() {
                classificationModal.style.display = 'none';
                updateClassificationBtnText();
            });
            // Update button text based on selected checkboxes
            function updateClassificationBtnText() {
                const checked = Array.from(classificationModal.querySelectorAll('input[type="checkbox"][name="classification[]"]:checked'));
                if (checked.length === 0) {
                    classificationBtn.textContent = '--- Select Option ---';
                } else {
                    // Show the label text (name) instead of the id
                    classificationBtn.textContent = checked.map(cb => cb.parentNode.textContent.trim()).join(', ');
                }
            }
            // Update on change
            classificationCheckboxes.forEach(cb => {
                cb.addEventListener('change', updateClassificationBtnText);
            });
        });
   </script>
    <?php
        $conn->close();
    ?>
</body>
</html>