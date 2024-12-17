// SEARCH QUERY ///////////////////////////////////////////////////////////////////
document.getElementById("name").addEventListener("keyup", function () {
  let query = this.value;
  if (query.length === 0) {
    document.getElementById("result").innerHTML = "";
    return;
  }

  // AJAX request for search suggestions
  let xhr = new XMLHttpRequest();
  xhr.open("GET", "api/search.php?query=" + encodeURIComponent(query), true);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      document.getElementById("result").innerHTML = xhr.responseText;
    }
  };
  xhr.send();
});

// Event listener for clicking on a result item
document.addEventListener("click", function (e) {
  if (e.target.classList.contains("result-item")) {
    let name = e.target.getAttribute("data-name");
    let id = e.target.getAttribute("data-id");

    console.log(id);

    document.getElementById("name").value = name;
    document.getElementById("result").innerHTML = "";

    // AJAX request to fetch full details for selected item
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "api/fetch_data.php?id=" + id);
    // encodeURIComponent(id), true
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

        // Set fields to disabled
        document.getElementById("hrn").readOnly = true;
        document.getElementById("name").readOnly = true;
        document.getElementById("age").readOnly = true;
        document.getElementById("birthday").readOnly = true;
        document.getElementById("address").readOnly = true;
        document.getElementById("clientSelect").readOnly = true;

        const contact = document.getElementById("contact");
        if (contact.value !== "") {
          contact.readOnly = true;
        }

        // Set client type to "Old Patient"
        document.getElementById("clientSelect").value = "Old Patient";
      }
    };
    xhr.send();
  }
});

//
// RADIO BUTTON IF NEW OR OLD PATIENT ///////////////////////////////////////////
const clientSelect = document.getElementById("clientSelect");
const teleconsultRadio = document.getElementById("teleconsult");

clientSelect.addEventListener("change", function () {
  if (this.value === "New Patient") {
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

//
// CLEAR DATA and REMOVE DISABLED ATTRIBUTES
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

//
// Function to handle CHECKBOXES behavior per TABLE
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

//
// NAV-LIST in TABLET MODE //////////////////////////////////////////////////////
const menuToggle = document.getElementById("menu-toggle");
const navList = document.querySelector(".nav-list");

// Add event listener to toggle the visibility of the nav-list
menuToggle.addEventListener("click", () => {
  navList.classList.toggle("show");
});
