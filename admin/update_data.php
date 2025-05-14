<?php
// 1-52 is made using chatgpt


require_once 'config.php';

// Check if the user is logged in as admin
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: /login.php");
    exit();
}

// Check if the form is submitted via GET method
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    // Getting form data from URL parameters
    $patientName = $_GET['patientName'] ?? '';
    $age = $_GET['age'] ?? '';
    $patientProblem = $_GET['patientProblem'] ?? '';
    $medicine = $_GET['medicine'] ?? '';
    $date = $_GET['date'] ?? '';
    $nextDate = $_GET['nextDate'] ?? '';
    $notes = $_GET['notes'] ?? '';

    // Validate the data
    if (empty($patientName) || empty($age) || empty($patientProblem) || empty($medicine) || empty($date)) {
        echo json_encode(['message' => 'All required fields must be filled out']);
        exit();
    }

    // Insert data into the patientdata table
    $stmt = $conn->prepare("INSERT INTO patientdata (PatientName, Age, PatientProblem, PrescribedMedicine, Date, NextAppointment, Notes) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisssss", $patientName, $age, $patientProblem, $medicine, $date, $nextDate, $notes);
    
    if ($stmt->execute()) {
        // Log the action into the logs table
        $logMessage = "Added patient data for: " . $patientName;
        
        // Insert log into logs table
        $logStmt = $conn->prepare("INSERT INTO logs (Log) VALUES (?)");
        $logStmt->bind_param("s", $logMessage);
        $logStmt->execute();
        $logStmt->close();

        echo json_encode(['message' => 'Patient data successfully added and logged!']);
    } else {
        echo json_encode(['message' => 'Error adding data: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
