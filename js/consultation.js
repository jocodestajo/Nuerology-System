// Get the URL parameters
const urlParams = new URLSearchParams(window.location.search);

// Get the 'id' value
const recordId = urlParams.get("id");
// console.log(recordId);

// Make an AJAX request to fetch the data for this record
if (recordId) {
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
      // console.log("Parsed JSON:", data); // Debugging output

      if (!data || Object.keys(data).length === 0) {
        throw new Error("Empty or invalid JSON response");
      }

      document.querySelector('input[name="consultStart"]').value =
        data.consult_start || "";
      document.querySelector('input[name="consultEnd"]').value =
        data.consult_end || "";
      document.querySelector('input[name="educStart"]').value =
        data.educ_start || "";
      document.querySelector('input[name="educEnd"]').value =
        data.educ_end || "";

      document.querySelector('input[name="consultant_1"]').value =
        data.doctor || "";
      document.querySelector('input[name="consultant_2"]').value =
        data.nurse || "";
      document.querySelector('input[name="name"]').value = data.name || "";

      if (data.birthday) {
        document.querySelector('input[name="birthday"]').value = data.birthday;

        let age = calculateAge(data.birthday); // Ensure calculateAge() also expects YYYY-MM-DD
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

      if (document.getElementById("consultRX") && data.rx_mc === "RX") {
        document.getElementById("consultRX").checked = true;
      } else if (document.getElementById("consultMC") && data.rx_mc === "MC") {
        document.getElementById("consultMC").checked = true;
      }

      document.querySelector('select[name="classification"]').value =
        data.classification;

      if (data.refer_from != "") {
        document.querySelector('select[name="refer_from"]').value =
          data.refer_from;
      }

      if (data.refer_to != "") {
        document.querySelector('select[name="refer_to"]').value = data.refer_to;
      }
      // Set hidden record_id field
      document.querySelector('input[name="record_id"]').value = recordId;
    })
    .catch((error) => console.error("Fetch error:", error));
}
