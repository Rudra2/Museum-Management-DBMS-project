<!DOCTYPE html>
<html>
<head>
  <title>Search Ticket Sales</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f2f2f2;
    }

    h1 {
      text-align: center;
      color: #003366;
      margin-top: 50px;
    }

    form {
      margin: 50px auto;
      max-width: 500px;
      padding: 20px;
      border: 1px solid #ccc;
      background-color: #fff;
    }

    label {
      display: block;
      margin-bottom: 10px;
      color: #666;
      font-size: 18px;
    }

    input[type="number"] {
      width: 100%;
      padding: 12px 20px;
      margin: 8px 0;
      box-sizing: border-box;
      border: 2px solid #ccc;
      border-radius: 4px;
      font-size: 18px;
    }

    input[type="submit"] {
      background-color: #003366;
      color: #fff;
      font-size: 18px;
      border: none;
      padding: 12px 20px;
      border-radius: 4px;
      cursor: pointer;
    }

    input[type="submit"]:hover {
      background-color: #004080;
    }

    table {
      margin: 50px auto;
      border-collapse: collapse;
      width: 80%;
    }

    th, td {
      text-align: left;
      padding: 8px;
    }

    th {
      background-color: #003366;
      color: #fff;
    }

    tr:nth-child(even) {
      background-color: #f2f2f2;
    }
  </style>
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
      echo "<p>Please enter a ticket ID or visitor ID.</p>";
      exit();
    }

    // prepare statement to retrieve ticket sales information from database
    $stmt = mysqli_prepare($conn, "SELECT * FROM ticket_sales WHERE ticket_id = ? OR visitor_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $ticket_id, $visitor_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // check if any ticket sales were found
    if (mysqli_num_rows($result) == 0) {
      echo "<p>No ticket sales found.</p>";
      exit();
    }

// display ticket sales information in a table
echo "<table>";
echo "<tr><th>Ticket ID</th><th>Visitor ID</th><th>Date of Sale</th><th>Price</th></tr>";
while ($row = mysqli_fetch_assoc($result)) {
echo "<tr><td>" . $row["ticket_id"] . "</td><td>" . $row["visitor_id"] . "</td><td>" . $row["date_of_sale"] . "</td><td>" . $row["price"] . "</td></tr>";
}
echo "</table>";

// close statement and database connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
}
?>

</body>
</html>
