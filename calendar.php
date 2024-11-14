<?php
// Set the timezone
date_default_timezone_set("Asia/Manila"); // Adjust to your timezone

// Get current year and month
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');
$month = isset($_GET['month']) ? $_GET['month'] : date('m');

// Number of days in the month
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

// First day of the month
$firstDayOfMonth = date('w', strtotime("$year-$month-01"));

// Calendar title (month and year)
$monthName = date('F', strtotime("$year-$month-01"));

// Generate previous and next month links
$prevMonth = $month - 1 > 0 ? $month - 1 : 12;
$nextMonth = $month + 1 <= 12 ? $month + 1 : 1;
$prevYear = $month - 1 > 0 ? $year : $year - 1;
$nextYear = $month + 1 <= 12 ? $year : $year + 1;

echo "<h2><a href='?year=$prevYear&month=$prevMonth'>&lt; </a> $monthName $year <a href='?year=$nextYear&month=$nextMonth'> &gt;</a></h2>";

// Start table
echo "<table border='1' cellspacing='0' cellpadding='5'>";
echo "<tr>
        <th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th>
      </tr>";

// Fill the first row with blank cells if the month doesn't start on Sunday
echo "<tr>";
for ($i = 0; $i < $firstDayOfMonth; $i++) {
    echo "<td></td>";
}

// Add days
for ($day = 1; $day <= $daysInMonth; $day++) {
    // Check if this is the current day
    $isToday = ($day == date('j') && $month == date('m') && $year == date('Y'));

    echo "<td" . ($isToday ? " style='background-color: #f0f8ff;'" : "") . ">";

    // Add a link to schedule appointments
    echo "<a href='schedule.php?year=$year&month=$month&day=$day'>$day</a>";
    echo "</td>";

    // Break the row after Saturday
    if (($day + $firstDayOfMonth) % 7 == 0) {
        echo "</tr><tr>";
    }
}

// Complete the last row with blank cells
for ($i = ($day + $firstDayOfMonth) % 7; $i < 7 && $i != 0; $i++) {
    echo "<td></td>";
}

echo "</tr>";
echo "</table>";
?>
