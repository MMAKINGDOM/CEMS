<?php

// THis PHP file is made using ChatGPT



session_start();
require_once 'config.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    echo json_encode(["message" => "Unauthorized access"]);
    exit();
}

if (!isset($_GET['deleteIndex'])) {
    echo json_encode(["message" => "No ID provided"]);
    exit();
}

$patientId = intval($_GET['deleteIndex']);

// 1. Fetch the data before deleting it
$sqlSelect = "SELECT * FROM patientdata WHERE Id = ?";
$stmtSelect = $conn->prepare($sqlSelect);
$stmtSelect->bind_param("i", $patientId);
$stmtSelect->execute();
$result = $stmtSelect->get_result();
$patientData = $result->fetch_assoc();
$stmtSelect->close();

if (!$patientData) {
    echo json_encode(["message" => "Patient not found"]);
    exit();
}

// 2. Insert into `deletedpatients`
$sqlInsert = "INSERT INTO deletedpatients (PatientName, Age, PatientProblem, PrescribedMedicine, Date, NextAppointment, Notes)
              VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmtInsert = $conn->prepare($sqlInsert);
$stmtInsert->bind_param(
    "sisssss",
    $patientData['PatientName'],
    $patientData['Age'],
    $patientData['PatientProblem'],
    $patientData['PrescribedMedicine'],
    $patientData['Date'],
    $patientData['NextAppointment'],
    $patientData['Notes']
);
$stmtInsert->execute();
$stmtInsert->close();

// 3. Delete from `patientdata`
$sqlDelete = "DELETE FROM patientdata WHERE Id = ?";
$stmtDelete = $conn->prepare($sqlDelete);
$stmtDelete->bind_param("i", $patientId);
$stmtDelete->execute();
$stmtDelete->close();

$conn->close();

echo json_encode(["message" => "Appointment canceled and moved to deleted patients!"]);
?>
