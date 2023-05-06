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
  $name = trim($_POST['name']);

  if (empty($staff_id) && empty($name)) {
    echo "Please enter a staff ID or name.";
    exit();
  }

  // prepare statement to retrieve staff member information from database
  $stmt = mysqli_prepare($conn, "SELECT * FROM staff WHERE staff_id = ? OR name = ?");
  mysqli_stmt_bind_param($stmt, "is", $staff_id, $name);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  // check if any staff members were found
  if (mysqli_num_rows($result) == 0) {
    echo "No staff members found.";
    exit();
  }

  // display search results in table
  echo "<table>";
  echo "<tr><th>ID</th><th>Name</th></tr>";
  while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $row['staff_id'] . "</td>";
    echo "<td>" . $row['name'] . "</td>";
    
    echo "</tr>";
  }
  echo "</table>";
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Search Staff</title>
</head>
<body>
  <h1>Search Staff</h1>
  <form method="post">
    <label for="staff_id">Staff ID:</label>
    <input type="number" name="staff_id"><br>

    <label for="name">Name:</label>
    <input type="text" name="name"><br>

    <input type="submit" value="Search">
  </form>
</body>
</html>


