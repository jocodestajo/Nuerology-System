<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
  </head>
  <body>
    <label class="label-border bold">
      Date:
      <input type="date" class="dateFilterPending border-none" />
    </label>

    <label class="label-border bold">
      Filter Month:
      <select name="" class="sortData-Pending border-none" id="monthFilter">
        <option value="" hidden disabled selected>Select Month</option>
        <!-- Dynamic months will be added here -->
      </select>
    </label>

    <label class="label-border bold">
      Filter Year:
      <select name="" class="sortData-Year border-none" id="yearFilter">
        <option value="" hidden disabled selected>Select Year</option>
        <!-- Dynamic years will be added here -->
      </select>
    </label>

    <label class="label-border bold">
      Filter:
      <select name="" class="sortData-Pending border-none" id="filterOption">
        <option value="showAll">Show All</option>
        <option value="lastMonth">Last Month</option>
      </select>
    </label>

    <script>
      // Get today's date
      var today = new Date();

      // Function to populate months and years dropdown
      function populateMonthAndYearDropdowns() {
        var currentYear = today.getFullYear();
        var currentMonth = today.getMonth();

        // Populate months (January to December)
        var monthSelect = document.getElementById("monthFilter");
        for (var month = 0; month < 12; month++) {
          var option = document.createElement("option");
          option.value = month + 1; // 1 to 12 for months
          option.textContent = new Date(currentYear, month).toLocaleString(
            "default",
            { month: "long" }
          ); // Month name
          monthSelect.appendChild(option);
        }

        // Populate years (e.g., 2023, 2024, etc.)
        var yearSelect = document.getElementById("yearFilter");
        for (var year = currentYear - 5; year <= currentYear + 5; year++) {
          // Show years from 5 years ago to 5 years ahead
          var option = document.createElement("option");
          option.value = year;
          option.textContent = year;
          yearSelect.appendChild(option);
        }
      }

      // Function to get the first and last day of a month based on the selected month and year
      function getFirstAndLastDayOfMonth(month, year) {
        var startDate = new Date(year, month - 1, 1); // 1st day of the selected month
        var endDate = new Date(year, month, 0); // Last day of the selected month
        return { start: startDate, end: endDate };
      }

      // Function to filter the table rows based on the selected month and year
      function filterTableByMonthAndYear(
        tableClass,
        selectedMonth,
        selectedYear
      ) {
        var rows = document.querySelectorAll(tableClass + " tbody tr");
        var { start, end } = getFirstAndLastDayOfMonth(
          selectedMonth,
          selectedYear
        );

        rows.forEach(function (row) {
          var scheduleCell = row.querySelector(".th-schedule");
          if (scheduleCell) {
            var scheduleDate = new Date(scheduleCell.textContent.trim());

            // Filter rows within the selected month and year
            if (scheduleDate >= start && scheduleDate <= end) {
              row.style.display = "";
            } else {
              row.style.display = "none";
            }
          }
        });

        // Check if no rows are visible (i.e., no matching records)
        var tableBody = document.querySelector(tableClass + " tbody");
        var existingNoRecordsRow = tableBody.querySelector(".no-records");

        var visibleRows = Array.from(rows).filter(
          (row) => row.style.display !== "none"
        );

        if (visibleRows.length === 0) {
          // If "No records found" row doesn't exist, create and append it
          if (!existingNoRecordsRow) {
            var noRecordsRow = document.createElement("tr");
            noRecordsRow.classList.add("no-records");
            noRecordsRow.innerHTML =
              '<td colspan="7" style="text-align: center; font-size: 2rem; padding: 32px 0 32px 0;">No records found</td>';
            tableBody.appendChild(noRecordsRow);
          }
        } else {
          // Remove the "No records found" row if matching records are found
          if (existingNoRecordsRow) {
            existingNoRecordsRow.remove();
          }
        }
      }

      // Event listener for the month change
      document
        .getElementById("monthFilter")
        .addEventListener("change", function () {
          var selectedMonth = parseInt(this.value);
          var selectedYear = document.getElementById("yearFilter").value;
          if (selectedMonth && selectedYear) {
            filterTableByMonthAndYear(
              ".table-pending",
              selectedMonth,
              selectedYear
            );
            filterTableByMonthAndYear(
              ".table-face-to-face",
              selectedMonth,
              selectedYear
            );
            filterTableByMonthAndYear(
              ".table-teleconsultation",
              selectedMonth,
              selectedYear
            );
          }
        });

      // Event listener for the year change
      document
        .getElementById("yearFilter")
        .addEventListener("change", function () {
          var selectedYear = parseInt(this.value);
          var selectedMonth = document.getElementById("monthFilter").value;
          if (selectedMonth && selectedYear) {
            filterTableByMonthAndYear(
              ".table-pending",
              selectedMonth,
              selectedYear
            );
            filterTableByMonthAndYear(
              ".table-face-to-face",
              selectedMonth,
              selectedYear
            );
            filterTableByMonthAndYear(
              ".table-teleconsultation",
              selectedMonth,
              selectedYear
            );
          }
        });

      // Event listener for the "Show All" filter option
      document
        .getElementById("filterOption")
        .addEventListener("change", function () {
          var selectedOption = this.value;
          var selectedMonth = document.getElementById("monthFilter").value;
          var selectedYear = document.getElementById("yearFilter").value;

          if (selectedOption === "showAll") {
            // Show all rows
            document
              .querySelectorAll(".table-pending tbody tr")
              .forEach((row) => (row.style.display = ""));
            document
              .querySelectorAll(".table-face-to-face tbody tr")
              .forEach((row) => (row.style.display = ""));
            document
              .querySelectorAll(".table-teleconsultation tbody tr")
              .forEach((row) => (row.style.display = ""));
          }
        });

      // Initialize dropdowns on page load
      window.onload = function () {
        populateMonthAndYearDropdowns(); // Populate months and years
      };
    </script>
  </body>
</html>
