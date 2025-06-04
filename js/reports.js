// REPORTS TABS
function openTab(tabName) {
  // Hide all tab contents
  var tabContents = document.getElementsByClassName("tab-content");
  for (var i = 0; i < tabContents.length; i++) {
    tabContents[i].classList.remove("active");
  }

  // Remove active class from all tabs
  var tabs = document.getElementsByClassName("tabRep");
  for (var i = 0; i < tabs.length; i++) {
    tabs[i].classList.remove("active");
  }

  // Show the current tab and add active class
  document.getElementById(tabName).classList.add("active");
  event.currentTarget.classList.add("active");
}

// Fetch and display patient reports
function loadPatientReports() {
  var timeframe = document.getElementById("timeframe").value;
  var patientType = document.getElementById("patient-type").value;

  fetch(
    "api/get/fetch-reports.php?timeframe=" +
      encodeURIComponent(timeframe) +
      "&patientType=" +
      encodeURIComponent(patientType)
  )
    .then((response) => response.json())
    .then((data) => {
      var tbody = document.querySelector("#patient-reports table tbody");
      tbody.innerHTML = "";
      if (data.length === 0) {
        tbody.innerHTML =
          '<tr><td colspan="6" style="text-align:center;">No records found.</td></tr>';
      } else {
        data.forEach((row, idx) => {
          var tr = document.createElement("tr");
          tr.innerHTML = `
                      <td>${idx + 1}</td>
                      <td>${row.hrn}</td>
                      <td>${row.name}</td>
                      <td>${row.date_sched}</td>
                      <td>${row.date_process || "-"}</td>
                      <td>${row.status}</td>
                  `;
          tbody.appendChild(tr);
        });
      }
    });
}

// Event listeners for filters
document.addEventListener("DOMContentLoaded", function () {
  if (document.getElementById("patient-reports")) {
    loadPatientReports();
    document
      .querySelector("#patient-reports button")
      .addEventListener("click", loadPatientReports);
  }
});
