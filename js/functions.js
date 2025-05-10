// DATE FORMAT CONVERTION
function formatDate(inputDate) {
  const months = {
    Jan: "01",
    Feb: "02",
    Mar: "03",
    Apr: "04",
    May: "05",
    Jun: "06",
    Jul: "07",
    Aug: "08",
    Sep: "09",
    Oct: "10",
    Nov: "11",
    Dec: "12",
  };

  const parts = inputDate.split("-");
  if (parts.length !== 3) {
    console.error("Invalid date format:", inputDate);
    return "";
  }

  let day, month, year;

  // Check if the middle part is a month name (e.g., "Nov")
  if (isNaN(parts[1]) && months[parts[1]]) {
    // Format: DD-MMM-YYYY (e.g., 10-Nov-1999)
    day = parts[0].padStart(2, "0");
    month = months[parts[1]];
    year = parts[2];
  } else {
    // Format: DD-MM-YYYY (e.g., 10-10-1960)
    day = parts[0].padStart(2, "0");
    month = parts[1].padStart(2, "0");
    year = parts[2];
  }

  // Return in YYYY-MM-DD format (required by <input type="date">)
  return `${year}-${month}-${day}`;
}

// calculate age from birthday
function calculateAge(birthday) {
  let birthDate = new Date(birthday); // Convert "YYYY-MM-DD" to Date object
  let today = new Date(); // Get today's date

  let age = today.getFullYear() - birthDate.getFullYear(); // Year difference
  let monthDiff = today.getMonth() - birthDate.getMonth(); // Month difference
  let dayDiff = today.getDate() - birthDate.getDate(); // Day difference

  // If birthday hasn't occurred yet this year, subtract 1 from age
  if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) {
    age--;
  }

  return age;
}

if (document.querySelectorAll(".birthdayInput")) {
  document.querySelectorAll(".birthdayInput").forEach((input) => {
    input.addEventListener("input", function () {
      let birthday = this.value;
      let ageOutputId = this.getAttribute("data-age-output");
      let age = calculateAge(birthday);

      document.getElementById(ageOutputId).value = age;
      document.getElementById(ageOutputId).readOnly = true;

      if (age <= 0) {
        document.getElementById(ageOutputId).value = "";
        document.getElementById(ageOutputId).readOnly = false;
      }
    });
  });
}

// Get all client select elements
const clientSelects = document.querySelectorAll(".clientSelection");

// Loop through each client select element and add an event listener
clientSelects.forEach((clientSelect) => {
  const consultationSelectId = clientSelect.getAttribute("data-consult-type");
  const consultationSelect = document.getElementById(consultationSelectId);

  // Event listener for the "Type of Client" dropdown
  clientSelect.addEventListener("change", function () {
    if (clientSelect.value === "New") {
      consultationSelect.value = "Face to Face"; // Default to "Face to Face"
      consultationSelect.disabled = true; // Disable the consultation select
    } else if (clientSelect.value === "Old") {
      consultationSelect.disabled = false; // Enable the consultation select
    }
  });
});

document.addEventListener("DOMContentLoaded", function () {
  // Toggle modal visibility
  function toggleModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.classList.toggle("show");

      // Close when clicking outside the modal content
      modal.onclick = function (event) {
        if (event.target === modal) {
          modal.classList.remove("show");
        }
      };
    }
  }

  // Attach event listeners to all trigger buttons with data-modal-target
  const triggerButtons = document.querySelectorAll("[data-modal-target]");
  triggerButtons.forEach((btn) => {
    btn.addEventListener("click", function () {
      const targetId = this.getAttribute("data-modal-target");
      toggleModal(targetId);
    });
  });
});
