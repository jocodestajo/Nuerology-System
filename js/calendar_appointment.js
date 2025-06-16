// Initialize calendar when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
  let currentDate = new Date();

  // Make changeMonth function global
  window.changeAppointmentMonth = function (action) {
    event.preventDefault();

    if (action === "previous") {
      currentDate.setMonth(currentDate.getMonth() - 1);
    } else if (action === "next") {
      currentDate.setMonth(currentDate.getMonth() + 1);
    } else {
      currentDate = new Date();
    }
    updateAppointmentCalendar();
  };

  // Define updateCalendar function
  function updateAppointmentCalendar() {
    const table = document.getElementById("appointmentCalendarTable");
    const monthTitle = document.getElementById("appointmentMonthTitle");

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
              handleAppointmentDateClick(
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
  function handleAppointmentDateClick(year, month, dayCount) {
    const selectedDate = new Date(year, month, parseInt(dayCount), 12, 0, 0);
    const formattedDate = selectedDate.toISOString().split("T")[0];

    // Find all date inputs with data-calendar-type="appointment"
    const dateInputs = document.querySelectorAll(
      'input[data-calendar-type="appointment"]'
    );
    dateInputs.forEach((input) => {
      if (input.getAttribute("data-active") === "true") {
        input.value = formattedDate;
      }
    });

    // Hide all appointment calendar containers
    const calendarContainers = document.querySelectorAll(
      ".appointment-calendar-container"
    );
    calendarContainers.forEach((container) => {
      container.style.display = "none";
    });
  }

  // Get enabled weekdays
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

  // Add click handlers for calendar triggers
  const appointmentTriggers = document.querySelectorAll(
    ".appointment-calendar-trigger"
  );
  appointmentTriggers.forEach((trigger) => {
    trigger.addEventListener("click", function (e) {
      e.preventDefault();

      // Get the target input
      const targetInput = document.getElementById(
        this.getAttribute("data-target")
      );
      if (!targetInput) return;

      // Mark this input as active
      document
        .querySelectorAll('input[data-calendar-type="appointment"]')
        .forEach((input) => input.setAttribute("data-active", "false"));
      targetInput.setAttribute("data-active", "true");

      // Show the calendar container
      const calendarWrapper = this.closest(".calendar-wrapper");
      const container = calendarWrapper
        ? calendarWrapper.querySelector(".appointment-calendar-container")
        : document.querySelector(".appointment-calendar-container");

      if (container) {
        // Hide all other calendars first
        document
          .querySelectorAll(".appointment-calendar-container")
          .forEach((cont) => (cont.style.display = "none"));

        container.style.display = "block";
        updateAppointmentCalendar();
      }
    });
  });

  // Close calendar when clicking outside
  document.addEventListener("click", function (e) {
    const calendarWrapper = e.target.closest(".calendar-wrapper");
    if (!calendarWrapper) {
      document
        .querySelectorAll(".appointment-calendar-container")
        .forEach((container) => (container.style.display = "none"));
    }
  });
});
