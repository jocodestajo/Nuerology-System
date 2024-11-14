<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ihoms_inventory";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$searchTerm = $_GET['query'] ?? '';

// Search query with concatenated fields for full name
$stmt = $conn->prepare("SELECT id, hrn, CONCAT(lastname, ', ', firstname, ' ', middlename) AS name 
                        FROM patient_database 
                        WHERE CONCAT(lastname, ' ', firstname, ' ', middlename) LIKE ?");
$searchTermWildcard = "%" . $searchTerm . "%";
$stmt->bind_param("s", $searchTermWildcard);
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
$conn->close();
?>

