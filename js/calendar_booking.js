// Declare these variables globally
let currentDate = new Date();
// let monthOffset = 0;

// Make changeMonth function global
window.changeMonth = function (action) {
  if (action === "previous") {
    currentDate.setMonth(currentDate.getMonth() - 1);
  } else if (action === "next") {
    currentDate.setMonth(currentDate.getMonth() + 1);
  } else {
    currentDate = new Date();
  }
  updateCalendar(0);
};

document.addEventListener("DOMContentLoaded", function () {
  // Debug logs
  console.log("DOM Content Loaded");

  const calendarBtn = document.querySelector(".btn-trigger");
  const calendarContainer = document.getElementById("calendarContainer");

  // console.log("Calendar Button:", calendarBtn);
  // console.log("Calendar Container:", calendarContainer);

  if (!calendarBtn || !calendarContainer) {
    console.log("calendar elements not found!");
    return;
  }

  // Add click event to the calendar button
  calendarBtn.addEventListener("click", function (e) {
    // console.log("Calendar button clicked");

    // Force initial display state check
    const currentDisplay = window.getComputedStyle(calendarContainer).display;
    // console.log("Current display state:", currentDisplay);

    if (currentDisplay === "none") {
      calendarContainer.style.display = "block";
      // console.log("Showing calendar");
      updateCalendar(); // Initialize calendar when showing
    } else {
      calendarContainer.style.display = "none";
      // console.log("Hiding calendar");
    }
  });

  // Initialize the calendar table structure
  function updateCalendar(monthOffset = 0) {
    console.log("Updating calendar with offset:", monthOffset);
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
      cell.className = "calendarTable-header";
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
          // cell.value = dayCount;
          cell.onclick = () => {
            // console.log("Text inside cell:", cell);

            // Call the handleDateClick function
            handleDateClick(
              date.getFullYear(),
              date.getMonth(),
              cell.textContent
            );
          };

          dayCount++;
        }
      }
    }
  }

  function handleDateClick(year, month, dayCount) {
    // Set the time to noon (12:00 PM) to avoid time zone issues
    const selectedDate = new Date(year, month, dayCount, 12, 0, 0);

    // Format the date as YYYY-MM-DD for the input field
    const formattedDate = selectedDate.toISOString().split("T")[0];

    // Update the date input field(s)
    const dateInputs = document.querySelectorAll(".date");
    dateInputs.forEach((dateInput) => {
      dateInput.value = formattedDate;
    });

    // Hide the calendar after selection
    const calendarContainer = document.getElementById("calendarContainer");
    if (calendarContainer) {
      calendarContainer.style.display = "none";
    }
  }

  // Add this new function to handle bookings
  function handleBooking(year, month, dayCount) {
    const bookingDate = new Date(year, month, dayCount); // Use dayCount instead of day
    const formattedDate = bookingDate.toLocaleDateString();
    // You can customize this part to handle the booking action
    console.log(`Booking requested for: ${formattedDate}`);
    // Add your booking logic here
  }

  // Initialize the calendar with the current month
  updateCalendar(0);
});

/// Function to update the calendar display
// function updateCalendar(monthOffset = 0) {
//   const date = new Date(
//     currentDate.getFullYear(),
//     currentDate.getMonth() + monthOffset,
//     1
//   );
//   const month = date.getMonth();
//   const year = date.getFullYear();

//   // Update the month title
//   const monthNames = [
//     "January",
//     "February",
//     "March",
//     "April",
//     "May",
//     "June",
//     "July",
//     "August",
//     "September",
//     "October",
//     "November",
//     "December",
//   ];
//   document.getElementById(
//     "calendarMonthTitle"
//   ).innerText = `${monthNames[month]} ${year}`;

//   // Create the calendar table
//   const table = document.getElementById("calendarTable");
//   table.innerHTML = "";

//   const daysOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
//   let row = table.insertRow();
//   for (let day of daysOfWeek) {
//     const cell = row.insertCell();
//     cell.textContent = day;
//   }

//   const firstDay = new Date(year, month, 1).getDay();
//   const lastDate = new Date(year, month + 1, 0).getDate();

//   let dayCount = 1;
//   for (let i = 0; dayCount <= lastDate; i++) {
//     row = table.insertRow();
//     for (let j = 0; j < 7; j++) {
//       const cell = row.insertCell();
//       if (i === 0 && j < firstDay) {
//         cell.textContent = "";
//       } else if (dayCount <= lastDate) {
//         cell.textContent = dayCount;
//         cell.className = "clickable-date";

//         // Use a closure to capture the correct value of dayCount for each cell
//         cell.onclick = (function (dayCount) {
//           return function () {
//             handleBooking(year, month, dayCount);
//           };
//         })(dayCount);

//         dayCount++;
//       }
//     }
//   }
// }
