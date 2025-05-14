<?php
session_start();

// 1-9 is made using chatgpt
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: /login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Clinic Data Management</title>
  <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700|Luckiest+Guy|Oxygen:300,400" rel="stylesheet">
  <link href="add.css" type="text/css" rel="stylesheet">
</head>
<body>

<!-- Navigation Bar -->
<ul class="navigation">
    <li>Welcome Dr. <?php echo htmlspecialchars($_SESSION['username']); ?></li>
    <li class="active">Administration</li>
    <li onclick="redirectTo('/data.php')"><a href="./data.php">Patient Records</a></li>
    <li onclick="redirectTo('/requests.php')"><a href="./requests.php">Appointment Requests</a></li>
    <li onclick="redirectTo('/delete.php')"><a href="./delete.php">Deleted Data</a></li>
</ul>

<div class="container">

  <div class="form-container">

    <form id="dataForm" action="update_data.php" method="POST">

      <div class="form-group">

        <label for="patientName">Patient Name:</label>

        <input type="text" id="patientName" name="patientName" required>

      </div>

      <div class="form-group">

        <label for="age">Age:</label>

        <input type="number" id="age" name="age" required>

      </div> <!-- IDS ARE NECESSARY DONT FUCKING CHANGE THEM!!! -->
      <div class="form-group">

        <label for="patientProblem">Patient Problem:</label>

        <input type="text" id="patientProblem" name="patientProblem" required>

      </div>

      <div class="form-group">

        <label for="medicine">Prescribed Medicine:</label>

        <input type="text" id="medicine" name="medicine" required>

      </div>

      <div class="form-group">

        <label for="date">Date:</label>

        <input type="date" id="date" name="date" required>

      </div>

      <div class="form-group">

        <label for="nextDate">Next Appointment Date (Optional):</label>

        <input type="date" id="nextDate" name="nextDate">

      </div>

      <div class="form-group">

        <label for="notes">Additional Notes (Optional):</label>

        <input type="text" id="notes" name="notes">

      </div>

      <button type="submit">Submit to Database</button>

    </form>

  </div>

  <div class="logs-container">
    <h2>Activity Logs</h2>
    <div class="search">
      <input type="text" id="searchInput" placeholder="Enter keyword...">
      <button type="submit" onclick="searchLogs()">Search</button>
    </div>

    <div id="searchResultsMessage"></div>

    <table id="logTable">
      <thead>
        <tr>
          <th>#</th>
          <th>Log Description</th>
          <th>Timestamp</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</div>

<script>
  document.getElementById('dataForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(this);
    const queryString = new URLSearchParams(formData).toString(); 
    fetch(`update_data.php?${queryString}`)
      .then(response => response.json()) // json response 
      .then(data => {
        alert(data.message); // alerting user with successmsg
        window.location.href = './Data.php'; // redirecting
      })
      .catch(error => console.error('Error:', error));
  });
  async function loadLogs() {
    try {
      const response = await fetch('get_logs.php'); // db
      const logs = await response.json();
      const params = new URLSearchParams(window.location.search);
      const searchQuery = params.get('logkeyword') || "";
      document.getElementById('searchInput').value = searchQuery;
      const tableBody = document.querySelector('#logTable tbody');
      tableBody.innerHTML = '';
      let resultsCount = 0;
      logs.forEach((log, index) => {
        if (!searchQuery || log.description.toLowerCase().includes(searchQuery.toLowerCase())) {
          const row = document.createElement('tr');
          row.innerHTML = `
            <td>${index + 1}</td>
            <td>${log.description}</td>
            <td>${new Date(log.timestamp).toLocaleString()}</td>
          `;
          tableBody.appendChild(row);
          resultsCount++;
        }
      });
      document.getElementById('searchResultsMessage').innerHTML = searchQuery 
        ? `Found ${resultsCount} results for '${searchQuery}'`
        : "";
    } catch (error) {
      console.error("Error loading logs:", error);
    }
  }
  function searchLogs() {
    const searchValue = document.getElementById('searchInput').value;
    window.location.href = `?logkeyword=${encodeURIComponent(searchValue)}`;
  }
  function redirectTo(pathName) {
    window.location.href = `${window.location.origin}/Admin${pathName}`;
  }
  loadLogs();
  // getting the parameter `name` 
  const urlParams = new URLSearchParams(window.location.search);
  const Name = urlParams.get('name');

  // parameters thing:
  const PatientName = document.getElementById('patientName');
  if (PatientName) {
  PatientName.value = Name;
  } else {
    console.log('I think there is an error in the name parameter thing, you can find it in the Index.php file');
  }
  const Age = document.getElementById('age');
  const AgeImported = urlParams.get('age');
  if (Age) {
    Age.value = AgeImported
  }  else {
    console.log('I think there is an error in the age parameter thing, you can find it in the Index.php file');
  }
  const PatientProblem = document.getElementById('patientProblem');
  const PatientProblemImported = urlParams.get('problem');
  if (PatientProblem) {
    PatientProblem.value = PatientProblemImported;
  } else {
    console.log('I think there is an error in the problem parameter thing, you can find it in the Index.php file');
  }
  const Medicine = document.getElementById('medicine');
  const MedicineImported = urlParams.get('medicine');
  if (Medicine) {
    Medicine.value = MedicineImported;
  } else {
    console.log('I think there is an error in the medicine parameter thing, you can find it in the Index.php file');
  }
  const Notes = document.getElementById('notes');
  const NotesImported = urlParams.get('notes');
  if (Notes) {
    Notes.value = NotesImported;
  } else {
    console.log('I think there is an error in the notes parameter thing, you can find it in the Index.php file');
  }
  // Made by https://github.com/MMAKINGDOM
</script>
</body>
</html>
