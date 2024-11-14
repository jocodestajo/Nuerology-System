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
    fetch("api/fetch_record.php?id=" + recordId)
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
        document.querySelector('input[name="date_request"]').value =
          data.date_request;
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

        // Optionally set other fields or radio buttons based on data
        if (data.consultation === "Face to face") {
          document.getElementById("faceToFace").checked = true;
        } else if (data.consultation === "Teleconsultation") {
          document.getElementById("teleconsultation").checked = true;
        }

        // Set hidden record_id field
        document.querySelector('input[name="record_id"]').value = recordId;

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
