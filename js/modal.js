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

    // check if working
    console.log(recordId);

    // Make an AJAX request to fetch the data for this record
    fetch("api/get/fetch_record.php?id=" + recordId)
      .then((response) => response.json())
      .then((data) => {
        // check data
        console.log(data);

        // Fill the modal form with the data
        document.querySelector('input[name="hrn"]').value = data.hrn;
        document.querySelector('input[name="name"]').value = data.name;
        document.querySelector('input[name="age"]').value = data.age;
        document.querySelector('input[name="address"]').value = data.address;
        document.querySelector('input[name="birthday"]').value = data.birthday;
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
        document.querySelector('input[name="referal"]').value = data.referal;

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
        document.getElementById("view-age").readOnly = true;
        document.getElementById("view-birthday").readOnly = true;
        // document.getElementById("view-contact").readOnly = true;
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
