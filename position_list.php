<nav class="sidebar">
  <!-- Other menu items... -->
  <a href="index.php">Home</a>
  <!-- Other menu items... -->
</nav>
<?php
// Database connection variables
$host = 'localhost'; // or your database host
$dbname = 'payroll';
$username = 'root';
$password = 'pavani19';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Insert position
if (isset($_POST['insert'])) {
  $department_id = $_POST['department_id'];
  $name = $_POST['name'];

  $sql = "INSERT INTO position (department_id, name) VALUES (?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("is", $department_id, $name);
  $stmt->execute();
}

// Update position
if (isset($_POST['update'])) {
  $id = $_POST['id'];
  $department_id = $_POST['department_id'];
  $name = $_POST['name'];

  $sql = "UPDATE position SET department_id = ?, name = ? WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("isi", $department_id, $name, $id);
  $stmt->execute();
}

// Delete position
if (isset($_POST['delete'])) {
  $id = $_POST['id'];

  $sql = "DELETE FROM position WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $id);
  $stmt->execute();
}

// Retrieve current positions
$sql = "SELECT * FROM position";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style_employee.css">
<title>Position List</title>
</head>
<body>

<h2>Add New Position</h2>
<form method="post" action="position_list.php">
  Department ID: <input type="text" name="department_id">
  Name: <input type="text" name="name">
  <input type="submit" name="insert" value="Add Position">
</form>

<h2>Current Positions</h2>
<table>
  <tr>
    <th>ID</th>
    <th>Department ID</th>
    <th>Name</th>
    <th>Action</th>
  </tr>
  <?php while($row = $result->fetch_assoc()): ?>
  <tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['department_id']; ?></td>
    <td><?php echo $row['name']; ?></td>
    <td>
      <form method="post" action="position_list.php">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <input type="submit" name="delete" value="Delete">
      </form>
    </td>
  </tr>
  <?php endwhile; ?>
</table>

</body>
</html>

<?php
$conn->close();
?>
