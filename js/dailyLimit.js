const dailyLimit = 5;

fetch("/api/appointments")
  .then((res) => res.json())
  .then((data) => {
    const fullyBookedDates = getFullyBookedDates(data);

    const calendar = new FullCalendar.Calendar(calendarEl, {
      dateClick: function (info) {
        if (fullyBookedDates.includes(info.dateStr)) {
          alert("This date is fully booked.");
        } else {
          // proceed to open appointment modal or form
        }
      },
      dayCellDidMount: function (info) {
        if (fullyBookedDates.includes(info.date.toISOString().slice(0, 10))) {
          info.el.classList.add("fully-booked");
          info.el.innerHTML += '<div class="tag">Fully Booked</div>';
        }
      },
    });

    calendar.render();
  });

function getFullyBookedDates(appointments) {
  const dateCounts = {};
  appointments.forEach((app) => {
    const date = app.date; // format: "YYYY-MM-DD"
    dateCounts[date] = (dateCounts[date] || 0) + 1;
  });

  return Object.keys(dateCounts).filter(
    (date) => dateCounts[date] >= dailyLimit
  );
}
