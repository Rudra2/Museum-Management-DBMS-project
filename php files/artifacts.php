<!DOCTYPE html>
<html>
<head>
  <title>Museum Exhibitions</title>
  <style>
    body {
      font-family: Arial, sans-serif;
    }
    h1 {
      color: #007bff;
    }
    table {
      border-collapse: collapse;
      width: 100%;
    }
    th, td {
      text-align: left;
      padding: 8px;
    }
    th {
      background-color: #007bff;
      color: white;
    }
    tr:nth-child(even) {
      background-color: #f2f2f2;
    }
    form {
      margin-top: 20px;
    }
    label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }
    input, textarea {
      width: 100%;
      padding: 8px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }
    button[type="submit"] {
      background-color: #007bff;
      color: white;
      padding: 8px 16px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    button[type="submit"]:hover {
      background-color: #0062cc;
    }
    .error {
      color: red;
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <h1>Current Exhibitions</h1>
  <?php
  // connect to database
  $host = "localhost";
  $username = "cs623";
  $password = "Dbms@1234";
  $dbname = "museum";
  $conn = mysqli_connect($host, $username, $password, $dbname);

  // Check if the form has been submitted
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the exhibition_id value from the form
    $exhibition_id = $_POST["exhibition_id"];

    // Prepare the SQL statement with a parameterized query
    $stmt = mysqli_prepare($conn, "SELECT artifacts.*, exhibitions.name
                                   FROM artifacts
                                   INNER JOIN exhibitions
                                   ON artifacts.exhibition_id = exhibitions.exhibition_id
                                   WHERE artifacts.exhibition_id = ?");

    // Bind the exhibition_id parameter to the prepared statement
    mysqli_stmt_bind_param($stmt, "i", $exhibition_id);

    // Execute the prepared statement
    mysqli_stmt_execute($stmt);

    // Get the result set
    $result = mysqli_stmt_get_result($stmt);

    // Display the results in a table
    if (mysqli_num_rows($result) > 0) {
      echo "<table>";
      echo "<tr><th>Artifact ID</th><th>Name</th><th>Description</th><th>Category</th><th>Acquisition Date</th><th>Donor Name</th><th>Exhibition Name</th></tr>";
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["artifact_id"] . "</td>";
        echo "<td>" . $row["name"] . "</td>";
        echo "<td>" . $row["description"] . "</td>";
        echo "<td>" . $row["category"] . "</td>";
        echo "<td>" . $row["acquisition_date"] . "</td>";
        echo "<td>" . $row["donor_name"] . "</td>";
		echo "</tr>";
      }
      echo "</table>";
    } else {
      echo "<p>No artifacts found in this exhibition.</p>";
    }
// Close the statement
mysqli_stmt_close($stmt);

// Close the connection
mysqli_close($conn);
}
?>

  <form method="post">
    <label for="exhibition_id">Enter exhibition ID:</label>
    <input type="number" id="exhibition_id" name="exhibition_id" required>
    <button type="submit">Submit</button>
  </form>
</body>
</html>