<?php
// Include database connection
require_once '../../config/dbcon.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Get year and month from query parameters
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$month = isset($_GET['month']) ? intval($_GET['month']) : date('m');

try {
    // Prepare the query to count appointments by date and type
    $query = "SELECT 
                DATE(date_sched) as date,
                consultation,
                COUNT(*) as count
              FROM neurology_consultations 
              WHERE YEAR(date_sched) = ? 
              AND MONTH(date_sched) = ?
              AND status = 'approved'
              GROUP BY DATE(date_sched), consultation";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $year, $month);
    $stmt->execute();
    $result = $stmt->get_result();

    // Initialize the response array
    $appointmentCounts = array();

    // Process the results
    while ($row = $result->fetch_assoc()) {
        $date = $row['date'];
        $type = strtolower($row['consultation']) === 'face to face' ? 'f2f' : 'telecon';
        $count = intval($row['count']);

        // Initialize the date in the array if it doesn't exist
        if (!isset($appointmentCounts[$date])) {
            $appointmentCounts[$date] = array(
                'f2f' => 0,
                'telecon' => 0
            );
        }

        // Add the count for the appropriate type
        $appointmentCounts[$date][$type] = $count;
    }

    // Return the JSON response
    echo json_encode($appointmentCounts);

} catch (Exception $e) {
    // Return error message as JSON
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}

// Close the database connection
$stmt->close();
$conn->close();
?> 