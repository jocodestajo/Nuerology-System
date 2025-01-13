function handleDateClick(type, year, month, cell) {
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

  // Trigger the filtering logic
  filterTable(tableId, dayFilterId, monthFilterId, yearFilterId);
}

// Filtering logic for tables (extracted to a reusable function)
function filterTable(tableId, dayFilterId, monthFilterId, yearFilterId) {
  const table = document.getElementById(tableId);
  const dayFilter = document.getElementById(dayFilterId);
  const monthFilter = document.getElementById(monthFilterId);
  const yearFilter = document.getElementById(yearFilterId);
  const tableRows = table.querySelectorAll("tbody tr");

  const selectedDay = dayFilter.value;
  const selectedMonth = monthFilter.value;
  const selectedYear = yearFilter.value;

  let visibleRowCount = 0;

  tableRows.forEach((row) => {
    const scheduleDate = row.querySelector(".th-schedule").textContent.trim();
    const [year, month, day] = scheduleDate.split("-");

    const matchesDay = selectedDay === "all" || day === selectedDay;
    const matchesMonth = selectedMonth === "all" || month === selectedMonth;
    const matchesYear = selectedYear === "all" || year === selectedYear;

    if (matchesDay && matchesMonth && matchesYear) {
      row.style.display = "";
      visibleRowCount++;
    } else {
      row.style.display = "none";
    }
  });

  const noRecordsRow = table.querySelector(".no-records-row");
  if (visibleRowCount === 0) {
    if (!noRecordsRow) {
      const noRecords = document.createElement("tr");
      noRecords.className = "no-records-row";
      noRecords.innerHTML = `<td colspan="7" style="text-align: center; font-size: 1.5rem; padding: 16px;">No records found</td>`;
      table.querySelector("tbody").appendChild(noRecords);
    }
  } else {
    if (noRecordsRow) {
      noRecordsRow.remove();
    }
  }
}
