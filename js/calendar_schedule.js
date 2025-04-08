let scheduleTodayDate = new Date();
let scheduleAppointmentCounts = {};

// Update the fetch function to get both F2F and Telecon counts
async function scheduleFetchAppointmentCounts(year, month) {
  try {
    const response = await fetch(
      `api/get/getAppointmentCounts.php?year=${year}&month=${month}`
    );
    const data = await response.json();
    scheduleAppointmentCounts = data;
  } catch (error) {
    console.error("Error fetching appointment counts:", error);
  }
}

// Function to update the calendar display
async function scheduleUpdateCalendar(monthOffset = 0) {
  const date = new Date(
    scheduleTodayDate.getFullYear(),
    scheduleTodayDate.getMonth() + monthOffset,
    1
  );
  const month = date.getMonth();
  const year = date.getFullYear();

  // Fetch appointment counts before updating calendar
  await scheduleFetchAppointmentCounts(year, month + 1);

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

        // Add button container
        const buttonContainer = document.createElement("div");
        buttonContainer.className = "button-container";

        const dateStr = `${year}-${String(month + 1).padStart(2, "0")}-${String(
          dayCount
        ).padStart(2, "0")}`;

        // Get counts from appointmentCounts
        const f2fCount = scheduleAppointmentCounts[dateStr]?.f2f || 0;
        const teleconCount = scheduleAppointmentCounts[dateStr]?.telecon || 0;

        // Conditionally add F2F button if count > 0
        if (f2fCount > 0) {
          const f2fButton = document.createElement("button");
          f2fButton.textContent = `F2F (${f2fCount})`;
          f2fButton.className = "calendar-btn f2f-btn";
          f2fButton.onclick = (e) => {
            e.stopPropagation();
            scheduleHandleDateClick("f2f", year, month, cell);
          };
          buttonContainer.appendChild(f2fButton);
        }

        // Conditionally add Telecon button if count > 0
        if (teleconCount > 0) {
          const teleconButton = document.createElement("button");
          teleconButton.textContent = `Telecon (${teleconCount})`;
          teleconButton.className = "calendar-btn telecon-btn";
          teleconButton.onclick = (e) => {
            e.stopPropagation();
            scheduleHandleDateClick("telecon", year, month, cell);
          };
          buttonContainer.appendChild(teleconButton);
        }

        // Append button container if there are any buttons
        if (f2fCount > 0 || teleconCount > 0) {
          cell.appendChild(buttonContainer);
        }

        dayCount++;
      }
    }
  }
}

// Function to handle button clicks
function scheduleMonthChange(action) {
  if (action === "previous") {
    scheduleTodayDate.setMonth(scheduleTodayDate.getMonth() - 1);
  } else if (action === "next") {
    scheduleTodayDate.setMonth(scheduleTodayDate.getMonth() + 1);
  } else {
    scheduleTodayDate = new Date();
  }
  scheduleUpdateCalendar(0);
}

// Initialize the schedule calendar
scheduleUpdateCalendar(0);

// WEEKLY SCHEDULE DROPDOWN /////////////////////////////////////
function scheduleToggleDropdown() {
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

function scheduleHandleDateClick(type, year, month, cell) {
  const dateNumber = cell.querySelector(".date-number").textContent;
  const selectedDate = new Date(year, month, parseInt(dateNumber), 12, 0, 0);

  // Format date to YYYY-MM-DD
  const formattedDate = selectedDate.toISOString().split("T")[0];
  console.log(`Selected ${type} appointment for ${formattedDate}`);

  // Determine table and filters to update based on the type
  const tableId = type === "f2f" ? "table2" : "table3";
  const dayFilterId = type === "f2f" ? "dayFilter2" : "dayFilter3";
  const monthFilterId = type === "f2f" ? "monthFilter2" : "monthFilter3";
  const yearFilterId = type === "f2f" ? "yearFilter2" : "yearFilter3";

  // Set the date filters dynamically
  const [yearStr, monthStr, dayStr] = formattedDate.split("-");
  document.getElementById(dayFilterId).value = dayStr;
  document.getElementById(monthFilterId).value = monthStr;
  document.getElementById(yearFilterId).value = yearStr;

  filterTable(tableId, dayFilterId, monthFilterId, yearFilterId);

  const tabIndex = type === "f2f" ? 2 : 3; // Adjust index based on tab setup
  showContent(tabIndex);
}
