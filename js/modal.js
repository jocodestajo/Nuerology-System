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
    console.log(recordId);

    // Make an AJAX request to fetch the data for this record
    fetch("api/get/fetch_record.php?id=" + recordId)
      .then((response) => response.json())
      .then((data) => {
        console.log("Fetched data:", data); // Add this line to log the fetched data
        // Fill the modal form with the data

        // Set the correct option in the select inputs
        document.querySelector('select[name="view_typeofappoint"]').value =
          data.appointment_type;
        document.querySelector('input[name="view_referal"]').value =
          data.refer_from;
        document.querySelector('input[name="view_hrn"]').value = data.hrn;
        document.querySelector('input[name="view_name"]').value = data.name;

        // Convert and set the birthday field
        if (data.birthday) {
          document.querySelector('input[name="view_birthday"]').value =
            data.birthday;

          let age = calculateAge(data.birthday); // Ensure calculateAge() also expects YYYY-MM-DD
          document.querySelector('input[name="view_age"]').value = age;
        }

        document.querySelector('input[name="view_address"]').value =
          data.address;
        document.querySelector('input[name="view_email"]').value = data.email;
        document.querySelector('input[name="view_contact"]').value =
          data.contact;
        document.querySelector('input[name="view_viber"]').value = data.viber;
        document.querySelector('input[name="view_informant"]').value =
          data.informant;
        document.querySelector('input[name="view_informant_relation"]').value =
          data.informant_relation;
        document.querySelector('select[name="view_old_new"]').value =
          data.old_new;

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

        document.querySelector('input[name="view_date_sched"]').value =
          data.date_sched;
        // document.querySelector('select[name="view_complaint"]').value =
        //   data.complaint;

        // Handle complaints (checkboxes)
        const complaintValues = data.complaint
          ? data.complaint.split(",").map((item) => item.trim())
          : [];
        document
          .querySelectorAll('input[name="view_complaint[]"]')
          .forEach((checkbox) => {
            checkbox.checked = complaintValues.includes(checkbox.value);
          });

        // After setting the checkboxes, update the button display
        if (
          window.updateComplaintDisplayFunctions &&
          typeof window.updateComplaintDisplayFunctions.complaintModal_edit ===
            "function"
        ) {
          window.updateComplaintDisplayFunctions.complaintModal_edit();
        }

        document.querySelector('textarea[name="view_history"]').value =
          data.history;

        // Set hidden record_id field
        document.querySelector('input[name="record_id"]').value = recordId;

        // Set fields to disabled
        document.getElementById("view-hrn").readOnly = true;
        // document.getElementById("view-name").readOnly = true;
        // document.getElementById("view-address").readOnly = true;
        // document.getElementById("view-clientSelect").readOnly = true;

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
// const typeOfAppointment = document.getElementById("typeOfAppointment");
// const referralContent = document.getElementById("referralContent");

// typeOfAppointment.addEventListener("change", function () {
//   if (this.value === "Referral") {
//     referralContent.style.display = "block";
//   } else if (this.value !== "Referral") {
//     referralContent.style.display = "none";
//   }
// });

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
    window.location.href = "consultation.php?id=" + recordId;
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

  // Function to clear all form inputs in the modal
  function clearModalForm() {
    const form = document.getElementById("addPatientForm");
    if (form) {
      // Clear all text inputs, textareas, and selects
      form
        .querySelectorAll('input[type="text"], input[type="email"], textarea')
        .forEach((input) => {
          input.value = "";
        });

      // Reset all select elements to their first option
      form.querySelectorAll("select").forEach((select) => {
        select.selectedIndex = 0;
      });

      // Reset radio buttons
      form.querySelectorAll('input[type="radio"]').forEach((radio) => {
        radio.checked = false;
      });

      // Reset complaint button and checkboxes
      const complaintBtn = document.getElementById("complaintBtn");
      if (complaintBtn) {
        complaintBtn.textContent = "--- Select Option ---";
      }
      form.querySelectorAll('input[name="complaint[]"]').forEach((checkbox) => {
        checkbox.checked = false;
      });

      // Reset birthdate and age
      const modalBirthday = document.getElementById("modal_birthday");
      const modalAge = document.getElementById("modal_age");
      if (modalBirthday) {
        modalBirthday.value = "";
      }
      if (modalAge) {
        modalAge.value = "";
      }

      // Hide and clear informant details
      const informantDetails = document.querySelector(".informant-details");
      if (informantDetails) {
        informantDetails.style.display = "none";
      }

      // Hide and clear referral section
      const referralSection = document.getElementById("modalReferral");
      if (referralSection) {
        referralSection.style.display = "none";
      }

      // Clear search results
      const searchResult = document.getElementById("modal_searchResult");
      if (searchResult) {
        searchResult.style.display = "none";
      }
      const result = document.getElementById("modal_result");
      if (result) {
        result.innerHTML = "";
      }

      // Reset readonly states
      const readonlyFields = [
        "modal_hrn",
        "modal_name",
        "modal_age",
        "modal_birthday",
        "modal_address",
        "modal_contact",
      ];
      readonlyFields.forEach((fieldId) => {
        const field = document.getElementById(fieldId);
        if (field) {
          field.readOnly = false;
        }
      });
    }
  }

  // Update close button handler
  if (closeAddPatientModal) {
    closeAddPatientModal.addEventListener("click", function () {
      const modal = document.getElementById("addPatientModal");
      if (modal) {
        modal.style.display = "none";
        clearModalForm();
      }
    });
  }

  // Update cancel button handler
  if (cancelAddPatient) {
    cancelAddPatient.addEventListener("click", function () {
      const modal = document.getElementById("addPatientModal");
      if (modal) {
        modal.style.display = "none";
        clearModalForm();
      }
    });
  }

  // Add click outside modal handler
  window.addEventListener("click", function (event) {
    const modal = document.getElementById("addPatientModal");
    if (event.target === modal) {
      modal.style.display = "none";
      clearModalForm();
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

  let debounceTimeout;

  function closeModalSearch() {
    modalResult.innerHTML = "";
    modalSearchResult.style.display = "none";
  }

  if (modalName) {
    modalName.addEventListener("keyup", function () {
      clearTimeout(debounceTimeout);

      debounceTimeout = setTimeout(function () {
        let inquery = modalName.value.trim(); // Trim whitespace
        if (inquery !== "") {
          modalSearchResult.style.display = "block";

          // AJAX request for search suggestions
          let xhr = new XMLHttpRequest();
          xhr.open(
            "GET",
            "api/get/searchPatient.php?query=" + encodeURIComponent(inquery),
            true
          );
          xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
              if (xhr.responseText.trim() !== "") {
                const patients = JSON.parse(xhr.responseText);
                modalResult.innerHTML = "";

                if (patients.length > 0) {
                  patients.forEach((patient) => {
                    const item = document.createElement("p");
                    item.classList.add("result-item");
                    item.setAttribute("data-id", patient.id);
                    item.setAttribute("data-name", patient.name);
                    item.textContent = patient.name;
                    modalResult.appendChild(item);
                  });
                } else {
                  modalResult.innerHTML = "<p>No results found</p>";
                }
              } else {
                modalResult.innerHTML = "<p>No results found</p>";
              }
            } else if (xhr.readyState === 4) {
              console.error("Error with request:", xhr.status);
            }
          };
          xhr.send();
        } else {
          closeModalSearch();
        }
      }, 300);
    });
  }

  // Close modal on outside click
  document.addEventListener("click", function (event) {
    const resultItem = event.target.closest(".result-item");

    // If clicking on modalName or a result item, avoid closing and handle accordingly
    if (event.target === modalName || resultItem) {
      if (resultItem) {
        let name = resultItem.getAttribute("data-name");
        let id = resultItem.getAttribute("data-id");

        // Fetch full details for the selected item
        let xhr = new XMLHttpRequest();
        xhr.open("GET", "api/get/fetch_data.php?id=" + id, true);
        xhr.onreadystatechange = function () {
          if (xhr.readyState === 4 && xhr.status === 200) {
            let data = JSON.parse(xhr.responseText);

            modalHrn.value = data.hrn;
            modalName.value = data.name;

            // Convert and set birthday
            if (data.birthday) {
              let formattedDate = formatDate(data.birthday);
              modalBirthday.value = formattedDate;

              // Calculate and set age
              let age = calculateAge(formattedDate);
              modalAge.value = age;
            }

            modalContact.value = data.contactnumber;
            modalAddress.value = data.address;

            // Set fields to readonly
            modalHrn.readOnly = true;
            modalName.readOnly = true;
            modalAge.readOnly = true;
            modalBirthday.readOnly = true;
            modalAddress.readOnly = true;

            if (modalContact.value !== "") {
              modalContact.readOnly = true;
            }

            closeModalSearch();
          }
        };
        xhr.send();
      }
      return;
    }

    // Close the modal if clicking outside the modal, results, or input
    if (
      !modalSearchResult.contains(event.target) &&
      !modalResult.contains(event.target) &&
      !modalName.contains(event.target)
    ) {
      closeModalSearch();
    }
  });

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

// Complaint Modal for Edit Records
document.addEventListener("DOMContentLoaded", function () {
  const complaintModalEdit = document.getElementById("complaintModal_edit");
  const complaintTriggerEdit = document.querySelector(
    'button[data-modal-target="complaintModal_edit"]'
  );

  if (complaintTriggerEdit) {
    complaintTriggerEdit.addEventListener("click", function () {
      complaintModalEdit.style.display = "block";
    });
  }

  if (complaintModalEdit) {
    complaintModalEdit.addEventListener("click", function (event) {
      if (
        event.target === complaintModalEdit ||
        event.target.classList.contains("close-btn")
      ) {
        complaintModalEdit.style.display = "none";
      }
    });
  }
});

// Handle informant question in modal
const modalInformantQA = document.querySelectorAll('input[name="informantQA"]');
const modalInformantDetails = document.querySelector(".informant-details");

modalInformantQA.forEach((radio) => {
  radio.addEventListener("change", function () {
    if (this.value === "yes") {
      modalInformantDetails.style.display = "block";
      // Make fields required when shown
      modalInformantDetails.querySelectorAll("input").forEach((input) => {
        input.required = true;
      });
    } else {
      modalInformantDetails.style.display = "none";
      // Remove required and clear values when hidden
      modalInformantDetails.querySelectorAll("input").forEach((input) => {
        input.required = false;
        input.value = "";
      });
    }
  });
});
