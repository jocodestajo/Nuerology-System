// Get the URL parameters
const urlParams = new URLSearchParams(window.location.search);

// Get the 'id' value
const recordId = urlParams.get("id");

// Make an AJAX request to fetch the data for this record
fetch("api/get/fetch_record.php?id=" + recordId)
  .then((response) => {
    if (!response.ok) {
      throw new Error("HTTP error! Status: " + response.status);
    }
    return response.text();
  })
  .then((text) => {
    // console.log("Raw response:", text);
    return text ? JSON.parse(text) : {};
  })
  .then((data) => {
    // console.log("Parsed JSON:", data);

    if (!data || Object.keys(data).length === 0) {
      throw new Error("Empty or invalid JSON response");
    }

    // Populate personal information fields
    document.querySelector('input[name="name"]').value = data.name || "";
    document.querySelector('input[name="blood_pressure"]').value =
      data.blood_pressure || "";
    document.querySelector('input[name="temperature"]').value =
      data.temperature || "";
    document.querySelector('input[name="heart_rate"]').value =
      data.heart_rate || "";
    document.querySelector('input[name="respiratory_rate"]').value =
      data.respiratory_rate || "";
    document.querySelector('input[name="height"]').value = data.height || "";
    document.querySelector('input[name="oxygen_saturation"]').value =
      data.oxygen_saturation || "";
    document.querySelector('input[name="weight"]').value = data.weight || "";
    document.querySelector('textarea[name="notes"]').value =
      data.vs_notes || "";
    document.querySelector('input[name="vs_start"]').value =
      data.vs_start || "";
    document.querySelector('input[name="vs_end"]').value = data.vs_end || "";

    // Set hidden record_id field
    document.querySelector('input[name="record_id"]').value = recordId;
  })
  .catch((error) => {
    // console.error("Fetch error:", error);
    alert("Error loading patient data. Please try again.");
  });
