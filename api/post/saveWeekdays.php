<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../../config/dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $weekdays = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON data');
        }
        
        // Clear existing weekday settings
        $clearQuery = "DELETE FROM weekday_settings";
        if (!mysqli_query($conn, $clearQuery)) {
            throw new Exception('Failed to clear existing settings');
        }
        
        // Insert new settings
        foreach ($weekdays as $day => $isChecked) {
            $day = mysqli_real_escape_string($conn, $day);
            $isChecked = $isChecked ? 1 : 0;
            
            $query = "INSERT INTO weekday_settings (day_name, is_enabled) VALUES ('$day', $isChecked)";
            if (!mysqli_query($conn, $query)) {
                throw new Exception('Failed to insert new settings');
            }
        }
        
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}
?>