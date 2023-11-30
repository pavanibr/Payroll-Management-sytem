<?php include 'db_connect.php' ?>
<nav class="sidebar">
  <!-- Other menu items... -->
  <a href="index.php">Home</a>
  <!-- Other menu items... -->
</nav>

<?php
//Assuming you have a database connection set up already.
// $host = 'localhost'; // or your database host
// $dbname = 'payroll';
// $username = 'root';
// $password = 'pavani19';

// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Function to add a new department
function adddepartment($pdo,$id, $name) {
    $sql = "INSERT INTO department(id,name) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id, $name]);
}


// Function to delete an department
function deletedepartment($pdo, $id) {
    $sql = "DELETE FROM department WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
}


// Check if the add form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    adddepartment($pdo,$_POST['id'], $_POST['name']);
}

// Check if the delete form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    deletedepartment($pdo, $_POST['id']);
}



// Retrieve departments from the database to display
$stmt = $pdo->query("SELECT * FROM department");
$department = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="style_employee.css">
    <meta charset="UTF-8">
    <title>Department Management</title>
</head>
<body>
    <h2>Add Department</h2>
    <form method="post">
        id:<br><input type="int" name="id">
        <br>Department: <input type="text" name="name">
        <input type="submit" name="add" value="Add Department">
    </form>

    <h2>Department</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Department</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($department as $department): ?>
            <tr>
                <td><?= htmlspecialchars($department['id']) ?></td>
                <td><?= htmlspecialchars($department['name']) ?></td>
                
                
                <td>
                    <form method="post">
                        <input type="hidden" name="id" value="<?= $department['id'] ?>">
                        <input type="submit" name="delete" value="Delete">
                    </form>
                    
                    <!-- Update form would be similar, potentially with a link to a separate update page -->
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
