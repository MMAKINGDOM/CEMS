<?php
// The whole code here is made using CHATGPT



require_once 'config.php';

// Fetch logs from the database
$query = "SELECT * FROM logs ORDER BY timestamp DESC";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $logs = [];
    while ($row = $result->fetch_assoc()) {
        $logs[] = [
            'description' => $row['Log'],
            'timestamp' => $row['timestamp']
        ];
    }

    // Send response as JSON
    echo json_encode($logs);
} else {
    echo json_encode([]);
}

$conn->close();
?>
