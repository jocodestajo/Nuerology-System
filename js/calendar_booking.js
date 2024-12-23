// Initialize calendar when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
  let currentDate = new Date();

  // Make changeMonth function global
  window.changeMonth = function (action) {
    // Prevent the default form submission
    event.preventDefault();

    if (action === "previous") {
      currentDate.setMonth(currentDate.getMonth() - 1);
    } else if (action === "next") {
      currentDate.setMonth(currentDate.getMonth() + 1);
    } else {
      currentDate = new Date();
    }
    updateCalendar();
  };

  // Define updateCalendar function globally
  function updateCalendar() {
    const table = document.getElementById("calendarTable");
    const monthTitle = document.getElementById("calendarMonthTitle");

    // Debug logs
    // console.log("Updating calendar");
    // console.log("Table element:", table);
    // console.log("Month title element:", monthTitle);

    if (!table || !monthTitle) {
      console.error("Calendar elements not found!");
      return;
    }

    // Get enabled weekdays from checkboxes
    const enabledDays = getEnabledWeekdays();

    // Clear existing table content
    table.innerHTML = "";

    // Add header row with days of week
    const headerRow = table.insertRow();
    ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"].forEach((day) => {
      const cell = headerRow.insertCell();
      cell.textContent = day;
      cell.className = "calendarTable-header";
    });

    // Calculate dates for the current month
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

    // Update month title
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

    // Fill in the calendar dates
    let dayCount = 1;
    for (let i = 0; dayCount <= lastDate; i++) {
      const row = table.insertRow();
      for (let j = 0; j < 7; j++) {
        const cell = row.insertCell();
        if (i === 0 && j < firstDay) {
          cell.textContent = "";
        } else if (dayCount <= lastDate) {
          cell.textContent = dayCount;

          // Check if this weekday is enabled
          const date = new Date(
            currentDate.getFullYear(),
            currentDate.getMonth(),
            dayCount
          );
          const isEnabled = enabledDays.includes(date.getDay());

          if (isEnabled) {
            cell.className = "clickable-date";
            cell.onclick = () => {
              handleDateClick(
                currentDate.getFullYear(),
                currentDate.getMonth(),
                cell.textContent
              );
            };
          } else {
            cell.className = "disabled-date";
          }
          dayCount++;
        }
      }
    }
  }

  // Handle date clicks
  function handleDateClick(year, month, dayCount) {
    const selectedDate = new Date(year, month, parseInt(dayCount), 12, 0, 0);
    const formattedDate = selectedDate.toISOString().split("T")[0];

    const dateInput = document.getElementById("date-sched");
    if (dateInput) {
      dateInput.value = formattedDate;
      // console.log(dateInput.value);
    }

    const calendarContainer = document.getElementById("calendarContainer");
    if (calendarContainer) {
      calendarContainer.style.display = "none";
    }
  }

  const calendarBtn = document.querySelector(".btn-trigger");
  const calendarContainer = document.getElementById("calendarContainer");

  if (!calendarBtn || !calendarContainer) {
    console.error("Calendar elements not found!");
    return;
  }

  // Add click event to the calendar button
  calendarBtn.addEventListener("click", function (e) {
    e.preventDefault(); // Prevent any default action

    // Toggle calendar visibility
    if (calendarContainer.style.display === "none") {
      calendarContainer.style.display = "block";
      updateCalendar(); // Update calendar when showing
    } else {
      calendarContainer.style.display = "none";
    }
  });

  // Close calendar when clicking outside
  document.addEventListener("click", function (e) {
    if (
      !calendarContainer.contains(e.target) &&
      !calendarBtn.contains(e.target) &&
      calendarContainer.style.display === "block"
    ) {
      calendarContainer.style.display = "none";
    }
  });

  // Initialize the calendar
  updateCalendar();

  // New function to get enabled weekdays
  function getEnabledWeekdays() {
    const weekdays = {
      sunday: 0,
      monday: 1,
      tuesday: 2,
      wednesday: 3,
      thursday: 4,
      friday: 5,
      saturday: 6,
    };

    const enabledDays = [];

    Object.keys(weekdays).forEach((day) => {
      const checkbox = document.getElementById(day);
      if (checkbox && checkbox.checked) {
        enabledDays.push(weekdays[day]);
      }
    });

    return enabledDays;
  }

  // Function to save checkbox states to database
  async function saveCheckboxStates() {
    const checkboxStates = {};
    const checkboxes = document.querySelectorAll(
      '.weekday-checkboxes input[type="checkbox"]'
    );
    checkboxes.forEach((checkbox) => {
      checkboxStates[checkbox.id] = checkbox.checked;
    });

    try {
      const response = await fetch("api/post/saveWeekdays.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(checkboxStates),
      });
      const data = await response.json();
      if (!data.success) {
        console.error("Failed to save weekday settings");
      }
    } catch (error) {
      console.error("Error saving weekday settings:", error);
    }
  }

  // Function to load checkbox states from database
  async function loadCheckboxStates() {
    try {
      const response = await fetch("api/get/getWeekdays.php");
      const checkboxStates = await response.json();

      Object.keys(checkboxStates).forEach((id) => {
        const checkbox = document.getElementById(id);
        if (checkbox) {
          checkbox.checked = checkboxStates[id];
        }
      });

      // Update calendar after loading states
      updateCalendar();
    } catch (error) {
      console.error("Error loading weekday settings:", error);
    }
  }

  // Modify the event listeners in DOMContentLoaded
  document.addEventListener("DOMContentLoaded", function () {
    // Load saved checkbox states
    loadCheckboxStates();

    // Add change event listeners to checkboxes
    const checkboxes = document.querySelectorAll(
      '.weekday-checkboxes input[type="checkbox"]'
    );
    checkboxes.forEach((checkbox) => {
      checkbox.addEventListener("change", () => {
        saveCheckboxStates(); // Save states when changed
        updateCalendar(); // Update calendar display
      });
    });

    // ... rest of your existing DOMContentLoaded code ...
  });

  // Add this CSS to your stylesheet
  const style = document.createElement("style");
  style.textContent = `
    .disabled-date {
      color: #ccc;
      cursor: not-allowed;
      background-color: #f5f5f5;
    }
  `;
  document.head.appendChild(style);
});

const calendarContainer = document.getElementById("calendarContainer");
if (!calendarContainer) {
  calendarContainer.style.display = "none";
}
