document.addEventListener("DOMContentLoaded", function () {
  const filterDataReport = document.getElementById("filterDataReport");
  const sortDataReport = document.getElementById("sortDataReport");
  const dateFrom = document.getElementById("dateFrom");
  const dateTo = document.getElementById("dateTo");
  const applyFilterBtn = document.getElementById("applyFilterBtn");
  const tableBody = document.querySelector("#table4 tbody");

  // Function to fetch and update table data
  function updateTable() {
    const status = filterDataReport.value;
    const consultation = sortDataReport.value;
    const fromDate = dateFrom.value;
    const toDate = dateTo.value;

    // Create FormData object
    const formData = new FormData();
    // Handle the 'all' status case
    if (status === "all") {
      // Instead of sending 'all', send a list of statuses that are not 'pending'
      formData.append(
        "status",
        JSON.stringify(["processed", "follow up", "cancelled"])
      );
    } else {
      // Send the selected status as usual
      formData.append("status", status);
    }
    formData.append("consultation", consultation);
    formData.append("fromDate", fromDate);
    formData.append("toDate", toDate);

    // Fetch filtered data
    fetch("api/get/filterHistory.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        // Clear existing table rows
        tableBody.innerHTML = "";

        // Add new rows
        data.forEach((record) => {
          const row = document.createElement("tr");
          row.id = `patient_${record.id}`;
          row.innerHTML = `
                    <td class="th-name">${record.name}</td>
                    <td class="th-consultation">${record.consultation}</td>
                    <td class="th-schedule">${record.date_sched}</td>
                    <td class="th-complaint">${record.complaint}</td>
                    <td class="th-status">${record.status}</td>
                    <td class="th-action action border-right">
                        <img src="img/edit.png" class="action-img view-button margin-right" alt="View" data-record-id="${record.id}">
                    </td>
                `;
          tableBody.appendChild(row);
        });
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  }

  // Add event listener to Apply Filter button
  applyFilterBtn.addEventListener("click", updateTable);
});
