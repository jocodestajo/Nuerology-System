let todayDate = new Date();
let appointmentCounts = {};

// Update the fetch function to get both F2F and Telecon counts
async function fetchAppointmentCounts(year, month) {
  try {
    // Format month to ensure it's two digits
    const formattedMonth = String(month).padStart(2, "0");

    const response = await fetch(
      `api/get/getAppointmentCounts.php?year=${year}&month=${formattedMonth}`
    );

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const contentType = response.headers.get("content-type");
    if (!contentType || !contentType.includes("application/json")) {
      throw new TypeError("Response was not JSON");
    }

    const data = await response.json();
    console.log("Fetched appointment counts:", data); // Debug log
    appointmentCounts = data;
  } catch (error) {
    console.error("Error fetching appointment counts:", error);
    appointmentCounts = {}; // Reset to empty object on error
  }
}

// Function to update the calendar display
async function updateCalendar(monthOffset = 0) {
  const date = new Date(
    todayDate.getFullYear(),
    todayDate.getMonth() + monthOffset,
    1
  );
  const month = date.getMonth();
  const year = date.getFullYear();

  // Fetch appointment counts before updating calendar
  await fetchAppointmentCounts(year, month + 1);

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

  for (let i = 0, dayCount = 1; dayCount <= lastDate; i++) {
    row = table.insertRow();
    for (let j = 0; j < 7; j++) {
      const cell = row.insertCell();
      if (i === 0 && j < firstDay) {
        cell.textContent = "";
      } else if (dayCount <= lastDate) {
        // Create a container for the date and buttons
        cell.className = "dateCell";

        // Add the date number
        const dateNumber = document.createElement("div");
        dateNumber.textContent = dayCount;
        dateNumber.className = "date-number";
        cell.appendChild(dateNumber);

        // Format the date string
        const dateStr = `${year}-${String(month + 1).padStart(2, "0")}-${String(
          dayCount
        ).padStart(2, "0")}`;

        // Add button container
        const buttonContainer = document.createElement("div");
        buttonContainer.className = "button-container";

        // Always create both buttons but control visibility with CSS
        const f2fButton = document.createElement("button");
        const teleconButton = document.createElement("button");

        // Get counts from appointmentCounts
        const f2fCount = appointmentCounts[dateStr]?.f2f || 0;
        const teleconCount = appointmentCounts[dateStr]?.telecon || 0;

        // Setup F2F button
        f2fButton.textContent = `F2F (${f2fCount})`;
        f2fButton.className = `calendar-btn f2f-btn ${
          f2fCount > 0 ? "active" : "hidden"
        }`;
        f2fButton.onclick = (e) => {
          e.stopPropagation();
          handleDateClick("f2f", year, month, dayCount);
        };

        // Setup Telecon button
        teleconButton.textContent = `Telecon (${teleconCount})`;
        teleconButton.className = `calendar-btn telecon-btn ${
          teleconCount > 0 ? "active" : "hidden"
        }`;
        teleconButton.onclick = (e) => {
          e.stopPropagation();
          handleDateClick("telecon", year, month, dayCount);
        };

        // Append buttons to container
        buttonContainer.appendChild(f2fButton);
        buttonContainer.appendChild(teleconButton);
        cell.appendChild(buttonContainer);

        dayCount++;
      }
    }
  }
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

function handleDateClick(type, year, month, day) {
  const selectedDate = new Date(year, month, day);
  const formattedDate = selectedDate.toISOString().split("T")[0];

  // Determine which tab to show and which filters to update
  const tabIndex = type === "f2f" ? 2 : 3;
  const filterPrefix = type === "f2f" ? "2" : "3";

  // Update the filters
  document.getElementById(`dayFilter${filterPrefix}`).value = String(
    day
  ).padStart(2, "0");
  document.getElementById(`monthFilter${filterPrefix}`).value = String(
    month + 1
  ).padStart(2, "0");
  document.getElementById(`yearFilter${filterPrefix}`).value = year;

  // Show the appropriate tab
  showContent(tabIndex);

  // Filter the table
  filterTable(
    `table${filterPrefix}`,
    `dayFilter${filterPrefix}`,
    `monthFilter${filterPrefix}`,
    `yearFilter${filterPrefix}`
  );
}

// Add this CSS to your stylesheet
const style = document.createElement("style");
style.textContent = `
  .calendar-btn.hidden {
    display: none;
  }
  .calendar-btn.active {
    display: inline-block;
    margin: 2px;
    padding: 2px 5px;
    font-size: 0.8em;
    cursor: pointer;
  }
  .f2f-btn {
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 3px;
  }
  .telecon-btn {
    background-color: #2196F3;
    color: white;
    border: none;
    border-radius: 3px;
  }
  .button-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
  }
  .dateCell {
    min-height: 60px;
    padding: 5px;
    vertical-align: top;
  }
`;
document.head.appendChild(style);

// Initialize the calendar when the page loads
document.addEventListener("DOMContentLoaded", () => {
  updateCalendar(0);
});
