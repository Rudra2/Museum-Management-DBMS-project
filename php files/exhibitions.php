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

  if (empty($staff_id)) {
    echo "Please select a staff member.";
    exit();
  }

  // prepare statement to insert new exhibition into database
  $stmt = mysqli_prepare($conn, "SELECT e.exhibition_id, e.name, e.start_date, e.end_date, s.name AS staff_name, s.job_title, s.department
    FROM exhibitions e
    JOIN staff s ON e.staff_id = s.staff_id
    WHERE s.staff_id = ?");
  mysqli_stmt_bind_param($stmt, "i", $staff_id);
  mysqli_stmt_execute($stmt);

  // get result set
  $result = mysqli_stmt_get_result($stmt);

  // check if any rows were returned
  if (mysqli_num_rows($result) == 0) {
    echo "No exhibitions found for selected staff member.";
    exit();
  }

  // display exhibitions
  while ($row = mysqli_fetch_assoc($result)) {
    echo "Exhibition Name: " . $row['name'] . "<br>";
    echo "Start Date: " . $row['start_date'] . "<br>";
    echo "End Date: " . $row['end_date'] . "<br>";
    echo "Curator Name: " . $row['staff_name'] . "<br>";
    echo "Job Title: " . $row['job_title'] . "<br>";
    echo "Department: " . $row['department'] . "<br>";
    echo "<br>";
  }

  // free result set
  mysqli_free_result($result);

  // close statement and connection
  mysqli_stmt_close($stmt);
  mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Search Exhibitions</title>
</head>
<body>
  <h1>Search Exhibitions</h1>
  <form method="post">
    <label for="staff_id">Select a staff member:</label>
    <select name="staff_id">
      <?php
      // get staff members from database
      $stmt = mysqli_prepare($conn, "SELECT staff_id, name FROM staff");
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);

      // display staff members in dropdown
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value='" . $row['staff_id'] . "'>" . $row['name'] . "</option>";
      }

      // free result set and close statement
      mysqli_free_result($result);
      mysqli_stmt_close($stmt);
      ?>
    </select><br>

    <input type="submit" value="Search">
  </form>
</body>
</html>
