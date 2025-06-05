// MODAL BEHAVIOR /////////////////////////////////////////////////////////////////

// Get the modal
var modal = document.getElementById("myModal");
var cancelBtn = document.querySelectorAll(".cancelBtn");

// Get all View buttons
var viewButtons = document.querySelectorAll(".view-button");

// When the user clicks on a view button, open the modal and fetch the record data
viewButtons.forEach((button) => {
  button.addEventListener("click", function (e) {
    e.preventDefault();
    var recordId = this.getAttribute("data-record-id"); // Get the record ID

    // Make an AJAX request to fetch the data for this record
    fetch("api/get/fetch_record.php?id=" + recordId)
      .then((response) => response.json())
      .then((data) => {
        // Fill the modal form with the data
        document.querySelector('input[name="hrn"]').value = data.hrn;
        document.querySelector('input[name="name"]').value = data.name;
        document.querySelector('input[name="address"]').value = data.address;

        // Convert and set the birthday field
        if (data.birthday) {
          document.querySelector('input[name="birthday"]').value =
            data.birthday;

          let age = calculateAge(data.birthday); // Ensure calculateAge() also expects YYYY-MM-DD
          document.querySelector('input[name="age"]').value = age;
        }
        // document.querySelector('input[name="birthday"]').value = data.birthday;
        document.querySelector('input[name="email"]').value = data.email;
        document.querySelector('input[name="contact"]').value = data.contact;
        document.querySelector('input[name="viber"]').value = data.viber;
        document.querySelector('input[name="informant"]').value =
          data.informant;
        document.querySelector('input[name="informant_relation"]').value =
          data.informant_relation;
        // document.querySelector('input[name="date_request"]').value =
        //   data.date_request;
        document.querySelector('input[name="date_sched"]').value =
          data.date_sched;
        document.querySelector('textarea[name="history"]').value = data.history;
        document.querySelector('input[name="referal"]').value = data.refer_from;

        // Set the correct option in the select inputs
        document.querySelector('select[name="typeofappoint"]').value =
          data.appointment_type;
        document.querySelector('select[name="old_new"]').value = data.old_new;
        document.querySelector('select[name="complaint"]').value =
          data.complaint;

        // Handle enabling/disabling of teleconsultation based on `old_new` value
        if (data.old_new === "New") {
          document.getElementById("faceToFace").checked = true;
          document.getElementById("teleconsultation").disabled = true;
          document.getElementById("teleconsultation").checked = false;
        } else if (data.old_new === "Old") {
          document.getElementById("teleconsultation").disabled = false;
        }

        // Handle consultation type selection
        if (data.consultation === "Face to face") {
          document.getElementById("faceToFace").checked = true;
        } else if (data.consultation === "Teleconsultation") {
          document.getElementById("teleconsultation").checked = true;
        }

        // Set hidden record_id field
        document.querySelector('input[name="record_id"]').value = recordId;

        // Set fields to disabled
        document.getElementById("view-hrn").readOnly = true;
        document.getElementById("view-name").readOnly = true;

        document.getElementById("view-address").readOnly = true;
        document.getElementById("view-clientSelect").readOnly = true;

        // Show the modal
        modal.style.display = "block";
      })
      .catch((error) => console.log(error));
  });
});

// When the user clicks on <span> (x) or cancel button, close the modal
cancelBtn.forEach((button) => {
  button.onclick = function (e) {
    e.preventDefault();
    modal.style.display = "none";
  };
});

// RADIO BUTTON IF NEW OR OLD PATIENT ///////////////////////////////////////////
const viewClientSelect = document.getElementById("view-clientSelect");
const teleconsultationRadio = document.getElementById("teleconsultation");

viewClientSelect.addEventListener("change", function () {
  if (this.value === "New") {
    // check radio button "face to face"
    document.getElementById("faceToFace").checked = true;

    teleconsultationRadio.disabled = true;
    // Optionally uncheck the radio button if it's selected
    if (teleconsultationRadio.checked) {
      teleconsultationRadio.checked = false;
    }
  } else {
    teleconsultationRadio.disabled = false;
  }
});

// MODAL - SCHEDULE SETTINGS
const scheduleSettings = document.getElementById("scheduleSettings");
const setSchedule = document.getElementById("setSchedule");

scheduleSettings.onclick = function (e) {
  e.preventDefault();
  if ((setSchedule.style.display = "none")) {
    setSchedule.style.display = "block";
    return;
  }
};

document.querySelector("#setSchedule .close-btn").onclick = function () {
  setSchedule.style.display = "none";
};

// DISPLAY REFERRAL WHEN Type of Appointment value is Referral
const typeOfAppointment = document.getElementById("typeOfAppointment");
const referralContent = document.getElementById("referralContent");

typeOfAppointment.addEventListener("change", function () {
  if (this.value === "Referral") {
    referralContent.style.display = "block";
  } else if (this.value !== "Referral") {
    referralContent.style.display = "none";
  }
});

const view_appointment = document.getElementById("view_appointment");
const viewReferalContent = document.getElementById("viewReferalContent");

view_appointment.addEventListener("change", function () {
  if (this.value === "Referral") {
    viewReferalContent.style.display = "block";
  } else if (this.value !== "Referral") {
    viewReferalContent.style.display = "none";
  }
});

// FOR VITAL SIGNS AND CONSULTATION BUTTONS
var vitalSignsButtons = document.querySelectorAll(
  ".action-img[alt='VitalSigns']"
);
var consultationButtons = document.querySelectorAll(
  ".action-img[alt='Consultation']"
);

// Handle Vital Signs button clicks
vitalSignsButtons.forEach((button) => {
  button.addEventListener("click", function (e) {
    e.preventDefault();
    var recordId = this.getAttribute("data-record-id");
    window.location.href = "vital_signs.php?id=" + recordId;
  });
});

// Handle Consultation button clicks
consultationButtons.forEach((button) => {
  button.addEventListener("click", function (e) {
    e.preventDefault();
    var recordId = this.getAttribute("data-record-id");
    window.open("consultation.php?id=" + recordId, "_blank");
  });
});

// ADD PATIENT MODAL
document.addEventListener("DOMContentLoaded", function () {
  // Get modal elements
  const addPatientModal = document.getElementById("addPatientModal");
  const closeAddPatientModal = document.getElementById("closeAddPatientModal");
  const cancelAddPatient = document.getElementById("cancelAddPatient");
  const addPatientForm = document.getElementById("addPatientForm");
  const consultationType = document.getElementById("consultation_type");
  const addPatientTitle = document.getElementById("addPatientTitle");

  // Add event listeners to all add patient buttons
  const addPatientButtons = document.querySelectorAll(".add-patient-btn");
  addPatientButtons.forEach(function (button) {
    button.addEventListener("click", function () {
      const type = this.getAttribute("data-type");

      if (type === "f2f") {
        consultationType.value = "Face to face";
        addPatientTitle.textContent = "Add Face to Face Patient";
      } else if (type === "telecon") {
        consultationType.value = "Teleconsultation";
        addPatientTitle.textContent = "Add Teleconsultation Patient";
      }

      addPatientModal.style.display = "block";
    });
  });

  // Close modal when clicking X button
  if (closeAddPatientModal) {
    closeAddPatientModal.addEventListener("click", function () {
      addPatientModal.style.display = "none";
    });
  }

  // Close modal when clicking Cancel button
  if (cancelAddPatient) {
    cancelAddPatient.addEventListener("click", function () {
      addPatientModal.style.display = "none";
    });
  }

  // Close modal when clicking outside the modal
  window.addEventListener("click", function (event) {
    if (event.target === addPatientModal) {
      addPatientModal.style.display = "none";
    }
  });

  // Show referral field when Referral option is selected
  const modalTypeOfAppointment = document.getElementById(
    "modal_typeOfAppointment"
  );
  const modalReferral = document.getElementById("modalReferral");

  if (modalTypeOfAppointment) {
    modalTypeOfAppointment.addEventListener("change", function () {
      if (this.value === "Referral") {
        modalReferral.style.display = "block";
      } else {
        modalReferral.style.display = "none";
      }
    });
  }

  // Patient name search in modal
  const modalName = document.getElementById("modal_name");
  const modalHrn = document.getElementById("modal_hrn");
  const modalAddress = document.getElementById("modal_address");
  const modalBirthday = document.getElementById("modal_birthday");
  const modalAge = document.getElementById("modal_age");
  const modalContact = document.getElementById("modal_contact");
  const modalSearchResult = document.getElementById("modal_searchResult");
  const modalResult = document.getElementById("modal_result");

  if (modalName) {
    modalName.addEventListener("input", function () {
      const searchQuery = this.value;

      if (searchQuery.length >= 3) {
        // Fetch matching patient data
        fetch(
          `api/get/searchPatient.php?query=${encodeURIComponent(searchQuery)}`
        )
          .then((response) => response.json())
          .then((data) => {
            modalResult.innerHTML = "";
            modalSearchResult.style.display = "block";

            if (data.length > 0) {
              data.forEach((patient) => {
                const item = document.createElement("div");
                item.classList.add("search-item");
                item.textContent = patient.name;
                item.addEventListener("click", function () {
                  modalName.value = patient.name;
                  modalHrn.value = patient.hrn;
                  modalAddress.value = patient.address;
                  modalBirthday.value = patient.birthday;
                  modalAge.value = calculateAge(patient.birthday);
                  modalContact.value = patient.contact;
                  modalSearchResult.style.display = "none";
                });
                modalResult.appendChild(item);
              });
            } else {
              const item = document.createElement("div");
              item.classList.add("search-item");
              item.textContent = "No matching patients found";
              modalResult.appendChild(item);
            }
          })
          .catch((error) => console.error("Error:", error));
      } else {
        modalSearchResult.style.display = "none";
      }
    });
  }

  // Calculate age function for modal
  if (modalBirthday) {
    modalBirthday.addEventListener("change", function () {
      const birthDate = new Date(this.value);
      const age = calculateAge(this.value);
      modalAge.value = age;
    });
  }

  // Helper function to calculate age from birthday
  function calculateAge(birthDateString) {
    const birthDate = new Date(birthDateString);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();

    if (
      monthDiff < 0 ||
      (monthDiff === 0 && today.getDate() < birthDate.getDate())
    ) {
      age--;
    }

    return age;
  }
});

// APPROVE CONFIRMATION MODAL
const approveConfirmationModal = document.getElementById(
  "approveConfirmationModal"
);
const triggerApproveModal = document.querySelectorAll(".trigger-approve-modal");
const confirmApproveBtn = document.getElementById("confirmApprove");
const cancelApproveBtn = document.getElementById("cancelApprove");
let currentRecordIdToApprove = null;

triggerApproveModal.forEach((button) => {
  button.addEventListener("click", function () {
    currentRecordIdToApprove = this.getAttribute("data-id");
    approveConfirmationModal.style.display = "block";
  });
});

confirmApproveBtn.addEventListener("click", function () {
  if (currentRecordIdToApprove) {
    // Trigger the approval logic here, potentially from approval-f2f-telecon.js
    // For now, let's assume we'll call a function or dispatch an event
    // that approval-f2f-telecon.js listens to.
    // A simpler way is to move the approval logic into a function here
    // or make it accessible globally.

    // Temporarily, I'll put a placeholder and then refactor in approval-f2f-telecon.js
    console.log("Approving record: " + currentRecordIdToApprove);

    // Call the approval function directly
    approveAppointment(currentRecordIdToApprove);
    approveConfirmationModal.style.display = "none";
  }
});

cancelApproveBtn.addEventListener("click", function () {
  approveConfirmationModal.style.display = "none";
  currentRecordIdToApprove = null;
});

document.querySelector("#approveConfirmationModal .close-btn").onclick =
  function () {
    approveConfirmationModal.style.display = "none";
    currentRecordIdToApprove = null;
  };
