<?php
    // Assuming appointments stored in a database
    $dateCounts = [];
    $query = $pdo->query("SELECT date, COUNT(*) as count FROM appointments GROUP BY date");
    while ($row = $query->fetch()) {
        $dateCounts[$row['date']] = $row['count'];
    }
    echo json_encode($dateCounts);
?>
