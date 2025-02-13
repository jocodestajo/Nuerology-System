async function searchData() {
  const query = document.getElementById("searchQuery").value;

  if (!query.trim()) {
    alert("Please enter name, hrn, consultation or status type to search.");
    return;
  }

  // Clear previous results
  document.getElementById("pendingTable").innerHTML = "";
  document.getElementById("faceToFaceTable").innerHTML = "";
  document.getElementById("teleconsultationTable").innerHTML = "";
  document.getElementById("processedTable").innerHTML = "";
  document.getElementById("cancelledTable").innerHTML = "";

  // Reset the buttons to their default state (enabled and no extra padding)
  resetScrollButtons();

  try {
    const response = await fetch(`searchQuery.php?query=${query}`);
    const appointments = await response.json();

    const pendingAppointments = appointments.filter(
      (app) => app.status === "pending"
    );
    const faceToFaceAppointments = appointments.filter(
      (app) => app.status === "approved" && app.consultation === "Face to face"
    );
    const teleconsultationAppointments = appointments.filter(
      (app) =>
        app.status === "approved" && app.consultation === "Teleconsultation"
    );
    const processedAppointments = appointments.filter(
      (app) => app.status === "processed"
    );
    const cancelledAppointments = appointments.filter(
      (app) => app.status === "cancelled"
    );

    displayTable("pendingTable", "Pending Appointments", pendingAppointments);
    displayTable(
      "faceToFaceTable",
      "Face to Face Appointments",
      faceToFaceAppointments
    );
    displayTable(
      "teleconsultationTable",
      "Teleconsultation Appointments",
      teleconsultationAppointments
    );
    displayTable(
      "processedTable",
      "Processed Appointments",
      processedAppointments
    );
    displayTable(
      "cancelledTable",
      "Cancelled Appointments",
      cancelledAppointments
    );

    attachViewButtonListeners();
  } catch (error) {
    console.error("Error fetching data: ", error);
    alert("There was an error processing the search.");
  }
}

function displayTable(tableId, title, appointments) {
  const table = document.getElementById(tableId);
  if (appointments.length > 0) {
    let tableHtml = `<h3 class="tableResultTitle">${title}</h3>
        <table border="1">
            <thead>
                <tr>
                    <th class="th-hrn">HRN</th>
                    <th class="th-name">Name</th>
                    <th class="th-schedule">Schedule</th>
                    <th class="th-complaint">Complaint</th>
                    <th class="th-action border-right">Action</th>
                </tr>
            </thead>
        <tbody>`;

    appointments.forEach((app) => {
      tableHtml += `<tr><td>${app.hrn}</td><td class="th-name">${app.name}</td><td>${app.date_sched}</td><td>${app.complaint}</td>
            <td class="th-action action border-right">
                <img src="img/check-circle.png" class="action-img update-processed margin-right" alt="Approve" data-id="${app.id}">
                <img src="img/edit.png" class="action-img view-button margin-right" alt="View" data-record-id="${app.id}">
                <img src="img/cancel.png" class="action-img update-cancelled" alt="Cancel" data-id="${app.id}">
            </td></tr>`;
    });

    tableHtml += "</tbody></table>";
    table.innerHTML = tableHtml;

    attachApproveButtonListeners();
    attachCancelButtonListeners();

    // Enable the corresponding button and ensure padding
    enableScrollButton(tableId);
  } else {
    // table.innerHTML = `<p>No results found.</p>`;
    disableScrollButton(tableId);
  }
}

// Function to reset the scroll buttons to their default state (enabled and no extra padding)
function resetScrollButtons() {
  const buttons = document.querySelectorAll(".scrollBtn");
  buttons.forEach((button) => {
    button.classList.remove("disabled");
    button.style.paddingBottom = "0";
  });
}

// Function to disable a button when the table is empty
function disableScrollButton(tableId) {
  const button = document.querySelector(`a[href="#${tableId}"]`);
  if (button) {
    button.classList.add("disabled");
    button.style.paddingBottom = "0";
  }
}

// Function to enable a button when the table has data
function enableScrollButton(tableId) {
  const button = document.querySelector(`a[href="#${tableId}"]`);
  if (button) {
    button.classList.remove("disabled");
    button.style.paddingBottom = "10px"; // Adjust padding if there are results
  }
}

// Function to attach event listeners for view buttons
function attachViewButtonListeners() {
  var viewButtons = document.querySelectorAll(".view-button");

  viewButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      var recordId = this.getAttribute("data-record-id");

      // console.log(recordId);

      // Make an AJAX request to fetch the data for this record
      fetch("api/get/fetch_record.php?id=" + recordId)
        .then((response) => response.json())
        .then((data) => {
          // console.log(data);

          // Fill the modal form with the data
          document.querySelector('input[name="hrn"]').value = data.hrn;
          document.querySelector('input[name="name"]').value = data.name;
          document.querySelector('input[name="age"]').value = data.age;
          document.querySelector('input[name="address"]').value = data.address;
          document.querySelector('input[name="birthday"]').value =
            data.birthday;
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
          document.querySelector('textarea[name="history"]').value =
            data.history;
          document.querySelector('input[name="referal"]').value = data.referal;

          // Set the correct option in the select inputs
          document.querySelector('select[name="typeofappoint"]').value =
            data.appointment_type;
          document.querySelector('select[name="old_new"]').value = data.old_new;
          document.querySelector('select[name="complaint"]').value =
            data.complaint;

          // Handle enabling/disabling of teleconsultation based on old_new value
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

          document.querySelector('input[name="record_id"]').value = recordId;

          document.getElementById("view-hrn").readOnly = true;
          document.getElementById("view-name").readOnly = true;
          document.getElementById("view-age").readOnly = true;
          document.getElementById("view-birthday").readOnly = true;
          document.getElementById("view-address").readOnly = true;
          document.getElementById("view-clientSelect").readOnly = true;

          modal.style.display = "block";
        })
        .catch((error) => console.log(error));
    });
  });
}

// Function to attach event listeners for approve buttons
function attachApproveButtonListeners() {
  var approveButtons = document.querySelectorAll(".update-processed");

  approveButtons.forEach((button) => {
    button.addEventListener("click", function () {
      var recordId = this.getAttribute("data-id");

      // Send AJAX request using Fetch API to approve the record
      fetch("api/post/updateData.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded", // Specify content type
        },
        body: "approve_record=" + encodeURIComponent(recordId), // Send the record ID
      })
        .then((response) => response.json()) // Parse the JSON response
        .then((data) => {
          if (data.success) {
            alert("Appointment Approved!");
            removeData(recordId); // You can implement this function to remove the record from the table
          } else {
            alert("Error: " + data.message);
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          alert("An error occurred.");
        });
    });
  });
}

function removeData(recordId) {
  const tableIds = [
    "pendingTable",
    "faceToFaceTable",
    "teleconsultationTable",
    "processedTable",
    "cancelledTable",
  ];

  tableIds.forEach((tableId) => {
    const table = document.getElementById(tableId);
    if (table) {
      const rows = table.querySelectorAll("tr");
      rows.forEach((row) => {
        if (
          row.querySelector(".update-processed") &&
          row.querySelector(".update-processed").getAttribute("data-id") ===
            recordId
        ) {
          row.remove();
        }
      });
    }
  });
}

// Function to attach event listeners for cancel buttons
function attachCancelButtonListeners() {
  var cancelButtons = document.querySelectorAll(".update-cancelled");

  cancelButtons.forEach((button) => {
    button.addEventListener("click", function () {
      var recordId = this.getAttribute("data-id");

      // Show the modal
      var modal = document.getElementById("confirmModal");
      modal.style.display = "block";

      // Handle the "Yes, Cancel" button
      document
        .getElementById("confirmCancel")
        .addEventListener("click", function () {
          // Send AJAX request using Fetch API to cancel the record
          fetch("api/post/updateData.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded", // Specify content type
            },
            body: "cancel_record=" + encodeURIComponent(recordId), // Send the record ID
          })
            .then((response) => response.json()) // Parse the JSON response
            .then((data) => {
              if (data.success) {
                alert("Appointment Cancelled!");
                removeData(recordId); // Remove the cancelled record from the table
              } else {
                alert("Error: " + data.message);
              }
            })
            .catch((error) => {
              console.error("Error:", error);
              alert("An error occurred.");
            });

          // Close the modal after the request
          modal.style.display = "none";
        });

      // Handle the "No" button (close the modal without doing anything)
      document
        .getElementById("cancelCancel")
        .addEventListener("click", function () {
          modal.style.display = "none";
        });

      // Close the modal when clicking on the "X"
      document.querySelector(".close").addEventListener("click", function () {
        modal.style.display = "none";
      });

      // Close the modal if the user clicks outside of the modal content
      window.addEventListener("click", function (event) {
        if (event.target === modal) {
          modal.style.display = "none";
        }
      });
    });
  });
}
