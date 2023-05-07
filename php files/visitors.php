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
    echo "<p style='color: red; font-weight: bold;'>Please enter a visitor ID or name.</p>";
    exit();
  }

// prepare statement to retrieve visitor information and total amount spent from database
$stmt = mysqli_prepare($conn, "SELECT visitors.name, ticket_sales.ticket_type, SUM(ticket_sales.ticket_price) AS total_spent FROM visitors INNER JOIN ticket_sales ON visitors.visitor_id = ticket_sales.visitor_id WHERE visitors.visitor_id = ? OR visitors.name LIKE ? GROUP BY visitors.visitor_id, ticket_sales.ticket_type");
$name = "%$name%"; // add wildcards to search for partial name
mysqli_stmt_bind_param($stmt, "is", $visitor_id, $name);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

  // check if any visitors were found
  if (mysqli_num_rows($result) == 0) {
    echo "<p style='color: red; font-weight: bold;'>No visitors found.</p>";
    exit();
  }

  // display search results in table
  echo "<table style='border-collapse: collapse;'>";
  echo "<tr><th style='border: 1px solid black; padding: 10px;'>Name</th><th style='border: 1px solid black; padding: 10px;'>Ticket Type</th><th style='border: 1px solid black; padding: 10px;'>Total Spent</th>";
  while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td style='border: 1px solid black; padding: 10px;'>" . $row['name'] . "</td>";
    echo "<td style='border: 1px solid black; padding: 10px;'>" . $row['ticket_type'] . "</td>";
    echo "<td style='border: 1px solid black; padding: 10px;'>" . $row['total_spent'] . "</td>";
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
<body style='font-family: Arial, sans-serif;'>
  <h1 style='text-align: center; margin-bottom: 30px;'>Search Visitors</h1>
  <form method="post" style='display: flex; flex-direction: column; width: 400px; margin: 0 auto;'>
    <label for="visitor_id" style='margin-bottom: 10px;'>Visitor ID:</label>
    <input type="number" name="visitor_id" style='margin-bottom: 20px; padding: 5px;'>

    <label for="name" style='margin-bottom: 10px;'>Name:</label>
    <input type="text" name="name" style='margin-bottom: 20px; padding: 5px;'>

    <input type="submit" value="Search" style='background-color: #4CAF50; color: white; border: none; padding: 10px; cursor: pointer;'>

  </form>
</body>
</html>