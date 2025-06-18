<?php
require('../../config/dbcon.php');

$searchTerm = $_GET['query'] ?? '';

// Split the search term into individual words
$searchTermsArray = explode(' ', $searchTerm);

// Create dynamic LIKE clauses based on the number of words in the search term
$whereClauses = [];
$bindings = [];

foreach ($searchTermsArray as $term) {
    // Wildcard each term for a "contains" search
    $whereClauses[] = "(firstname LIKE ? OR middlename LIKE ? OR lastname LIKE ?)";
    $bindings[] = "%" . $term . "%";
    $bindings[] = "%" . $term . "%";
    $bindings[] = "%" . $term . "%";
}

if (count($whereClauses) > 0) {
    // Combine all the WHERE conditions with AND
    $whereSql = implode(' AND ', $whereClauses);
    
    // Prepare the SQL query
    $stmt = $conn->prepare("SELECT id, hrn, CONCAT(firstname, ' ', middlename, ' ', lastname) AS name, 
                           birthday, contactnumber AS contact, 
                           CONCAT(houseno, ' ', barangay, ', ', municipality, ', ', province) AS address 
                           FROM patient_database 
                           WHERE " . $whereSql);
    
    // Bind parameters dynamically
    $stmt->bind_param(str_repeat('s', count($bindings)), ...$bindings);

    $stmt->execute();
    $result = $stmt->get_result();

    $patients = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $patients[] = $row;
        }
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($patients);
} else {
    echo json_encode([]);
}

$stmt->close();
$conn->close();
?> 