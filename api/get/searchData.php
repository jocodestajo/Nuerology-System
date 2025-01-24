<?php
require('../../config/dbcon.php');

// Function to search the database
function searchNeurologyRecords($searchTerm) {
    global $pdo;
    
    // Prepare the SQL query to search the neurology_records table
    $stmt = $pdo->prepare("SELECT * FROM neurology_records WHERE name LIKE :searchTerm");
    
    // Bind parameters and execute the query
    $stmt->execute(['searchTerm' => '%' . $searchTerm . '%']);
    
    // Fetch all results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Display the results in a table
    if ($results) {
        echo "<table border='1'>
                <thead>
                    <tr>
                        <th>Record ID</th>
                        <th>Name</th>
                        <th>Appointment Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>";
        foreach ($results as $row) {
            echo "<tr>
                    <td>{$row['record_id']}</td>
                    <td>{$row['record_name']}</td>
                    <td>{$row['appointment_date']}</td>
                    <td>
                        <button class='update-approve' data-id='{$row['record_id']}'>Approve</button>
                        <button class='update-processed' data-id='{$row['record_id']}'>Processed</button>
                        <button class='update-cancelled' data-id='{$row['record_id']}'>Cancel</button>
                    </td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No records found.</p>";
    }
}
?>
