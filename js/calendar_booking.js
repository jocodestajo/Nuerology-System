document.addEventListener("DOMContentLoaded", function () {
  // Debug logs
  console.log("DOM Content Loaded");

  const calendarBtn = document.querySelector(".btn-trigger");
  const calendarContainer = document.getElementById("calendarContainer");

  console.log("Calendar Button:", calendarBtn);
  console.log("Calendar Container:", calendarContainer);

  if (!calendarBtn || !calendarContainer) {
    console.error("Required calendar elements not found!");
    return;
  }

  // Add click event to the calendar button
  calendarBtn.addEventListener("click", function (e) {
    console.log("Calendar button clicked");

    // Force initial display state check
    const currentDisplay = window.getComputedStyle(calendarContainer).display;
    console.log("Current display state:", currentDisplay);

    if (currentDisplay === "none") {
      calendarContainer.style.display = "block";
      console.log("Showing calendar");
      updateCalendar(0); // Initialize calendar when showing
    } else {
      calendarContainer.style.display = "none";
      console.log("Hiding calendar");
    }
  });

  // Initialize the calendar table structure
  function updateCalendar(monthOffset = 0) {
    console.log("Updating calendar");
    const table = document.getElementById("calendarTable");
    if (!table) {
      console.error("Calendar table not found!");
      return;
    }

    // Clear existing table content
    table.innerHTML = "";

    // Add header row with days of week
    const headerRow = table.insertRow();
    ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"].forEach((day) => {
      const cell = headerRow.insertCell();
      cell.textContent = day;
    });

    // Calculate dates and populate calendar
    const date = new Date();
    date.setMonth(date.getMonth() + monthOffset);
    const firstDay = new Date(date.getFullYear(), date.getMonth(), 1).getDay();
    const lastDate = new Date(
      date.getFullYear(),
      date.getMonth() + 1,
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
    document.getElementById("calendarMonthTitle").textContent = `${
      monthNames[date.getMonth()]
    } ${date.getFullYear()}`;

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
          cell.className = "clickable-date";
          cell.onclick = () =>
            handleDateClick(date.getFullYear(), date.getMonth(), dayCount);
          dayCount++;
        }
      }
    }
  }

  function handleDateClick(year, month, day) {
    console.log(`Date clicked: ${month + 1}/${day}/${year}`);

    // Format the date as YYYY-MM-DD for the input field
    const selectedDate = new Date(year, month, day);
    const formattedDate = selectedDate.toISOString().split("T")[0];

    // Update the date input field
    const dateInput = document.getElementById("date");
    if (dateInput) {
      dateInput.value = formattedDate;

      // Hide the calendar after selection
      const calendarContainer = document.getElementById("calendarContainer");
      if (calendarContainer) {
        calendarContainer.style.display = "none";
      }
    }
  }
});

let currentDate = new Date();

// Function to update the calendar display
function updateCalendar(monthOffset = 0) {
  const date = new Date(
    currentDate.getFullYear(),
    currentDate.getMonth() + monthOffset,
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
    "calendarMonthTitle"
  ).innerText = `${monthNames[month]} ${year}`;

  // Create the calendar table
  const table = document.getElementById("calendarTable");
  table.innerHTML = "";

  const daysOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
  let row = table.insertRow();
  for (let day of daysOfWeek) {
    const cell = row.insertCell();
    cell.textContent = day;
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
        cell.onclick = () => handleBooking(year, month, dayCount);
        dayCount++;
      }
    }
  }
}

// Function to handle button clicks
function changeMonth(action) {
  if (action === "previous") {
    currentDate.setMonth(currentDate.getMonth() - 1);
  } else if (action === "next") {
    currentDate.setMonth(currentDate.getMonth() + 1);
  } else {
    currentDate = new Date();
  }
  updateCalendar(0);
}

// Add this new function to handle bookings
function handleBooking(year, month, day) {
  const bookingDate = new Date(year, month, day);
  const formattedDate = bookingDate.toLocaleDateString();
  // You can customize this part to handle the booking action
  console.log(`Booking requested for: ${formattedDate}`);
  // Add your booking logic here
}

// Initialize the calendar with the current month
updateCalendar(0);
