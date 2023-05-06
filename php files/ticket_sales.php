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
  $ticket_id = trim($_POST['ticket_id']);
  $visitor_id = trim($_POST['visitor_id']);

  if (empty($ticket_id) && empty($visitor_id)) {
    echo "Please enter a ticket ID or visitor ID.";
    exit();
  }

  // prepare statement to retrieve ticket sales information from database
  $stmt = mysqli_prepare($conn, "SELECT * FROM ticket_sales WHERE ticket_id = ? OR visitor_id = ?");
  mysqli_stmt_bind_param($stmt, "ii", $ticket_id, $visitor_id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  // check if any ticket sales were found
  if (mysqli_num_rows($result) == 0) {
    echo "No ticket sales found.";
    exit();
  }

  // display search results in table
  echo "<table>";
  echo "<tr><th>Ticket ID</th><th>Visitor ID</th><th>Date</th><th>Quantity</th><th>Price</th></tr>";
  while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $row['ticket_id'] . "</td>";
    echo "<td>" . $row['visitor_id'] . "</td>";
    echo "<td>" . $row['date'] . "</td>";
    echo "<td>" . $row['quantity'] . "</td>";
    echo "<td>" . $row['price'] . "</td>";
    echo "</tr>";
  }
  echo "</table>";
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Search Ticket Sales</title>
</head>
<body>
  <h1>Search Ticket Sales</h1>
  <form method="post">
    <label for="ticket_id">Ticket ID:</label>
    <input type="number" name="ticket_id"><br>

    <label for="visitor_id">Visitor ID:</label>
    <input type="number" name="visitor_id"><br>

    <input type="submit" value="Search">
  </form>
</body>
</html>
