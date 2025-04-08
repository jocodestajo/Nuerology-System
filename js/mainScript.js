// TABS FUNCTION (navbar-2) ////////////////////////////////////////////////////////////////////
function showContent(index) {
  let contents = document.querySelectorAll(".content");
  let tabs = document.querySelectorAll(".tab");
  contents.forEach(function (content) {
    content.classList.remove("active");
  });

  // Show the content of the clicked tab
  if (contents[index]) contents[index].classList.add("active");

  // Remove active class from all tabs and add it to the clicked tab
  tabs.forEach(function (tab) {
    tab.classList.remove("active");
  });
  if (tabs[index]) tabs[index].classList.add("active");

  // Uncheck all checkboxes
  const checkboxes = document.querySelectorAll(".checkbox");
  if (checkboxes && checkboxes.length > 0) {
    checkboxes.forEach((checkbox) => {
      checkbox.checked = false;
    });
  }

  // hide the button div
  const buttonDiv = document.querySelector(".btn-div-checkbox");
  if (buttonDiv) buttonDiv.classList.remove("show");

  // Save the current tab index
  localStorage.setItem("activeTab", index);
}

// Add this to restore the active tab on page load
document.addEventListener("DOMContentLoaded", function () {
  // Get the saved tab index from localStorage
  const activeTab = localStorage.getItem("activeTab");

  // If there was a saved tab, switch to it
  if (activeTab !== null) {
    showContent(parseInt(activeTab));
  }
});

// NAV-LIST in TABLET MODE //////////////////////////////////////////////////////
const menuToggle = document.getElementById("menu-toggle");
const navList = document.querySelector(".nav-list");

// Add event listener to toggle the visibility of the nav-list
menuToggle.addEventListener("click", () => {
  navList.classList.toggle("show");
});

// REFERRAL DISPLAY NONE / BLOCK
document.querySelectorAll(".appointment");

// SEARCH QUERY ///////////////////////////////////////////////////////////////////
const searchResult = document.getElementById("result");
const nameInput = document.getElementById("name");
const searchResultModal = document.getElementById("searchResult-modal");

let debounceTimeout;

function closeModal() {
  searchResult.style.display = "none";
  searchResultModal.style.display = "none";
}

if (nameInput) {
  nameInput.addEventListener("keyup", function () {
    console.log("Keyup", nameInput.value);
    clearTimeout(debounceTimeout);

    debounceTimeout = setTimeout(function () {
      let inquery = nameInput.value.trim(); // Trim whitespace
      if (inquery !== "") {
        searchResult.style.display = "block";
        searchResultModal.style.display = "block";

        // AJAX request for search suggestions
        let xhr = new XMLHttpRequest();
        xhr.open(
          "GET",
          "api/get/search.php?inquery=" + encodeURIComponent(inquery),
          true
        );
        xhr.onreadystatechange = function () {
          if (xhr.readyState === 4 && xhr.status === 200) {
            console.log("AJAX response status: ", xhr.status);
            console.log("AJAX response: ", xhr.responseText);
            if (xhr.responseText.trim() !== "") {
              searchResult.innerHTML = xhr.responseText;
            } else {
              searchResult.innerHTML = "<p>No results found</p>";
            }
          } else if (xhr.readyState === 4) {
            console.error("Error with request:", xhr.status);
          }
        };
        xhr.send();
      } else {
        closeModal();
      }
    }, 300);
  });
}

// Close modal on outside click
document.addEventListener("click", function (event) {
  const resultItem = event.target.closest(".result-item");

  // If clicking on nameInput or a result item, avoid closing and handle accordingly
  if (event.target === nameInput || resultItem) {
    if (resultItem) {
      let name = resultItem.getAttribute("data-name");
      let id = resultItem.getAttribute("data-id");

      // console.log("ID:", id);
      // console.log("Name:", name);

      // Fetch full details for the selected item
      let xhr = new XMLHttpRequest();
      xhr.open("GET", "api/get/fetch_data.php?id=" + id, true);
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
          let data = JSON.parse(xhr.responseText);
          console.log(data);

          document.getElementById("hrn").value = data.hrn;
          document.getElementById("name").value = data.name;

          // Convert and set birthday
          if (data.birthday) {
            let formattedDate = formatDate(data.birthday);

            console.log(formattedDate);
            document.getElementById("birthday").value = formattedDate;

            // Calculate and set age
            let age = calculateAge(formattedDate);
            console.log(age);
            document.getElementById("age1").value = age;
          }

          // document.getElementById("birthday").value = data.birthday;
          // document.getElementById("age").value = data.currentage;
          document.getElementById("contact").value = data.contactnumber;
          document.getElementById("address").value = data.address;

          // Set fields to readonly
          document.getElementById("hrn").readOnly = true;
          document.getElementById("name").readOnly = true;
          document.getElementById("age1").readOnly = true;
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
    return;
  }

  // Close the modal if clicking outside the modal, results, or input
  if (
    !searchResultModal.contains(event.target) &&
    !searchResult.contains(event.target) &&
    !nameInput.contains(event.target)
  ) {
    closeModal();
  }
});

// ALERT MESSAGE ON INQUIRY ////////////////////////////////////////////////////////
// Close the modal when clicking on the "X" and "close" button
document.querySelectorAll(".close-floatingAlert").forEach(function (button) {
  button.addEventListener("click", function () {
    floatingAlert.style.position = "unset";
    floatingAlert.style.display = "none";
  });
});

// RADIO BUTTON IF NEW OR OLD PATIENT ///////////////////////////////////////////
const clientSelect = document.getElementById("clientSelect");
const teleconsultRadio = document.getElementById("teleconsult");

if (clientSelect) {
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
}

// CLEAR DATA and REMOVE DISABLED ATTRIBUTES ///////////////////////////////////////
if (document.querySelector("[name='clear_data_btn']")) {
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
}

// CALENDAR ////////////////////////////////////////////////////////////////////
function toggleDropdown() {
  const dropdown = document.getElementById("weekdayDropdown");
  dropdown.classList.toggle("show");
}

// Close the dropdown if clicked outside
document.addEventListener("click", function (event) {
  const dropdown = document.getElementById("weekdayDropdown");
  const weekdayCheckboxes = document.querySelector(".weekday-checkboxes");

  if (!weekdayCheckboxes.contains(event.target)) {
    dropdown.classList.remove("show");
  }
});

// Prevent dropdown from closing when clicking inside
if (document.querySelector(".checkbox-group")) {
  document
    .querySelector(".checkbox-group")
    .addEventListener("click", function (event) {
      event.stopPropagation();
    });
}
