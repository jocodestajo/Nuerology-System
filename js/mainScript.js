////////////////////////////////////////////////////////////////////////////////////////////////////
// TABS FUNCTION ////////////////////////////////////////////////////////////////////
function showContent(index) {
  // Hide all content sections
  let contents = document.querySelectorAll(".content");
  let tabs = document.querySelectorAll(".tab");
  contents.forEach(function (content) {
    content.classList.remove("active");
  });

  // Show the content of the clicked tab
  contents[index].classList.add("active");

  // Remove active class from all tabs and add it to the clicked tab
  tabs.forEach(function (tab) {
    tab.classList.remove("active");
  });
  tabs[index].classList.add("active");
}

////////////////////////////////////////////////////////////////////////////////////////////////////
// SEARCH QUERY ///////////////////////////////////////////////////////////////////
const searchResult = document.getElementById("result");
const nameInput = document.getElementById("name");

// Modal for search results
const searchResultModal = document.getElementById("searchResult-modal");

// Function to close the modal
function closeModal() {
  searchResultModal.style.display = "none";
  searchResult.innerHTML = ""; // Clear the results
}

// Debounce timeout
let debounceTimeout;

// Handle keyup event for search input
nameInput.addEventListener("keyup", function () {
  clearTimeout(debounceTimeout);

  debounceTimeout = setTimeout(function () {
    let query = nameInput.value.trim(); // Trim whitespace
    if (query !== "") {
      searchResult.style.display = "block"; // Show the result container

      // AJAX request for search suggestions
      let xhr = new XMLHttpRequest();
      xhr.open(
        "GET",
        "api/search.php?query=" + encodeURIComponent(query),
        true
      );
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
          if (xhr.responseText.trim() !== "") {
            searchResult.innerHTML = xhr.responseText; // Update search results
          } else {
            searchResult.innerHTML = "<p>No results found</p>"; // No results
          }
        }
      };
      xhr.send();
    } else {
      searchResult.innerHTML = ""; // Clear results if input is empty
      searchResult.style.display = "none"; // Hide the result container
    }
  }, 300); // Delay in milliseconds
});

// Delegate clicks on dynamically added result items or nameInput
document.addEventListener("click", function (event) {
  const resultItem = event.target.closest(".result-item");

  // If clicking on nameInput or a result item, avoid closing and handle accordingly
  if (event.target === nameInput || resultItem) {
    if (resultItem) {
      let name = resultItem.getAttribute("data-name");
      let id = resultItem.getAttribute("data-id");

      console.log("ID:", id);
      console.log("Name:", name);

      // Fetch full details for the selected item
      let xhr = new XMLHttpRequest();
      xhr.open("GET", "api/fetch_data.php?id=" + id, true);
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
          let data = JSON.parse(xhr.responseText);
          console.log(data);

          document.getElementById("hrn").value = data.hrn;
          document.getElementById("name").value = data.name;
          document.getElementById("age").value = data.currentage;
          document.getElementById("birthday").value = data.birthday;
          document.getElementById("contact").value = data.contactnumber;
          document.getElementById("address").value = data.address;

          // Set fields to readonly
          document.getElementById("hrn").readOnly = true;
          document.getElementById("name").readOnly = true;
          document.getElementById("age").readOnly = true;
          document.getElementById("birthday").readOnly = true;
          document.getElementById("address").readOnly = true;

          const contact = document.getElementById("contact");
          if (contact.value !== "") {
            contact.readOnly = true;
          }

          closeModal();
        }
      };
      xhr.send();
    }
    return; // Prevent further actions (like closing the modal) when clicking on nameInput or resultItem
  }

  // Close the modal if clicking outside the modal or results
  if (
    !searchResultModal.contains(event.target) &&
    !searchResult.contains(event.target) &&
    !nameInput.contains(event.target)
  ) {
    closeModal();
  }
});

// // Event listener for clicking on a result item
// document.addEventListener("click", function (e) {
//   console.log(e.target.classList.contains("result-item"));

//   if (e.target.classList.contains("result-item")) {
//     let name = e.target.getAttribute("data-name");
//     let id = e.target.getAttribute("data-id");

//     // Check data if working/showing
//     console.log(id);

//     // AJAX request to fetch full details for the selected item
//     let xhr = new XMLHttpRequest();
//     xhr.open("GET", "api/fetch_data.php?id=" + id, true);
//     xhr.onreadystatechange = function () {
//       if (xhr.readyState === 4 && xhr.status === 200) {
//         let data = JSON.parse(xhr.responseText);
//         console.log(data);

//         document.getElementById("hrn").value = data.hrn;
//         document.getElementById("name").value = data.name;
//         document.getElementById("age").value = data.currentage;
//         document.getElementById("birthday").value = data.birthday;
//         document.getElementById("contact").value = data.contactnumber;
//         document.getElementById("address").value = data.address;

//         // Set fields to disabled
//         document.getElementById("hrn").readOnly = true;
//         document.getElementById("name").readOnly = true;
//         document.getElementById("age").readOnly = true;
//         document.getElementById("birthday").readOnly = true;
//         document.getElementById("address").readOnly = true;
//         document.getElementById("clientSelect").readOnly = true;

//         const contact = document.getElementById("contact");
//         if (contact.value !== "") {
//           contact.readOnly = true;
//         }

//         closeModal();
//       }
//     };
//     xhr.send();
//   }
// });

//
// ALERT MESSAGE ON INQUIRY
const floatingAlert = document.getElementById("floatingAlert");
// Close the modal when clicking on the "X" and "close" button
document.querySelectorAll(".close-floatingAlert").forEach(function (button) {
  button.addEventListener("click", function () {
    floatingAlert.style.position = "unset";
    floatingAlert.style.display = "none";
  });
});

////////////////////////////////////////////////////////////////////////////////////////////////////
// RADIO BUTTON IF NEW OR OLD PATIENT ///////////////////////////////////////////
const clientSelect = document.getElementById("clientSelect");
const teleconsultRadio = document.getElementById("teleconsult");

clientSelect.addEventListener("change", function () {
  if (this.value === "New") {
    // check radio button "face to face"
    document.getElementById("f2f").checked = true;

    teleconsultRadio.disabled = true;
    // Optionally uncheck the radio button if it's selected
    if (teleconsultRadio.checked) {
      teleconsultRadio.checked = false;
    }
  } else {
    teleconsultRadio.disabled = false;
  }
});

////////////////////////////////////////////////////////////////////////////////////////////////////
// CLEAR DATA and REMOVE DISABLED ATTRIBUTES ///////////////////////////////////////
document
  .querySelector("[name='clear_data_btn']")
  .addEventListener("click", function () {
    // Get all input, select, and textarea elements inside the form
    const inputs = document.querySelectorAll(
      ".box input, .box select, .box textarea"
    );

    // Loop through each element
    inputs.forEach((input) => {
      // Skip the currentdate field and leave it as it is
      if (input.classList.contains("datetime")) {
        return;
      }

      // Clear the value for all other fields
      input.value = "";

      // If the field is not the HRN field, remove the disabled and readonly attributes
      if (input.id !== "hrn") {
        input.removeAttribute("disabled");
        input.removeAttribute("readonly");
      }

      // Reset selection for select elements
      if (input.tagName === "SELECT") {
        input.selectedIndex = 0; // Resets to the default option
      }
    });
  });

////////////////////////////////////////////////////////////////////////////////////////////////////
// Function to handle CHECKBOXES behavior per TABLE //////////////////////////////////////
document.querySelectorAll(".checkbox-header").forEach((headerCheckbox) => {
  // Select all checkboxes in the current table
  const table = headerCheckbox.closest("table");
  const rowCheckboxes = table.querySelectorAll(
    ".checkbox:not(.checkbox-header)"
  );

  // Event listener for "Select All" checkbox
  headerCheckbox.addEventListener("change", function () {
    rowCheckboxes.forEach((checkbox) => (checkbox.checked = this.checked));
  });

  // Event listener for individual row checkboxes
  rowCheckboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", function () {
      // If any checkbox is unchecked, uncheck the header checkbox
      if (!this.checked) {
        headerCheckbox.checked = false;
      }
      // If all checkboxes are checked, check the header checkbox
      else if ([...rowCheckboxes].every((checkbox) => checkbox.checked)) {
        headerCheckbox.checked = true;
      }
    });
  });
});

////////////////////////////////////////////////////////////////////////////////////////////////////
// NAV-LIST in TABLET MODE //////////////////////////////////////////////////////
const menuToggle = document.getElementById("menu-toggle");
const navList = document.querySelector(".nav-list");

// Add event listener to toggle the visibility of the nav-list
menuToggle.addEventListener("click", () => {
  navList.classList.toggle("show");
});

////////////////////////////////////////////////////////////////////////////////////////////////////
// DOM CONTENT FILTER FOR DATE, MONTH, YEAR ////////////////////////////////////////////////////////
document.addEventListener("DOMContentLoaded", function () {
  const tableConfigs = [
    {
      tableId: "table1",
      dayId: "dayFilter1",
      monthId: "monthFilter1",
      yearId: "yearFilter1",
      defaultToCurrentDay: false,
    },
    {
      tableId: "table2",
      dayId: "dayFilter2",
      monthId: "monthFilter2",
      yearId: "yearFilter2",
      defaultToCurrentDay: true,
    },
    {
      tableId: "table3",
      dayId: "dayFilter3",
      monthId: "monthFilter3",
      yearId: "yearFilter3",
      defaultToCurrentDay: true,
    },
  ];

  const currentDate = new Date();
  const currentYear = currentDate.getFullYear();
  const currentMonth = (currentDate.getMonth() + 1).toString().padStart(2, "0");
  const currentDay = currentDate.getDate().toString().padStart(2, "0");

  function setupTable({
    tableId,
    dayId,
    monthId,
    yearId,
    defaultToCurrentDay,
  }) {
    const table = document.getElementById(tableId);
    const dayFilter = document.getElementById(dayId);
    const monthFilter = document.getElementById(monthId);
    const yearFilter = document.getElementById(yearId);
    const tableRows = table.querySelectorAll("tbody tr");

    function filterTable() {
      const selectedDay = dayFilter.value;
      const selectedMonth = monthFilter.value;
      const selectedYear = yearFilter.value;

      let visibleRowCount = 0;

      tableRows.forEach((row) => {
        const scheduleDate = row
          .querySelector(".th-schedule")
          .textContent.trim();
        const [year, month, day] = scheduleDate.split("-");

        const matchesDay =
          selectedDay === "all" || day === selectedDay || selectedDay === "";
        const matchesMonth =
          selectedMonth === "all" ||
          month === selectedMonth ||
          selectedMonth === "";
        const matchesYear =
          selectedYear === "all" ||
          year === selectedYear ||
          selectedYear === "";

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

    function populateDropdowns() {
      const addShowAllOption = (dropdown, label) => {
        const option = document.createElement("option");
        option.value = "all";
        option.textContent = label;
        dropdown.appendChild(option);
      };

      for (let i = currentYear; i >= 2000; i--) {
        const option = document.createElement("option");
        option.value = i.toString();
        option.textContent = i;
        if (i === currentYear) option.selected = true;
        yearFilter.appendChild(option);
      }
      addShowAllOption(yearFilter, "Show All Years");

      const populateMonths = () => {
        monthFilter.innerHTML = "";
        addShowAllOption(monthFilter, "Show All Months");
        for (let i = 1; i <= 12; i++) {
          const option = document.createElement("option");
          option.value = i.toString().padStart(2, "0");
          option.textContent = new Date(0, i - 1).toLocaleString("default", {
            month: "long",
          });
          if (option.value === currentMonth)
            option.selected = defaultToCurrentDay;
          monthFilter.appendChild(option);
        }
      };

      const populateDays = () => {
        const selectedYear = parseInt(yearFilter.value) || currentYear;
        const selectedMonth =
          parseInt(monthFilter.value) || parseInt(currentMonth);
        const daysInMonth = new Date(selectedYear, selectedMonth, 0).getDate();

        dayFilter.innerHTML = "";
        addShowAllOption(dayFilter, "Show All Days");
        for (let i = 1; i <= daysInMonth; i++) {
          const option = document.createElement("option");
          option.value = i.toString().padStart(2, "0");
          option.textContent = i;
          if (option.value === currentDay && defaultToCurrentDay)
            option.selected = true;
          dayFilter.appendChild(option);
        }
      };

      populateMonths();
      populateDays();

      yearFilter.addEventListener("change", () => {
        populateMonths();
        populateDays();
        filterTable();
      });

      monthFilter.addEventListener("change", () => {
        populateDays();
        filterTable();
      });

      dayFilter.addEventListener("change", filterTable);
    }

    populateDropdowns();

    // Apply default filtering if needed
    if (defaultToCurrentDay) {
      filterTable();
    }
  }

  tableConfigs.forEach(setupTable);
});

////////////////////////////////////////////////////////////////////////////////////////////////////
// UPDATE DATA AS APPROVE ////////////////////////////////////////////////////////////////////////
document.querySelectorAll(".update-approve").forEach(function (button) {
  button.addEventListener("click", function () {
    // Get the record ID from the data-id attribute
    var recordId = this.getAttribute("data-id");

    // Send AJAX request using Fetch API
    fetch("api/post/updateData.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded", // Specify content type
      },
      body: "approve_record=" + encodeURIComponent(recordId), // Send the record ID
    })
      .then((response) => response.json()) // Parse the JSON response
      .then((data) => {
        if (data.success) {
          alert("Appointment Approved!");
          removeData(recordId);
        } else {
          alert("Error: " + data.message);
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("An error occurred.");
      });
  });
});

////////////////////////////////////////////////////////////////////////////////////////////////////
// UPDATE DATA AS PROCESSED ////////////////////////////////////////////////////////////////////////
document.querySelectorAll(".update-processed").forEach(function (button) {
  button.addEventListener("click", function () {
    // get the record ID from the data-id attribute
    const recordID = this.getAttribute("data-id");

    // send AJAX request
    fetch("api/post/updateData.php", {
      method: "POST",
      headers: {
        "content-Type": "application/x-www-form-urlencoded",
      },
      body: "processed_record=" + encodeURIComponent(recordID),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert("Appointment Processed");
          removeData(recordID);
        } else {
          alert("Error: " + data.message);
        }
      })
      .catch((error) => {
        console.errror("Error:", error);
        alert("An error occured.");
      });
  });
});

////////////////////////////////////////////////////////////////////////////////////////////////////
// UPDATE DATA AS CANCELLED with confirmation /////////////////////////////////////////////////////
document.querySelectorAll(".update-cancelled").forEach(function (button) {
  button.addEventListener("click", function () {
    // Get the record ID from the data-id attribute
    var recordId = this.getAttribute("data-id");

    // Show the modal
    var modal = document.getElementById("confirmModal");
    modal.style.display = "block";

    // Handle the "Yes, Cancel" button
    document
      .getElementById("confirmCancel")
      .addEventListener("click", function () {
        // Send AJAX request using Fetch API
        fetch("api/post/updateData.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded", // Specify content type
          },
          body: "cancel_record=" + encodeURIComponent(recordId), // Send the record ID
        })
          .then((response) => response.json()) // Parse the JSON response
          .then((data) => {
            if (data.success) {
              alert("Appointment Cancelled!");
              removeData(recordId);
            } else {
              alert("Error: " + data.message);
            }
          })
          .catch((error) => {
            console.error("Error:", error);
            alert("An error occurred.");
          });

        // Close the modal after the request
        modal.style.display = "none";
      });

    // Handle the "No" button (close the modal without doing anything)
    document
      .getElementById("cancelCancel")
      .addEventListener("click", function () {
        modal.style.display = "none";
      });

    // Close the modal when clicking on the "X"
    document.querySelector(".close").addEventListener("click", function () {
      modal.style.display = "none";
    });

    // Close the modal if the user clicks outside of the modal content
    window.addEventListener("click", function (event) {
      if (event.target === modal) {
        modal.style.display = "none";
      }
    });
  });
});

function removeData(recordID) {
  document.getElementById(`patient_${recordID}`).remove();
}
