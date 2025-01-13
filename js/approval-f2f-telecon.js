// Function to handle CHECKBOXES behavior per TABLE //////////////////////////////////////
document.querySelectorAll(".checkbox-header").forEach((headerCheckbox) => {
  // Select all checkboxes in the current table
  const table = headerCheckbox.closest("table");
  const rowCheckboxes = table.querySelectorAll(
    ".checkbox:not(.checkbox-header)"
  );

  // Event listener for "Select All" checkbox
  headerCheckbox.addEventListener("change", function () {
    rowCheckboxes.forEach((checkbox) => (checkbox.checked = this.checked));
  });

  // Event listener for individual row checkboxes
  rowCheckboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", function () {
      // If any checkbox is unchecked, uncheck the header checkbox
      if (!this.checked) {
        headerCheckbox.checked = false;
      }
      // If all checkboxes are checked, check the header checkbox
      else if ([...rowCheckboxes].every((checkbox) => checkbox.checked)) {
        headerCheckbox.checked = true;
      }
    });
  });
});

function filterTable(tableId, dayFilterId, monthFilterId, yearFilterId) {
  const table = document.getElementById(tableId);
  const dayFilter = document.getElementById(dayFilterId);
  const monthFilter = document.getElementById(monthFilterId);
  const yearFilter = document.getElementById(yearFilterId);
  const tableRows = table.querySelectorAll("tbody tr");

  const selectedDay = dayFilter.value;
  const selectedMonth = monthFilter.value;
  const selectedYear = yearFilter.value;

  let visibleRowCount = 0;

  tableRows.forEach((row) => {
    const scheduleCell = row.querySelector(".th-schedule");

    // Skip the row if .th-schedule does not exist
    if (!scheduleCell) {
      row.style.display = "none";
      return;
    }

    const scheduleDate = scheduleCell.textContent.trim();
    const [year, month, day] = scheduleDate.split("-");

    const matchesDay = selectedDay === "all" || day === selectedDay;
    const matchesMonth = selectedMonth === "all" || month === selectedMonth;
    const matchesYear = selectedYear === "all" || year === selectedYear;

    if (matchesDay && matchesMonth && matchesYear) {
      row.style.display = "";
      visibleRowCount++;
    } else {
      row.style.display = "none";
    }
  });

  const noRecordsRow = table.querySelector(".no-records-row");
  if (visibleRowCount === 0) {
    if (!noRecordsRow) {
      const noRecords = document.createElement("tr");
      noRecords.className = "no-records-row";
      noRecords.innerHTML = `<td colspan="7" style="text-align: center; font-size: 1.5rem; padding: 16px;">No records found</td>`;
      table.querySelector("tbody").appendChild(noRecords);
    }
  } else {
    if (noRecordsRow) {
      noRecordsRow.remove();
    }
  }
}

document.addEventListener("DOMContentLoaded", function () {
  const tableConfigs = [
    {
      tableId: "table1",
      dayId: "dayFilter1",
      monthId: "monthFilter1",
      yearId: "yearFilter1",
      defaultToCurrentDay: false,
    },
    {
      tableId: "table2",
      dayId: "dayFilter2",
      monthId: "monthFilter2",
      yearId: "yearFilter2",
      defaultToCurrentDay: true,
    },
    {
      tableId: "table3",
      dayId: "dayFilter3",
      monthId: "monthFilter3",
      yearId: "yearFilter3",
      defaultToCurrentDay: true,
    },
  ];

  const currentDate = new Date();
  const currentYear = currentDate.getFullYear();
  const currentMonth = (currentDate.getMonth() + 1).toString().padStart(2, "0");
  const currentDay = currentDate.getDate().toString().padStart(2, "0");

  function setupTable({
    tableId,
    dayId,
    monthId,
    yearId,
    defaultToCurrentDay,
  }) {
    const table = document.getElementById(tableId);
    const dayFilter = document.getElementById(dayId);
    const monthFilter = document.getElementById(monthId);
    const yearFilter = document.getElementById(yearId);

    function populateDropdowns() {
      const addShowAllOption = (dropdown, label) => {
        const option = document.createElement("option");
        option.value = "all";
        option.textContent = label;
        dropdown.appendChild(option);
      };

      for (let i = currentYear; i >= 2000; i--) {
        const option = document.createElement("option");
        option.value = i.toString();
        option.textContent = i;
        if (i === currentYear) option.selected = true;
        yearFilter.appendChild(option);
      }
      addShowAllOption(yearFilter, "Show All Years");

      const populateMonths = () => {
        monthFilter.innerHTML = "";
        addShowAllOption(monthFilter, "Show All Months");
        for (let i = 1; i <= 12; i++) {
          const option = document.createElement("option");
          option.value = i.toString().padStart(2, "0");
          option.textContent = new Date(0, i - 1).toLocaleString("default", {
            month: "long",
          });
          if (option.value === currentMonth)
            option.selected = defaultToCurrentDay;
          monthFilter.appendChild(option);
        }
      };

      const populateDays = () => {
        const selectedYear = parseInt(yearFilter.value) || currentYear;
        const selectedMonth =
          parseInt(monthFilter.value) || parseInt(currentMonth);
        const daysInMonth = new Date(selectedYear, selectedMonth, 0).getDate();

        dayFilter.innerHTML = "";
        addShowAllOption(dayFilter, "Show All Days");
        for (let i = 1; i <= daysInMonth; i++) {
          const option = document.createElement("option");
          option.value = i.toString().padStart(2, "0");
          option.textContent = i;
          if (option.value === currentDay && defaultToCurrentDay)
            option.selected = true;
          dayFilter.appendChild(option);
        }
      };

      populateMonths();
      populateDays();

      yearFilter.addEventListener("change", () => {
        populateMonths();
        populateDays();
        filterTable(tableId, dayId, monthId, yearId);
      });

      monthFilter.addEventListener("change", () => {
        populateDays();
        filterTable(tableId, dayId, monthId, yearId);
      });

      dayFilter.addEventListener("change", () =>
        filterTable(tableId, dayId, monthId, yearId)
      );
    }

    populateDropdowns();

    // Apply default filtering if needed
    if (defaultToCurrentDay) {
      filterTable(tableId, dayId, monthId, yearId);
    }
  }

  tableConfigs.forEach(setupTable);
});

// UPDATE DATA AS APPROVE ////////////////////////////////////////////////////////////////////////
document.querySelectorAll(".update-approve").forEach(function (button) {
  button.addEventListener("click", function () {
    // Get the record ID from the data-id attribute
    var recordId = this.getAttribute("data-id");

    // Send AJAX request using Fetch API
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
          removeData(recordId);
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

// UPDATE DATA AS PROCESSED ////////////////////////////////////////////////////////////////////////
document.querySelectorAll(".update-processed").forEach(function (button) {
  button.addEventListener("click", function () {
    // get the record ID from the data-id attribute
    const recordID = this.getAttribute("data-id");

    // send AJAX request
    fetch("api/post/updateData.php", {
      method: "POST",
      headers: {
        "content-Type": "application/x-www-form-urlencoded",
      },
      body: "processed_record=" + encodeURIComponent(recordID),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert("Appointment Processed");
          removeData(recordID);
        } else {
          alert("Error: " + data.message);
        }
      })
      .catch((error) => {
        console.errror("Error:", error);
        alert("An error occured.");
      });
  });
});

// UPDATE DATA AS CANCELLED with confirmation /////////////////////////////////////////////////////
document.querySelectorAll(".update-cancelled").forEach(function (button) {
  button.addEventListener("click", function () {
    // Get the record ID from the data-id attribute
    var recordId = this.getAttribute("data-id");

    // Show the modal
    var modal = document.getElementById("confirmModal");
    modal.style.display = "block";

    // Handle the "Yes, Cancel" button
    document
      .getElementById("confirmCancel")
      .addEventListener("click", function () {
        // Send AJAX request using Fetch API
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
              removeData(recordId);
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

function removeData(recordID) {
  document.getElementById(`patient_${recordID}`).remove();
}
