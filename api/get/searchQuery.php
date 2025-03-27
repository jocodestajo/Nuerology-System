<?php
require ('../../config/dbcon.php');

// Get the search query from URL parameters
$query = isset($_GET['query']) ? $_GET['query'] : '';

$sql = "SELECT * FROM neurology_records r LEFT JOIN neurology_consultations c ON r.id = c.record_id WHERE `name` LIKE ? OR `hrn` LIKE ? OR `consultation` LIKE ? OR `status` LIKE ?";

// Prepare statement to avoid SQL injection
$stmt = $conn->prepare($sql);

// Add the wildcard characters to the search term
$searchTerm = "%" . $query . "%";

// Bind parameters: two strings, one for `name` and one for `hrn`
$stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch all records as an associative array
    $appointments = array();
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }

    // Return the results as JSON
    header('Content-Type: application/json');
    echo json_encode($appointments);
} else {
    echo json_encode([]);
}

$conn->close();
?>
