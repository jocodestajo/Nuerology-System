// Get all View buttons
var viewConsultation = document.querySelectorAll(".viewConsultation");

viewConsultation.forEach((button) => {
  button.addEventListener("click", function (e) {
    console.log("clicked");
    e.preventDefault();
    var recordId = this.getAttribute("data-record-id");

    window.location.href = "consultation.php?id=" + recordId;
  });
});

// Get the URL parameters
const urlParams = new URLSearchParams(window.location.search);

// Get the 'id' value
const recordId = urlParams.get("id");

// Make an AJAX request to fetch the data for this record
fetch("api/get/fetch_record.php?id=" + recordId)
  .then((response) => {
    if (!response.ok) {
      throw new Error("HTTP error! Status: " + response.status);
    }
    return response.text(); // Get raw response
  })
  .then((text) => {
    console.log("Raw response:", text); // Debugging output
    return text ? JSON.parse(text) : {}; // Only parse if there's data
  })
  .then((data) => {
    console.log("Parsed JSON:", data); // Debugging output

    if (!data || Object.keys(data).length === 0) {
      throw new Error("Empty or invalid JSON response");
    }

    document.querySelector('input[name="name"]').value = data.name || "";

    // Convert and set birthday
    if (data.birthday) {
      console.log(data.birthday);

      let formattedDate = formatDate(data.birthday);
      console.log(formattedDate);
      document.querySelector('input[name="birthday"]').value = formattedDate;

      // Calculate and set age
      let age = calculateAge(formattedDate);
      console.log(age);
      document.querySelector('input[name="age"]').value = age;
    }

    document.querySelector('input[name="address"]').value = data.address;
    document.querySelector('input[name="contact"]').value = data.contact;
    document.querySelector('input[name="email"]').value = data.email;
    document.querySelector('input[name="viber"]').value = data.viber;
    document.querySelector('input[name="informant"]').value = data.informant;
    document.querySelector('input[name="informant_relation"]').value =
      data.informant_relation;

    if (document.getElementById("consultNew") && data.old_new === "New") {
      document.getElementById("consultNew").checked = true;
    } else if (
      document.getElementById("consultOld") &&
      data.old_new === "Old"
    ) {
      document.getElementById("consultOld").checked = true;
    }

    // Handle consultation type selection
    if (
      document.getElementById("consultFTF") &&
      data.consultation === "Face to face"
    ) {
      document.getElementById("consultFTF").checked = true;
    } else if (
      document.getElementById("consultTelecon") &&
      data.consultation === "Teleconsultation"
    ) {
      document.getElementById("consultTelecon").checked = true;
    }

    document.querySelector('input[name="refer_from"]').value = data.refer_from;
    // Set hidden record_id field
    document.querySelector('input[name="record_id"]').value = recordId;
  })
  .catch((error) => console.error("Fetch error:", error));

// const followUp = document.getElementById("consultFollowUp");
// const consultDate = document.getElementById("consultDate");

// if ((followUp.checked = true)) {
//   consultDate = require;
// }

const followUp = document.getElementById("consultFollowUp");
const consultDate = document.getElementById("consultDate");

// Use an event listener to check the checkbox status
// followUp.addEventListener("change", function () {
// if (followUp.checked) {
//   // Check if consultDate input is empty
//   if (!consultDate.value) {
//     // alert("Please fill in the consultation date.");
//     consultDate.consultDate.focus(); // Optionally set focus on the input
//   }
// }
// });
