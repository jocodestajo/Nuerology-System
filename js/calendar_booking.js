// Initialize calendar when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
  // Load calendar settings first
  loadCalendarSettings();
});

// Function to load calendar settings
async function loadCalendarSettings() {
  await loadCheckboxStates();

  // Add change event listeners to checkboxes if they exist
  const checkboxes = document.querySelectorAll(
    '.weekday-checkboxes input[type="checkbox"]'
  );

  checkboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", () => {
      saveCheckboxStates();
      updateCalendar();
    });
  });

  // Initialize calendar if we're on a page with a calendar
  if (document.querySelector(".calendar")) {
    let currentDate = new Date();
    window.currentDate = currentDate; // Make currentDate globally accessible

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

    // Initialize the calendar
    updateCalendar();
  }
}

// Define updateCalendar function globally
function updateCalendar() {
  const table = document.getElementById("calendarTable");
  const monthTitle = document.getElementById("calendarMonthTitle");

  if (!table || !monthTitle) {
    console.error("Calendar elements not found!");
    return;
  }

  const enabledDays = getEnabledWeekdays();

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

// Update calendar trigger handling to work with multiple buttons
const calendarBtns = document.querySelectorAll(".datePicker");
const calendarContainers = document.querySelectorAll("#calendarContainer");

calendarBtns.forEach((btn) => {
  btn.addEventListener("click", function (e) {
    e.preventDefault();

    // Find the closest calendar container to this button
    const container =
      this.closest(".calendar").querySelector("#calendarContainer");
    const outputId = this.getAttribute("data-sched-output");

    if (container) {
      // Hide all other calendar containers first
      calendarContainers.forEach((cont) => {
        if (cont !== container) {
          cont.style.display = "none";
        }
      });

      // Toggle this container
      container.style.display =
        container.style.display === "none" ? "block" : "none";

      if (container.style.display === "block") {
        updateCalendar();
      }
    }
  });
});

// click handler to use data attribute
function handleDateClick(year, month, dayCount) {
  const selectedDate = new Date(year, month, parseInt(dayCount), 12, 0, 0);
  const formattedDate = selectedDate.toISOString().split("T")[0];

  // Find the active calendar container
  const activeContainer = document.querySelector(
    '#calendarContainer[style="display: block;"]'
  );
  if (activeContainer) {
    // Get the associated output element ID from the trigger button
    const triggerBtn = activeContainer
      .closest(".calendar")
      .querySelector(".datePicker");
    const outputId = triggerBtn.getAttribute("data-sched-output");
    const dateInput = document.getElementById(outputId);

    if (dateInput) {
      dateInput.value = formattedDate;
    }

    activeContainer.style.display = "none";
  }
}

// Update outside click handler
document.addEventListener("click", function (e) {
  calendarContainers.forEach((container) => {
    const triggerBtn = container
      .closest(".calendar")
      .querySelector(".datePicker");
    if (
      !container.contains(e.target) &&
      !triggerBtn.contains(e.target) &&
      container.style.display === "block"
    ) {
      container.style.display = "none";
    }
  });
});

// Update getEnabledWeekdays to work without requiring DOM elements
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

  // Get saved states from localStorage or use defaults
  const savedStates = JSON.parse(localStorage.getItem("weekdayStates") || "{}");
  const defaultStates = {
    monday: true,
    tuesday: true,
    wednesday: true,
    thursday: true,
    friday: true,
    saturday: true,
    sunday: true,
  };

  // Combine default states with saved states
  const finalStates = { ...defaultStates, ...savedStates };

  Object.keys(weekdays).forEach((day) => {
    if (finalStates[day]) {
      enabledDays.push(weekdays[day]);
    }
  });

  return enabledDays;
}

// Update saveCheckboxStates to also save to localStorage
async function saveCheckboxStates() {
  try {
    const checkboxStates = {};
    const checkboxes = document.querySelectorAll(
      '.weekday-checkboxes input[type="checkbox"]'
    );
    checkboxes.forEach((checkbox) => {
      checkboxStates[checkbox.id] = checkbox.checked;
    });

    // Save to localStorage for immediate access
    localStorage.setItem("weekdayStates", JSON.stringify(checkboxStates));

    // Save to database
    const response = await fetch("api/post/saveWeekdays.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(checkboxStates),
    });

    const result = await response.json();

    if (!result.success) {
      console.error("Failed to save weekday settings");
    }
  } catch (error) {
    console.error("Error saving weekday settings:", error);
  }
}

// Update loadCheckboxStates to also load from localStorage
async function loadCheckboxStates() {
  try {
    // Try to load from localStorage first
    const savedStates = JSON.parse(
      localStorage.getItem("weekdayStates") || "{}"
    );

    // If we have saved states, use them
    if (Object.keys(savedStates).length > 0) {
      Object.keys(savedStates).forEach((id) => {
        const checkbox = document.getElementById(id);
        if (checkbox) {
          checkbox.checked = savedStates[id];
        }
      });
      return;
    }

    // If no localStorage data, try to load from database
    const response = await fetch("api/get/getWeekdays.php");
    const checkboxStates = await response.json();

    // Set default checked state if no data exists
    const defaultCheckedDays = {
      monday: true,
      tuesday: true,
      wednesday: true,
      thursday: true,
      friday: true,
      saturday: true,
      sunday: true,
    };

    // Combine default states with saved states
    const finalStates = { ...defaultCheckedDays, ...checkboxStates };

    // Save to localStorage for future use
    localStorage.setItem("weekdayStates", JSON.stringify(finalStates));

    Object.keys(finalStates).forEach((id) => {
      const checkbox = document.getElementById(id);
      if (checkbox) {
        checkbox.checked = finalStates[id];
      }
    });

    // If no states were saved yet, save the default states
    if (Object.keys(checkboxStates).length === 0) {
      saveCheckboxStates();
    }
  } catch (error) {
    console.error("Error loading weekday settings:", error);

    // If there's an error, set all checkboxes to checked
    const checkboxes = document.querySelectorAll(
      '.weekday-checkboxes input[type="checkbox"]'
    );
    checkboxes.forEach((checkbox) => {
      checkbox.checked = true;
    });

    // Try to save the default states
    saveCheckboxStates();
  }
}

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

// Only hide the calendar container if it exists
const calendarContainer = document.getElementById("calendarContainer");
if (calendarContainer) {
  calendarContainer.style.display = "none";
}
