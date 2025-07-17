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
      // console.log("Raw response:", text); // Debugging output
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

      // document.querySelector('select[name="consultant_1_type"]').value =
      //   data.c1_type;
      document.querySelector('input[name="consultant_1"]').value =
        data.consultant_1 || "";
      // document.querySelector('select[name="consultant_2_type"]').value =
      //   data.c2_type;
      document.querySelector('input[name="consultant_2"]').value =
        data.consultant_2 || "";
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

      // --- Diagnosis tags rendering ---
      const tagsWrapper = document.getElementById("tagsWrapper");
      const diagnosisInput = document.getElementById("diagnosis");
      // Clear any existing tags
      if (tagsWrapper) tagsWrapper.innerHTML = "";
      if (data.diagnosis && tagsWrapper) {
        // Split by comma, trim each, and ignore empty
        data.diagnosis
          .split(",")
          .map((d) => d.trim())
          .filter(Boolean)
          .forEach((diag) => {
            const tag = document.createElement("div");
            tag.classList.add("tag");

            const text = document.createElement("span");
            text.textContent = diag;
            tag.appendChild(text);

            const removeBtn = document.createElement("span");
            removeBtn.textContent = "x";
            removeBtn.classList.add("remove-tag");
            removeBtn.onclick = () => {
              tag.remove();
              diagnosisInput && diagnosisInput.focus();
            };
            tag.appendChild(removeBtn);

            tagsWrapper.appendChild(tag);
          });
      }

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

      // Set checked checkboxes for classification by id
      if (data.classification && Array.isArray(data.classification)) {
        data.classification.forEach((id) => {
          const checkbox = document.querySelector(
            `input[type="checkbox"][name="classification[]"][value="${id}"]`
          );
          if (checkbox) checkbox.checked = true;
        });
      }

      if (data.refer_from != "") {
        document.querySelector('select[name="refer_from"]').value =
          data.refer_from;
      }

      if (data.refer_to != "") {
        const referToSelect = document.querySelector('select[name="refer_to"]');
        const otherInstituteInput = document.querySelector(
          'input[name="otherInstitute"]'
        );
        let found = false;
        if (referToSelect) {
          for (let i = 0; i < referToSelect.options.length; i++) {
            if (referToSelect.options[i].value === data.refer_to) {
              found = true;
              break;
            }
          }
        }
        if (found) {
          referToSelect.value = data.refer_to;
          if (otherInstituteInput) {
            otherInstituteInput.value = "";
            otherInstituteInput.style.display = "none";
          }
        } else {
          if (referToSelect) referToSelect.value = "Other";
          if (otherInstituteInput) {
            otherInstituteInput.value = data.refer_to;
            otherInstituteInput.style.display = "block";
          }
        }
      }

      // --- Medication and Quantity fetching ---
      const medicationContainer = document.getElementById(
        "medication-container"
      );
      if (medicationContainer && data.medication) {
        // Remove all but the first medication-entry
        const entries =
          medicationContainer.querySelectorAll(".medication-entry");
        entries.forEach((entry, idx) => {
          if (idx > 0) entry.remove();
        });
        // Clear the first entry's inputs
        const firstMedInput = medicationContainer.querySelector(
          'input[name="medication[]"]'
        );
        const firstQtyInput = medicationContainer.querySelector(
          'input[name="medQty[]"]'
        );
        if (firstMedInput) firstMedInput.value = "";
        if (firstQtyInput) firstQtyInput.value = "";

        // Split medication string only on commas followed by a space and a number
        const meds = data.medication
          .split(/, (?=\d)/)
          .map((m) => m.trim())
          .filter(Boolean);
        meds.forEach((med, idx) => {
          // Find the first space (between qty and medicine)
          const match = med.match(/^(\d+)\s+(.+)$/);
          let qty = "",
            medName = "";
          if (match) {
            qty = match[1];
            medName = match[2];
          } else {
            // fallback: treat all as name
            medName = med;
          }
          if (idx === 0) {
            if (firstMedInput) firstMedInput.value = medName;
            if (firstQtyInput) firstQtyInput.value = qty;
          } else {
            // Simulate add-medication button click to add new row
            const addBtn = document.getElementById("add-medication");
            if (addBtn) addBtn.click();
            // Fill the newly added row
            const allMedInputs = medicationContainer.querySelectorAll(
              'input[name="medication[]"]'
            );
            const allQtyInputs = medicationContainer.querySelectorAll(
              'input[name="medQty[]"]'
            );
            if (allMedInputs[idx]) allMedInputs[idx].value = medName;
            if (allQtyInputs[idx]) allQtyInputs[idx].value = qty;
          }
        });
      }

      document.querySelector('input[name="date_sched_def"]').value =
        data.date_sched;
      document.querySelector('input[name="appointment_type"]').value =
        data.appointment_type;

      // Set hidden record_id field
      document.querySelector('input[name="record_id"]').value = recordId;
    })
    .catch((error) => console.error("Fetch error:", error));
}
