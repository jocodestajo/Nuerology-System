document.addEventListener("DOMContentLoaded", function () {
  let currentDate = new Date();

  // --- Weekday filter logic ---
  const weekdays = {
    sunday: 0,
    monday: 1,
    tuesday: 2,
    wednesday: 3,
    thursday: 4,
    friday: 5,
    saturday: 6,
  };

  // Load weekday states from localStorage or default to all enabled
  function loadWeekdayStates() {
    const saved = JSON.parse(localStorage.getItem("weekdayStates") || "{}");
    const defaultStates = {
      monday: true,
      tuesday: true,
      wednesday: true,
      thursday: true,
      friday: true,
      saturday: true,
      sunday: true,
    };
    return { ...defaultStates, ...saved };
  }

  function saveWeekdayStates(states) {
    localStorage.setItem("weekdayStates", JSON.stringify(states));
  }

  function applyWeekdayStates(states) {
    Object.keys(states).forEach((id) => {
      const checkbox = document.getElementById(id);
      if (checkbox) {
        checkbox.checked = states[id];
      }
    });
  }

  // Set up weekday checkboxes
  const weekdayStates = loadWeekdayStates();
  applyWeekdayStates(weekdayStates);

  // Add change listeners to weekday checkboxes
  Object.keys(weekdays).forEach((id) => {
    const checkbox = document.getElementById(id);
    if (checkbox) {
      checkbox.addEventListener("change", function () {
        weekdayStates[id] = checkbox.checked;
        saveWeekdayStates(weekdayStates);
        updateCalendar();
      });
    }
  });

  function getEnabledWeekdays() {
    return Object.keys(weekdays)
      .filter((id) => weekdayStates[id])
      .map((id) => weekdays[id]);
  }

  // --- Calendar modal logic ---
  let currentDateInputId = null;
  const calendarContainer = document.getElementById("calendarContainer");

  document.querySelectorAll(".datePicker[data-sched-output]").forEach((btn) => {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      currentDateInputId = btn.getAttribute("data-sched-output");
      calendarContainer.style.display = "block";
      updateCalendar();
    });
  });

  function updateCalendar() {
    const table = document.getElementById("calendarTable");
    const monthTitle = document.getElementById("calendarMonthTitle");
    if (!table || !monthTitle) return;
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
    const enabledDays = getEnabledWeekdays();
    for (let i = 0; dayCount <= lastDate; i++) {
      const row = table.insertRow();
      for (let j = 0; j < 7; j++) {
        const cell = row.insertCell();
        if (i === 0 && j < firstDay) {
          cell.textContent = "";
        } else if (dayCount <= lastDate) {
          cell.textContent = dayCount;
          const date = new Date(
            currentDate.getFullYear(),
            currentDate.getMonth(),
            dayCount
          );
          const isEnabled = enabledDays.includes(date.getDay());
          if (isEnabled) {
            cell.className = "clickable-date";
            const currentDayCount = dayCount;
            cell.onclick = () => {
              handleDateClick(
                currentDate.getFullYear(),
                currentDate.getMonth(),
                currentDayCount
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

  function handleDateClick(year, month, dayCount) {
    const selectedDate = new Date(year, month, parseInt(dayCount), 12, 0, 0);
    const formattedDate = selectedDate.toISOString().split("T")[0];
    if (currentDateInputId) {
      const dateInput = document.getElementById(currentDateInputId);
      if (dateInput) {
        dateInput.value = formattedDate;
      }
    }
    if (calendarContainer) {
      calendarContainer.style.display = "none";
    }
  }

  // Hide calendar when clicking outside
  document.addEventListener("click", function (e) {
    if (
      calendarContainer &&
      calendarContainer.style.display === "block" &&
      !calendarContainer.contains(e.target) &&
      !e.target.classList.contains("datePicker")
    ) {
      calendarContainer.style.display = "none";
    }
  });

  // Hide calendar on load
  if (calendarContainer) {
    calendarContainer.style.display = "none";
  }
});
