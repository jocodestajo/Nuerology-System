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
