<?php
session_start();

// The following PHP code is written using chatgpt


if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: /login.php");
    exit();
}
require_once 'config.php';
$searchQuery = isset($_GET['q']) ? $_GET['q'] : '';
// fetching data basedon queris 
$sql = "SELECT * FROM patientdata";
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

<!-- the code below is all made by me:) -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Patient Data</title>
  <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700|Luckiest+Guy|Oxygen:300,400" rel="stylesheet">
  <link href="Data.css" type="text/css" rel="stylesheet">
</head>
<body>
  <ul class="navigation">
    <li>Welcome Dr. <?php echo htmlspecialchars($_SESSION['username']); ?></li>
    <li onclick="redirectTo('/')"><a href="./">Administration</a></li>
    <li class="active">Patient Records</li>
    <li onclick="redirectTo('/Requests.php')"><a href="./Requests.php">Appointment Requests</a></li>
    <li onclick="redirectTo('/Delete.php')"><a href="./Delete.php">Deleted Data</a></li>
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
          <th>Actions</th>
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
          <td><button onclick="deleteRow(<?php echo $row['Id']; ?>)">Cancel Appointment</button></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <script>
    function searchPatient() {
      const searchValue = document.getElementById('searchInput').value;
      window.location.href = `?q=${encodeURIComponent(searchValue)}`;
    }

    function deleteRow(id) {
      if (!confirm("Are you sure you want to cancel this appointment?")) return;

      fetch(`delete_appointment.php?deleteIndex=${id}`)
        .then(response => response.json())
        .then(data => {
          alert(data.message); // alerting success message
          location.reload(); // reloading the table :D
        })
        .catch(error => console.error("Error deleting data:", error));
    }

    function redirectTo(pathName) {
      const url = `${window.location.origin}/Admin${pathName}`;
      window.location.href = url;
    }
    function redirectTo2(url) {
      // redirecting users to my github page
      open('https://github.com/MMAKINGDOM');
    }
  </script>

  <footer class="Poweredbycera" onclick="redirectTo2()">
    <h5>Powered by Muntadhar</h5>
  </footer>
</body>
</html>
