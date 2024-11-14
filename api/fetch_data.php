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

// Get the ID from the query string
$id = $_GET['id'] ?? '';

$stmt = $conn->prepare("SELECT hrn, CONCAT(lastname, ', ', firstname, ' ', middlename) AS name, 
                        currentage, birthday, contactnumber, 
                        CONCAT(houseno, ' ', barangay, ', ', municipality, ', ', province) AS address 
                        FROM patient_database WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

$data = $result->fetch_assoc();
echo json_encode($data);

$stmt->close();
$conn->close();
?>

