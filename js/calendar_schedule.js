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
  const table = document.getElementById("calendarTable_schedule");
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

// Initialize the calendar with the current month
updateCalendar(0);