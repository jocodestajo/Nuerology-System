// Function to handle CHECKBOXES behavior per TABLE //////////////////////////////////////
if (
  document.querySelectorAll(".checkbox-header") &&
  document.querySelectorAll(".checkbox-header").length > 0
) {
  document.querySelectorAll(".checkbox-header").forEach((headerCheckbox) => {
    if (headerCheckbox) {
      const table = headerCheckbox.closest("table");

      // Event listener for "Select All" checkbox
      headerCheckbox.addEventListener("change", function () {
        // Only select visible checkboxes (rows that aren't hidden)
        const visibleRowCheckboxes = table.querySelectorAll(
          "tbody tr:not([style*='display: none']) .checkbox:not(.checkbox-header)"
        );

        visibleRowCheckboxes.forEach((checkbox) => {
          checkbox.checked = this.checked;
        });

        // Update the buttons visibility
        handleCheckedItems();
      });
    }
  });
}

// Get the checkbox elements and button div
const checkboxes = document.querySelectorAll(".checkbox");
const buttonDiv = document.querySelector(".btn-div-checkbox");

if (checkboxes && buttonDiv) {
  // Add event listener to each checkbox
  checkboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", function () {
      // Check if at least one checkbox is checked
      const atLeastOneChecked = checkboxes.forEach((cb) => cb.checked);

      // Show or hide the button div based on whether any checkbox is checked
      if (atLeastOneChecked) {
        buttonDiv.classList.add("show");
      } else {
        buttonDiv.classList.remove("show");
      }
    });
  });
}

// DATE FILTERING
function filterTable(tableId, dayFilterId, monthFilterId, yearFilterId) {
  const table = document.getElementById(tableId);
  const dayFilter = document.getElementById(dayFilterId);
  const monthFilter = document.getElementById(monthFilterId);
  const yearFilter = document.getElementById(yearFilterId);
  const tbody = table.querySelector("tbody");
  const tableRows = tbody.querySelectorAll("tr:not(.no-records-row)");

  const selectedDay = dayFilter.value;
  const selectedMonth = monthFilter.value;
  const selectedYear = yearFilter.value;

  let visibleRowCount = 0;

  // First remove any existing "no records" row
  const existingNoRecordsRow = tbody.querySelector(".no-records-row");
  if (existingNoRecordsRow) {
    existingNoRecordsRow.remove();
  }

  tableRows.forEach((row) => {
    const scheduleCell = row.querySelector(".th-schedule");
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

  // Add "No records found" row if no visible records
  if (visibleRowCount === 0) {
    const noRecords = document.createElement("tr");
    noRecords.className = "no-records-row";
    noRecords.innerHTML = `<td colspan="7" style="text-align: center; font-size: 1.5rem; padding: 16px;">No records found</td>`;
    tbody.appendChild(noRecords);
  }
}

// Add this function to check initial table state
function checkTableState(tableId) {
  const table = document.getElementById(tableId);
  if (!table) return;

  const tbody = table.querySelector("tbody");
  const rows = tbody.querySelectorAll("tr:not(.no-records-row)");

  // Remove any existing no-records-row first
  const existingNoRecordsRow = tbody.querySelector(".no-records-row");
  if (existingNoRecordsRow) {
    existingNoRecordsRow.remove();
  }

  // Check if there are any rows at all
  if (rows.length === 0) {
    const noRecords = document.createElement("tr");
    noRecords.className = "no-records-row";
    noRecords.innerHTML = `<td colspan="7" style="text-align: center; font-size: 1.5rem; padding: 16px;">No records found</td>`;
    tbody.appendChild(noRecords);
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

  // Check initial state of all tables
  tableConfigs.forEach((config) => {
    checkTableState(config.tableId);
  });

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

// Function to handle checked items across all tables
function handleCheckedItems() {
  const tables = ["table1", "table2", "table3"];
  const checkedIds = [];
  let hasCheckedItems = false;

  tables.forEach((tableId) => {
    const table = document.getElementById(tableId);
    if (table) {
      // Only get checkboxes from visible rows
      const visibleCheckboxes = table.querySelectorAll(
        "tbody tr:not([style*='display: none']) .checkbox:not(.checkbox-header)"
      );

      visibleCheckboxes.forEach((checkbox) => {
        if (checkbox.checked) {
          hasCheckedItems = true;
          const row = checkbox.closest("tr");
          if (row && row.id) {
            const recordId = row.id.replace("patient_", "");
            checkedIds.push(recordId);
          }
        }
      });
    }
  });

  const buttonDiv = document.querySelector(".btn-div-checkbox");

  if (hasCheckedItems) {
    buttonDiv.classList.add("show");
  } else {
    buttonDiv.classList.remove("show");
  }

  return checkedIds;
}

// Add event listener to all checkboxes in all tables
document.addEventListener("DOMContentLoaded", function () {
  const tables = ["table1", "table2", "table3"];

  tables.forEach((tableId) => {
    const table = document.getElementById(tableId);
    if (table) {
      // Handle header checkbox
      const headerCheckbox = table.querySelector("thead .checkbox-header");
      if (headerCheckbox) {
        headerCheckbox.addEventListener("change", function () {
          // Only select visible checkboxes
          const visibleBodyCheckboxes = table.querySelectorAll(
            "tbody tr:not([style*='display: none']) .checkbox"
          );
          visibleBodyCheckboxes.forEach((checkbox) => {
            checkbox.checked = this.checked;
          });
          handleCheckedItems();
        });
      }

      // Handle individual checkboxes
      const bodyCheckboxes = table.querySelectorAll("tbody .checkbox");
      bodyCheckboxes.forEach((checkbox) => {
        checkbox.addEventListener("change", function () {
          // Update header checkbox based on visible checkboxes only
          const visibleBodyCheckboxes = table.querySelectorAll(
            "tbody tr:not([style*='display: none']) .checkbox"
          );
          const allVisibleChecked = [...visibleBodyCheckboxes].every(
            (cb) => cb.checked
          );
          if (headerCheckbox) {
            headerCheckbox.checked = allVisibleChecked;
          }
          handleCheckedItems();
        });
      });
    }
  });
});

// Function to uncheck all header checkboxes
function uncheckHeaderCheckboxes() {
  document.querySelectorAll(".custom-checkbox").forEach((customCheckbox) => {
    customCheckbox.checked = false;
  });
}

// Function to handle cancellation for both individual and bulk actions
function handleCancellation(recordIds) {
  const modal = document.getElementById("confirmModal");
  modal.style.display = "block";

  document.getElementById("confirmCancel").onclick = function () {
    Promise.all(
      recordIds.map((recordId) =>
        fetch("api/post/updateData.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: "cancel_record=" + encodeURIComponent(recordId),
        }).then((response) => response.json())
      )
    )
      .then((results) => {
        const allSuccess = results.every((data) => data.success);
        if (allSuccess) {
          recordIds.forEach((id) => {
            const row = document.getElementById(`patient_${id}`);
            if (row) row.remove();
          });
          document.querySelector(".btn-div-checkbox").classList.remove("show");
          uncheckHeaderCheckboxes(); // Uncheck header checkboxes
          alert("Selected appointments cancelled successfully!");
        } else {
          alert("Some appointments could not be cancelled.");
        }
        modal.style.display = "none";
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("An error occurred while cancelling appointments.");
        modal.style.display = "none";
      });
  };

  document.getElementById("cancelCancel").onclick = function () {
    modal.style.display = "none";
  };

  document.querySelector(".close").onclick = function () {
    modal.style.display = "none";
  };
}

// Individual cancel buttons handler
document.addEventListener("click", function (e) {
  if (
    e.target.classList.contains("update-cancelled") &&
    e.target.classList.contains("action-img")
  ) {
    const recordId = e.target.getAttribute("data-id");
    if (recordId) {
      handleCancellation([recordId]);
    }
  }
});

// Bulk cancel button handler
document
  .querySelector(".btn-div-checkbox .update-cancel")
  .addEventListener("click", function () {
    const checkedIds = handleCheckedItems();
    if (checkedIds.length === 0) return;
    handleCancellation(checkedIds);
  });

// Initialize the calendar
initRescheduleCalendar();

// Add the reschedule calendar functionality
function initRescheduleCalendar() {
  // Update the reschedule button handler /////////////////////////////////////////////////////////////////////////
  document
    .querySelector(".update-reschedule")
    .addEventListener("click", function () {
      const checkedIds = handleCheckedItems();

      if (checkedIds.length === 0) return;

      // Show the reschedule modal
      const modal = document.getElementById("rescheduleModal");
      modal.style.display = "block";

      const calModal = document.getElementById("rescheduleCalendarContainer");
      calModal.style.display = "block";
      updateCalendar();
    });

  let currentDate = new Date();
  const calendarContainer = document.getElementById(
    "rescheduleCalendarContainer"
  );
  // const calendarTrigger = document.querySelector(
  //   ".reschedule-calendar-trigger"
  // );
  const dateInput = document.getElementById("reschedule-date");
  const modal = document.getElementById("rescheduleModal");

  // Add button handlers
  const prevButton = calendarContainer.querySelector(".btn-prev");
  const nextButton = calendarContainer.querySelector(".btn-next");

  prevButton.addEventListener("click", () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    updateCalendar();
  });

  nextButton.addEventListener("click", () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    updateCalendar();
  });

  function updateCalendar() {
    const table = document.getElementById("rescheduleCalendarTable");
    const monthTitle = document.getElementById("rescheduleMonthTitle");

    table.innerHTML = "";

    // Add header row
    const headerRow = table.insertRow();
    ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"].forEach((day) => {
      const cell = headerRow.insertCell();
      cell.textContent = day;
      cell.className = "calendarTable-header";
    });

    // Calculate dates
    const firstDay = new Date(
      currentDate.getFullYear(),
      currentDate.getMonth(),
      1
    ).getDay();
    const lastDate = new Date(
      currentDate.getFullYear(),
      currentDate.getMonth() + 1,
      0
    ).getDate();

    const monthNames = [
      "January",
      "February",
      "March",
      "April",
      "May",
      "June",
      "July",
      "August",
      "September",
      "October",
      "November",
      "December",
    ];
    monthTitle.textContent = `${
      monthNames[currentDate.getMonth()]
    } ${currentDate.getFullYear()}`;

    let dayCount = 1;
    for (let i = 0; dayCount <= lastDate; i++) {
      const row = table.insertRow();
      for (let j = 0; j < 7; j++) {
        const cell = row.insertCell();
        if (i === 0 && j < firstDay) {
          cell.textContent = "";
        } else if (dayCount <= lastDate) {
          cell.textContent = dayCount;
          cell.className = "clickable-date";
          cell.onclick = () =>
            handleDateClick(
              new Date(
                currentDate.getFullYear(),
                currentDate.getMonth(),
                cell.textContent
              )
            );
          dayCount++;
        }
      }
    }
  }

  function handleDateClick(selectedDate) {
    // Fix the date offset issue by setting the time to noon
    selectedDate.setHours(12, 0, 0, 0);

    // Format the date manually to avoid timezone issues
    const year = selectedDate.getFullYear();
    const month = String(selectedDate.getMonth() + 1).padStart(2, "0");
    const day = String(selectedDate.getDate()).padStart(2, "0");
    const formattedDate = `${year}-${month}-${day}`;

    dateInput.value = formattedDate;
    // calendarContainer.style.display = "none";
  }

  // // Show/hide calendar on trigger click
  // calendarTrigger.addEventListener("click", function (e) {
  //   e.preventDefault();
  //   // console.log(calendarContainer.style.display == "none");
  //   if (calendarContainer.style.display === "none") {
  //     // console.log(calendarContainer);
  //     calendarContainer.style.display = "block";
  //     updateCalendar();
  //     return;
  //   } else {
  //     calendarContainer.style.display = "none";
  //     return;
  //   }
  // });

  // Handle confirm reschedule
  document.getElementById("confirmReschedule").onclick = function () {
    const newDate = dateInput.value;
    if (!newDate) {
      alert("Please select a new date");
      return;
    }

    const checkedIds = handleCheckedItems();
    Promise.all(
      checkedIds.map((recordId) =>
        fetch("api/post/updateData.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: `reschedule_record=${encodeURIComponent(
            recordId
          )}&new_date=${encodeURIComponent(newDate)}`,
        }).then((response) => response.json())
      )
    )
      .then((results) => {
        const allSuccess = results.every((data) => data.success);
        if (allSuccess) {
          // Update the date in the table for each rescheduled appointment
          checkedIds.forEach((id) => {
            const row = document.getElementById(`patient_${id}`);
            if (row) {
              const scheduleCell = row.querySelector(".th-schedule");
              if (scheduleCell) {
                scheduleCell.textContent = newDate;
                // Add a highlight effect
                scheduleCell.style.backgroundColor = "#e6ffe6";
                setTimeout(() => {
                  scheduleCell.style.backgroundColor = "";
                }, 2000);
              }
            }
          });

          document.querySelector(".btn-div-checkbox").classList.remove("show");
          // clear date input value
          document.getElementById("reschedule-date").value = "";
          uncheckHeaderCheckboxes();
          alert("Appointments rescheduled successfully!");
        } else {
          alert("Some appointments could not be rescheduled.");
        }
        modal.style.display = "none";
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("An error occurred while rescheduling appointments.");
      });
  };

  // Handle cancel button
  document.getElementById("cancelReschedule").onclick = function () {
    if (modal) {
      modal.style.display = "none";
      // clear date input value
      document.getElementById("reschedule-date").value = "";
    }
  };

  // Close modal when clicking the X
  document.querySelector("#rescheduleModal .close-btn").onclick = function () {
    modal.style.display = "none";
  };
}

// Close modal when clicking the X
document.querySelector(".close").onclick = function () {
  modal.style.display = "none";
};

// UPDATE DATA AS APPROVE ////////////////////////////////////////////////////////////////////////
document.querySelectorAll(".update-approve").forEach(function (button) {
  button.addEventListener("click", function () {
    const recordId = this.getAttribute("data-id");
    const row = document.getElementById(`patient_${recordId}`);

    fetch("api/post/updateData.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: "approve_record=" + encodeURIComponent(recordId),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          if (row) row.remove();
          alert("Appointment Approved!");
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
    const recordId = this.getAttribute("data-id");
    const row = document.getElementById(`patient_${recordId}`);

    fetch("api/post/updateData.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: "processed_record=" + encodeURIComponent(recordId),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          if (row) row.remove();
          alert("Appointment Processed");
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
