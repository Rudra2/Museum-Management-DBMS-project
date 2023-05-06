<!DOCTYPE html>
<html>
<head>
  <title>Museum Exhibitions</title>
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
      echo "<td>" . $row["name"] . "</td>";
      echo "</tr>";
    }
    echo "</table>";
  } else {
    echo "No artifacts found for the selected exhibition.";
  }

  // Close the prepared statement
  mysqli_stmt_close($stmt);
}



// Display a form to allow the user to input the exhibition_id value
echo '<form method="POST" action="">';
echo '<label for="exhibition_id">Exhibition ID:</label>';
echo '<input type="number" id="exhibition_id" name="exhibition_id" required>';
echo '<button type="submit">Submit</button>';
echo '</form>';

// handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // validate user input
  $name = trim($_POST['name']);
  $description = trim($_POST['description']);
  $exhibition_id = trim($_POST['exhibition_id']);

  if (empty($name) || empty($description) || empty($exhibition_id)) {
    echo "All fields are required.";
    exit();
  }

  // prepare statement to insert new artifact into database
  $stmt = mysqli_prepare($conn, "INSERT INTO artifacts (name, description, exhibition_id) VALUES (?, ?, ?)");
  mysqli_stmt_bind_param($stmt, "ssi", $name, $description, $exhibition_id);
  mysqli_stmt_execute($stmt);


  // redirect to artifacts page
  header("Location: artifacts.php");
}

// handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
// validate user input
$name = trim($_POST['name']);
$description = trim($_POST['description']);
$exhibition_id = trim($_POST['exhibition_id']);

if (empty($name) || empty($description) || empty($exhibition_id)) {
echo "All fields are required.";
exit();
}

// prepare statement to insert new artifact into database
$stmt = mysqli_prepare($conn, "INSERT INTO artifacts (name, description, exhibition_id) VALUES (?, ?, ?)");
mysqli_stmt_bind_param($stmt, "ssi", $name, $description, $exhibition_id);
mysqli_stmt_execute($stmt);

// redirect to artifacts page
header("Location: artifacts.php");
}



// Display a form to allow the user to input artifact information
echo '<form method="POST" action="">';
echo '<label for="name">Name:</label>';
echo '<input type="text" id="name" name="name" required>';
echo '<label for="description">Description:</label>';
echo '<textarea id="description" name="description" required></textarea>';
echo '<label for="exhibition_id">Exhibition ID:</label>';
echo '<input type="number" id="exhibition_id" name="exhibition_id" required>';
echo '<button type="submit">Submit</button>';
echo '</form>';
?>
</body>
</html>