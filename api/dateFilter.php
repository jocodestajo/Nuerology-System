<?php
// Include your database connection
require '../config/dbcon.php';

// Get the selected date from the request (if provided)
$dateFilter = isset($_GET['dateFilter']) ? $_GET['dateFilter'] : '';

// Build the query based on the selected date filter
if ($dateFilter) {
    $query = "SELECT * FROM neurology_records WHERE status = 'pending' AND date_request = '$dateFilter' ORDER BY date_request DESC";
} else {
    $query = "SELECT * FROM neurology_records WHERE status = 'pending' ORDER BY date_request DESC";
}

$query_run = mysqli_query($conn, $query);

// Output the filtered table rows
if (mysqli_num_rows($query_run) > 0) {
    foreach ($query_run as $records) {
        echo "<tr>";
        echo "<td class='th-check border-left'><input type='checkbox' class='checkbox custom-checkbox'></td>";
        echo "<td class='th-hrn'>" . $records['hrn'] . "</td>";
        echo "<td class='th-name'>" . $records['name'] . "</td>";
        echo "<td class='th-typeOfClient'>" . $records['old_new'] . "</td>";
        echo "<td class='th-contact'>" . $records['contact'] . "</td>";
        echo "<td class='th-schedule'>" . $records['date_sched'] . "</td>";
        echo "<td class='th-complaint'>" . $records['complaint'] . "</td>";
        echo "<td class='th-action action border-right'>
                <img src='img/check-circle.png' class='action-img update-approve margin-right' alt='Approve' data-id='".$records['id']."'>
                <img src='img/edit.png' class='action-img view-button margin-right' alt='View' data-record-id='".$records['id']."'>
                <img src='img/cancel.png' class='action-img update-cancelled' alt='Cancel' data-id='".$records['id']."'>
            </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='8' style='text-align: center;'>No records found</td></tr>";
}
?>


<div class="content active">
                <!-- <div>    
                    <label for="dateFilterPending">Date</label>
                    <input type="date" class="dateFilterPending">
                </div> -->

                <!-- Table to display the data -->
                <table>
                    <thead>
                        <tr>
                            <th class="th-check border-left"><input type="checkbox" class="checkbox checkbox-header custom-checkbox"></th>
                            <th class="th-hrn">HRN</th>
                            <th class="th-name">Name</th>
                            <th class="th-typeOfClient">Client Type</th>
                            <th class="th-contact">Contact</th>
                            <th class="th-schedule">Schedule</th>
                            <th class="th-complaint">Complaint</th>
                            <th class="th-action border-right">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <!-- Data will be populated here by AJAX -->
                        <?php
                            // Default query (without any date filter)
                            $query = "SELECT * FROM neurology_records WHERE status = 'pending' ORDER BY date_request DESC";
                            $query_run = mysqli_query($conn, $query);

                            // If records found, display them
                            if (mysqli_num_rows($query_run) > 0) {
                                foreach ($query_run as $records) {
                                    echo "<tr>";
                                    echo "<td class='th-check border-left'><input type='checkbox' class='checkbox custom-checkbox'></td>";
                                    echo "<td class='th-hrn'>" . $records['hrn'] . "</td>";
                                    echo "<td class='th-name'>" . $records['name'] . "</td>";
                                    echo "<td class='th-typeOfClient'>" . $records['old_new'] . "</td>";
                                    echo "<td class='th-contact'>" . $records['contact'] . "</td>";
                                    echo "<td class='th-schedule'>" . $records['date_sched'] . "</td>";
                                    echo "<td class='th-complaint'>" . $records['complaint'] . "</td>";
                                    echo "<td class='th-action action border-right'>
                                            <img src='img/check-circle.png' class='action-img update-approve margin-right' alt='Approve' data-id='".$records['id']."'>
                                            <img src='img/edit.png' class='action-img view-button margin-right' alt='View' data-record-id='".$records['id']."'>
                                            <img src='img/cancel.png' class='action-img update-cancelled' alt='Cancel' data-id='".$records['id']."'>
                                        </td>";
                                    echo "</tr>";
                                }
                            } else {
                                // If no records found, display this message
                                echo "<tr><td colspan='8' style='text-align: center;'>No records found</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>