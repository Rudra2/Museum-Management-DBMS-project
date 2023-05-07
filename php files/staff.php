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
    echo "<p class='error'>Please enter a staff ID or name.</p>";
    exit();
  }

  // prepare statement to retrieve staff member information from database
  $stmt = mysqli_prepare($conn, "SELECT * FROM staff WHERE staff_id = ? OR name LIKE CONCAT('%', ?, '%')");
  mysqli_stmt_bind_param($stmt, "is", $staff_id, $name);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  // check if any staff members were found
  if (mysqli_num_rows($result) == 0) {
    echo "<p>No staff members found.</p>";
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
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }

    h1 {
      text-align: center;
      margin-top: 50px;
    }

    form {
      width: 50%;
      margin: 50px auto;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    label {
      font-size: 18px;
      margin-top: 20px;
    }

    input[type='number'],
    input[type='text'] {
      font-size: 16px;
      padding: 8px;
      border-radius: 5px;
      border: 1px solid #ccc;
      width: 100%;
      margin-top: 10px;
    }

    input[type='submit'] {
      font-size: 18px;
      background-color: #4CAF50;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
      margin-top: 20px;
    }

    input[type='submit']:hover {
      background-color: #3e8e41;
    }

    table {
      width: 50%;
      margin: 50px auto;
      border-collapse: collapse;
    }

    th,
    td {
      padding: 10px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #4CAF50;
      color: white;
    }

    tr:hover {
      background-color: #f5f5f5;
    }

    .error {
      color: red;
      text-align: center;
      margin-top: 50px;
    }
  </style>
</head>
<body>
  <h1>Search Staff</h1>
  <form method="POST" action="">
    <label for="staff_id">Staff ID:</label>
    <input type="number" name="staff_id" id="staff_id" placeholder="Enter Staff ID">

<label for="name">Name:</label>
<input type="text" name="name" id="name" placeholder="Enter Name">

<input type="submit" value="Search">
</form>
</body>
</html>