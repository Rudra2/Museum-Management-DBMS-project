<!DOCTYPE html>
<html>
<head>
  <title>Search Exhibitions</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }
    h1 {
      font-size: 36px;
      text-align: center;
      margin: 20px 0;
    }
    form {
      max-width: 500px;
      margin: 0 auto;
      border: 1px solid #ccc;
      padding: 20px;
      border-radius: 5px;
    }
    label {
      display: block;
      margin-bottom: 10px;
      font-size: 18px;
    }
    input[type="text"] {
      font-size: 18px;
      padding: 10px;
      border-radius: 5px;
      border: 1px solid #ccc;
      width: 100%;
      margin-bottom: 20px;
    }
    input[type="submit"] {
      background-color: #4CAF50;
      color: white;
      font-size: 18px;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    input[type="submit"]:hover {
      background-color: #3e8e41;
    }
    table {
      border-collapse: collapse;
      margin: 20px auto;
      width: 90%;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }
    th {
      background-color: #f2f2f2;
    }
  </style>
</head>
<body>
  <h1>Search Exhibitions</h1>
  <form method="post">
    <label for="staff_id">Staff ID:</label>
    <input type="text" name="staff_id">

    <label for="staff_name">Staff Name:</label>
    <input type="text" name="staff_name">

    <input type="submit" value="Search Exhibitions">
  </form>

  <?php
// connect to database
$host = "localhost";
$username = "cs623";
$password = "Dbms@1234";
$dbname = "museum";
$conn = mysqli_connect($host, $username, $password, $dbname);

// handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // validate user input
  $staff_id = trim($_POST['staff_id']);
  $staff_name = trim($_POST['staff_name']);

  if (empty($staff_id) && empty($staff_name)) {
    echo "At least one field is required.";
    exit();
  }

  // prepare query to search for exhibitions based on staff id or name
  $query = "SELECT e.exhibition_id, e.name, e.start_date, e.end_date, e.description, s.name AS staff_name, s.job_title, s.department
            FROM exhibitions e
            JOIN staff s ON e.staff_id = s.staff_id";

  if (!empty($staff_id)) {
    $query .= " WHERE s.staff_id = ?";
    $param = $staff_id;
  }

  if (!empty($staff_name)) {
    $query .= " WHERE s.name LIKE ?";
    $param = "%$staff_name%";
  }

  // execute query
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, "s", $param);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  // display results
  echo "<h1>Exhibitions</h1>";
  if (mysqli_num_rows($result) == 0) {
    echo "No exhibitions found for the given staff member.";
  } else {
    echo "<table>";
    echo "<tr><th>Name</th><th>Start Date</th><th>End Date</th><th>Description</th><th>Staff Name</th><th>Job Title</th><th>Department</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<tr><td>" . $row['name'] . "</td><td>" . $row['start_date'] . "</td><td>" . $row['end_date'] . "</td><td>" . $row['description'] . "</td><td>" . $row['staff_name'] . "</td><td>" . $row['job_title'] . "</td><td>" . $row['department'] . "</td></tr>";
    }
    echo "</table>";
  }
}
?>
</body>
</html>
