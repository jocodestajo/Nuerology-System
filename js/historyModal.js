// Function to open the history modal
function openHistoryModal(recordId) {
  // Get the modal element
  const modal = document.getElementById("historyModal");

  // Fetch the patient data
  fetch(`api/get/fetch_history.php?id=${recordId}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        const patient = data.data;

        // Update the record ID in the form
        const form = document.getElementById("historyForm");
        form.querySelector('input[name="record_id"]').value = recordId;

        // Populate the form fields
        form.querySelector('input[name="name"]').value = patient.name;
        form.querySelector('input[name="hrn"]').value = patient.hrn;
        form.querySelector('input[name="birthday"]').value = patient.birthday;
        form.querySelector('input[name="age"]').value = patient.age;
        form.querySelector('input[name="address"]').value = patient.address;
        form.querySelector('input[name="contact"]').value = patient.contact;
        form.querySelector('input[name="email"]').value = patient.email;
        form.querySelector('input[name="viber"]').value = patient.viber;
        form.querySelector('select[name="typeofappoint"]').value =
          patient.appointment_type;
        form.querySelector('select[name="consultation"]').value =
          patient.consultation;
        form.querySelector('input[name="date_sched"]').value =
          patient.date_sched;
        form.querySelector('select[name="status"]').value = patient.status;
        form.querySelector('textarea[name="complaint"]').value =
          patient.complaint;
        form.querySelector('textarea[name="history"]').value = patient.history;
        form.querySelector('input[name="blood_pressure"]').value =
          patient.blood_pressure;
        form.querySelector('input[name="temperature"]').value =
          patient.temperature;
        form.querySelector('input[name="pulse_rate"]').value =
          patient.pulse_rate;
        form.querySelector('input[name="respiratory_rate"]').value =
          patient.respiratory_rate;

        // Show the modal
        modal.style.display = "block";
      } else {
        alert("Error loading patient data: " + data.message);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Error loading patient data");
    });

  // Add event listener to close button
  const closeBtn = modal.querySelector(".close-btn");
  closeBtn.onclick = function () {
    closeHistoryModal();
  };

  // Close modal when clicking outside
  window.onclick = function (event) {
    if (event.target == modal) {
      closeHistoryModal();
    }
  };

  // Handle form submission
  const form = document.getElementById("historyForm");
  form.onsubmit = function (e) {
    e.preventDefault();
    updateHistory(form);
  };
}

// Function to close the history modal
function closeHistoryModal() {
  const modal = document.getElementById("historyModal");
  if (modal) {
    modal.style.display = "none";
  }
}

// Function to update history
function updateHistory(form) {
  const formData = new FormData(form);

  fetch("api/post/updateHistory.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        // Show success message
        alert("Record updated successfully");
        // Close the modal
        closeHistoryModal();
        // Refresh the table
        location.reload(); // Reload the page to refresh the table
      } else {
        // Show error message
        alert("Error updating record: " + data.message);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Error updating record");
    });
}

// Add event listeners when the document is loaded
document.addEventListener("DOMContentLoaded", function () {
  // Add click event listener to the document for event delegation
  document.addEventListener("click", function (e) {
    // Check if the clicked element is the view button image
    if (
      e.target.classList.contains("view-history") &&
      e.target.tagName === "IMG"
    ) {
      const recordId = e.target.getAttribute("data-record-id");
      if (recordId) {
        openHistoryModal(recordId);
      }
    }
  });
});
