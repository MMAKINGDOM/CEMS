<?php

// The following PHP code is written using CHATGPT, only the HTML code below is made by me <3



session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: /login.php");
    exit();
}

// Database configuration
require_once 'config.php';

// Search query logic
$searchQuery = isset($_GET['q']) ? $_GET['q'] : '';

// Fetch data from deletedpatients table
$sql = "SELECT * FROM deletedpatients";
if ($searchQuery) {
    $sql .= " WHERE PatientName LIKE ?";
}

$stmt = $conn->prepare($sql);
if ($searchQuery) {
    $searchParam = "%" . $searchQuery . "%";
    $stmt->bind_param("s", $searchParam);
}

$stmt->execute();
$result = $stmt->get_result();
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Deleted Patients</title>
  <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700|Luckiest+Guy|Oxygen:300,400" rel="stylesheet">
  <link href="Data.css" type="text/css" rel="stylesheet">
</head>
<body>
  <ul class="navigation">
    <li>Welcome Dr. <?php echo htmlspecialchars($_SESSION['username']); ?></li>
    <li onclick="redirectTo('/')"><a href="./">Administration</a></li>
    <li onclick="redirectTo('/data.php')"><a href="./data.php">Patient Records</a></li>
    <li onclick="redirectTo('/requests.php')"><a href="./requests.php">Appointment Requests</a></li>
    <li class="active">Deleted Data</li>
  </ul>
  
  <div class="search">
    <label for="searchInput">Name</label>
    <input type="text" id="searchInput" value="<?php echo htmlspecialchars($searchQuery); ?>" />
    <button type="submit" onclick="searchPatient()">Search</button>
  </div>
  
  <div id="searchResultsMessage"></div>
  
  <table id="dataTable">
    <thead>
        <tr>
          <th>Patient Name</th>
          <th>Age</th>
          <th>Patient Problem</th>
          <th>Prescribed Medicine</th>
          <th>Date</th>
          <th>Next Appointment</th>
          <th>Notes</th>
          <th>Deleted At</th>
        </tr>
    </thead>
    <tbody>
      <?php foreach ($data as $index => $row): ?>
        <tr>
          <td><?php echo htmlspecialchars($row['PatientName']); ?></td>
          <td><?php echo htmlspecialchars($row['Age']); ?></td>
          <td><?php echo htmlspecialchars($row['PatientProblem']); ?></td>
          <td><?php echo htmlspecialchars($row['PrescribedMedicine']); ?></td>
          <td><?php echo htmlspecialchars($row['Date']); ?></td>
          <td><?php echo htmlspecialchars($row['NextAppointment']); ?></td>
          <td><?php echo htmlspecialchars($row['Notes']); ?></td>
          <td><?php echo htmlspecialchars($row['DeletedAt']); ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <script>
    function searchPatient() {
      const searchValue = document.getElementById('searchInput').value;
      window.location.href = `?q=${encodeURIComponent(searchValue)}`;
    }

    function redirectTo(pathName) {
      const url = `${window.location.origin}/Admin${pathName}`;
      window.location.href = url;
    }
    function redirectTo2(url) {
      open('https://github.com/MMAKINGDOM');
    }
  </script>

  <footer class="Poweredbycera" onclick="redirectTo2()">
    <h5>Powered by Muntadhar</h5>
  </footer>
</body>
</html>
