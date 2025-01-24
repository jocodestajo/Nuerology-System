<?php
 // Include your database connection here
require '../../config/dbcon.php';

// Check if the id is set and is numeric (to prevent SQL injection)
if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $recordId = $_POST['id'];

    try {
        // Prepare the SQL statement to update the record
        $sql = "UPDATE neurology_records SET status = '1' WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $recordId, PDO::PARAM_INT);
        
        // Execute the query
        if ($stmt->execute()) {
            // Send a success response back to the client
            echo json_encode(['success' => true]);
        } else {
            // Send an error response
            echo json_encode(['success' => false]);
        }
    } catch (PDOException $e) {
        // Handle database errors
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    // If no valid id is provided
    echo json_encode(['success' => false, 'message' => 'Invalid ID']);
}
?>
