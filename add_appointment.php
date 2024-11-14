<?php
$year = $_GET['year'];
$month = $_GET['month'];
$day = $_GET['day'];

echo "<h2>Add Appointment for $month/$day/$year</h2>";
?>

<form action="save_appointment.php" method="POST">
    <input type="hidden" name="year" value="<?php echo $year; ?>">
    <input type="hidden" name="month" value="<?php echo $month; ?>">
    <input type="hidden" name="day" value="<?php echo $day; ?>">

    <label for="time">Time:</label>
    <input type="time" name="time" required>

    <label for="description">Description:</label>
    <input type="text" name="description" required>

    <button type="submit">Save Appointment</button>
</form>
