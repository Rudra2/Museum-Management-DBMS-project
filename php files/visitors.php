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
  $visitor_id = trim($_POST['visitor_id']);
  $name = trim($_POST['name']);

  if (empty($visitor_id) && empty($name)) {
    echo "Please enter a visitor ID or name.";
    exit();
  }

  // prepare statement to retrieve visitor information from database
  $stmt = mysqli_prepare($conn, "SELECT * FROM visitors WHERE visitor_id = ? OR name = ?");
  mysqli_stmt_bind_param($stmt, "is", $visitor_id, $name);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  // check if any visitors were found
  if (mysqli_num_rows($result) == 0) {
    echo "No visitors found.";
    exit();
  }

  // display search results in table
  echo "<table>";
  echo "<tr><th>ID</th><th>Name</th>";
  while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $row['visitor_id'] . "</td>";
    echo "<td>" . $row['name'] . "</td>";
    echo "</tr>";
  }
  echo "</table>";
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Search Visitors</title>
</head>
<body>
  <h1>Search Visitors</h1>
  <form method="post">
    <label for="visitor_id">Visitor ID:</label>
    <input type="number" name="visitor_id"><br>

    <label for="name">Name:</label>
    <input type="text" name="name"><br>

    <input type="submit" value="Search">
  </form>
</body>
</html>
