<?php
require_once '../../config/dbcon.php';

// Check if search term is provided
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    
    // Query to search medicines by generic_name or brand_name
    $sql = "SELECT medicine_id, generic_name, brand_name, strength, strength_description 
            FROM medicines 
            WHERE generic_name LIKE '%$search%' 
            OR brand_name LIKE '%$search%' 
            ORDER BY generic_name 
            LIMIT 10";
    
    $result = $conn->query($sql);
    
    $medicines = array();
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $medicines[] = $row;
        }
    }
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($medicines);
} else {
    // Return empty array if no search term
    header('Content-Type: application/json');
    echo json_encode([]);
}

$conn->close();
?> 