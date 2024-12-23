let todayDate = new Date();

// Function to update the calendar display
function updateCalendar(monthOffset = 0) {
  const date = new Date(
    todayDate.getFullYear(),
    todayDate.getMonth() + monthOffset,
    1
  );
  const month = date.getMonth();
  const year = date.getFullYear();

  // Update the month title
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
  document.getElementById(
    "MonthTitle"
  ).innerText = `${monthNames[month]} ${year}`;

  // Create the calendar table
  const table = document.getElementById("calendarTable_schedule");
  table.innerHTML = "";

  const daysOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
  let row = table.insertRow();
  for (let day of daysOfWeek) {
    const cell = row.insertCell();
    cell.textContent = day;
    cell.className = "calendarTable-header";
  }

  const firstDay = new Date(year, month, 1).getDay();
  const lastDate = new Date(year, month + 1, 0).getDate();

  let dayCount = 1;
  for (let i = 0; dayCount <= lastDate; i++) {
    row = table.insertRow();
    for (let j = 0; j < 7; j++) {
      const cell = row.insertCell();
      if (i === 0 && j < firstDay) {
        cell.textContent = "";
      } else if (dayCount <= lastDate) {
        cell.textContent = dayCount;
        cell.className = "clickable-date";
        dayCount++;
      }
    }
  }

  updateCalendarBasedOnWeeklySchedule();
}

// Function to handle button clicks
function monthChange(action) {
  if (action === "previous") {
    todayDate.setMonth(todayDate.getMonth() - 1);
  } else if (action === "next") {
    todayDate.setMonth(todayDate.getMonth() + 1);
  } else {
    todayDate = new Date();
  }
  updateCalendar(0);
}

// Initialize the calendar with the current month
updateCalendar(0);

// WEEKLY SCHEDULE DROPDOWN /////////////////////////////////////
function toggleDropdown() {
  const dropdown = document.getElementById("weekdayDropdown");
  dropdown.classList.toggle("show");
}

// Close the dropdown if clicked outside
document.addEventListener("click", function (event) {
  const dropdown = document.getElementById("weekdayDropdown");
  const weekdayCheckboxes = document.querySelector(".weekday-checkboxes");

  if (!weekdayCheckboxes.contains(event.target)) {
    dropdown.classList.remove("show");
  }
});

// Prevent dropdown from closing when clicking inside
document
  .querySelector(".checkbox-group")
  .addEventListener("click", function (event) {
    event.stopPropagation();
  });

// WEEKLY SCHEDULE /////////////////////////////////////
function updateCalendarBasedOnWeeklySchedule() {
  const calendar = document.getElementById("calendarTable_schedule");
  const cells = calendar.getElementsByTagName("td");
  const selectedDays = getSelectedWeekdays();

  for (let cell of cells) {
    if (cell.textContent !== "") {
      // Skip empty cells
      const date = new Date(
        currentYear,
        currentMonth,
        parseInt(cell.textContent)
      );
      const dayName = date.toLocaleDateString("en-US", { weekday: "long" });

      // If no days are selected, all days are available
      if (selectedDays.length === 0) {
        cell.classList.remove("disabled");
        continue;
      }

      // Enable/disable based on selected weekdays
      if (selectedDays.includes(dayName)) {
        cell.classList.remove("disabled");
      } else {
        cell.classList.add("disabled");
      }
    }
  }
}

function getSelectedWeekdays() {
  const checkboxes = document.querySelectorAll(
    '.checkbox-item input[type="checkbox"]'
  );
  const selectedDays = [];

  checkboxes.forEach((checkbox) => {
    if (checkbox.checked) {
      selectedDays.push(checkbox.value);
    }
  });

  return selectedDays;
}

// Add event listeners to checkboxes
document
  .querySelectorAll('.checkbox-item input[type="checkbox"]')
  .forEach((checkbox) => {
    checkbox.addEventListener("change", updateCalendarBasedOnWeeklySchedule);
  });
