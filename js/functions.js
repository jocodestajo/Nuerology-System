// DATE FORMAT CONVERTION
function formatDate(inputDate) {
  let months = {
    Jan: "01",
    Feb: "02",
    Mar: "03",
    Apr: "04",
    May: "05",
    Jun: "06",
    Jul: "07",
    Aug: "08",
    Sep: "09",
    Oct: "10",
    Nov: "11",
    Dec: "12",
  };

  let parts = inputDate.split("-"); // Split "25-Sep-1968" into ["25", "Sep", "1968"]
  if (parts.length !== 3) {
    console.error("Invalid date format:", inputDate);
    return ""; // Return empty if format is incorrect
  }

  let day = parts[0].padStart(2, "0"); // Ensure 2-digit day
  let month = months[parts[1]]; // Convert "Sep" to "09"
  let year = parts[2]; // Get year

  return `${year}-${month}-${day}`; // Return in "YYYY-MM-DD" format
}

// calculate age from birthday
function calculateAge(birthday) {
  let birthDate = new Date(birthday); // Convert "YYYY-MM-DD" to Date object
  let today = new Date(); // Get today's date

  let age = today.getFullYear() - birthDate.getFullYear(); // Year difference
  let monthDiff = today.getMonth() - birthDate.getMonth(); // Month difference
  let dayDiff = today.getDate() - birthDate.getDate(); // Day difference

  // If birthday hasn't occurred yet this year, subtract 1 from age
  if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) {
    age--;
  }

  return age;
}

if (document.querySelectorAll(".birthdayInput")) {
  document.querySelectorAll(".birthdayInput").forEach((input) => {
    input.addEventListener("input", function () {
      let birthday = this.value; // Get selected date
      let ageOutputId = this.getAttribute("data-age-output"); // Get linked span ID
      let age = calculateAge(birthday);

      document.getElementById(ageOutputId).value = age;
      document.getElementById(ageOutputId).readOnly = true;

      if (age <= 0) {
        document.getElementById(ageOutputId).value = "";
        document.getElementById(ageOutputId).readOnly = false;
      }
    });
  });
}
