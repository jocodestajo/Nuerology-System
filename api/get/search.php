<?php
require('../../config/dbcon.php');

$searchTerm = $_GET['inquery'] ?? '';

// Split the search term into individual words
$searchTermsArray = explode(' ', $searchTerm);

// Create dynamic LIKE clauses based on the number of words in the search term
$whereClauses = [];
$bindings = [];

foreach ($searchTermsArray as $term) {
    // Wildcard each term for a "contains" search
    $whereClauses[] = "(lastname LIKE ? OR firstname LIKE ? OR middlename LIKE ?)";
    $bindings[] = "%" . $term . "%";
    $bindings[] = "%" . $term . "%";
    $bindings[] = "%" . $term . "%";
}

if (count($whereClauses) > 0) {
    // Combine all the WHERE conditions with AND
    $whereSql = implode(' AND ', $whereClauses);
    
    // Prepare the SQL query
    $stmt = $conn->prepare("SELECT id, hrn, CONCAT(lastname, ', ', firstname, ' ', middlename) AS name 
                            FROM patient_database 
                            WHERE " . $whereSql);
    
    // Bind parameters dynamically
    $stmt->bind_param(str_repeat('s', count($bindings)), ...$bindings);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<p class='result-item' data-id='" . $row['id'] . "' data-name='" . $row['name'] . "'>" . $row['name'] . "</p>";
        }
    } else {
        echo "<p>No results found.</p>";
    }

    $stmt->close();
}

$conn->close();
?>