// Function to open the history modal
function openHistoryModal(recordId) {
  // Load the modal content
  fetch(`includes/historyModal.php?id=${recordId}`)
    .then((response) => response.text())
    .then((html) => {
      // Add the modal to the document
      document.body.insertAdjacentHTML("beforeend", html);

      // Get the modal element
      const modal = document.getElementById("historyModal");

      // Show the modal
      modal.style.display = "block";

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
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}

// Function to close the history modal
function closeHistoryModal() {
  const modal = document.getElementById("historyModal");
  if (modal) {
    modal.remove();
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
