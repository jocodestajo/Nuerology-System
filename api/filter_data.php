<?php
include 'dbcon.php'; // Include your database connection

$day = isset($_GET['day']) ? $_GET['day'] : '';
$month = isset($_GET['month']) ? $_GET['month'] : '';
$year = isset($_GET['year']) ? $_GET['year'] : '';

// Build the query dynamically based on selected filters
$query = "SELECT * FROM neurology_records WHERE status = 'pending'";
$conditions = [];

if (!empty($day)) {
    $conditions[] = "DAY(date_sched) = '$day'";
}
if (!empty($month)) {
    $conditions[] = "MONTH(date_sched) = '$month'";
}
if (!empty($year)) {
    $conditions[] = "YEAR(date_sched) = '$year'";
}

// Append conditions to query if any
if (count($conditions) > 0) {
    $query .= " AND " . implode(' AND ', $conditions);
}

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    while ($records = mysqli_fetch_assoc($result)) {
        ?>
        <tr id="patient_<?=$records['id'];?>">
            <td class="th-check border-left"><input type="checkbox" class="checkbox custom-checkbox"></td>
            <td class="th-hrn"><?= $records['hrn']; ?></td>
            <td class="th-name"><?= $records['name']; ?></td>
            <td class="th-contact"><?= $records['contact']; ?></td>
            <td class="th-schedule"><?= $records['date_sched']; ?></td>
            <td class="th-complaint"><?= $records['complaint']; ?></td>
            <td class="th-action action border-right">
                <img src="img/check-circle.png" class="action-img update-approve margin-right" alt="image here" data-id="<?=$records['id'];?>">
                <img src="img/edit.png" class="action-img view-button margin-right" alt="image here" data-record-id="<?=$records['id'];?>">
                <img src="img/cancel.png" class="action-img update-cancelled" alt="image here" data-id="<?=$records['id'];?>">
            </td>
        </tr>
        <?php
    }
} else {
    echo "<tr><td colspan='7' style='text-align: center; font-size: 2rem; padding: 32px 0 32px 0;'>No records found</td></tr>";
}
?>