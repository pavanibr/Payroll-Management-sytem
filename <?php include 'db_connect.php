<?php include 'db_connect.php' ?>
<nav class="sidebar">
  <!-- Other menu items... -->
  <a href="index.php">Home</a>
  <!-- Other menu items... -->
</nav>

<?php
//Assuming you have a database connection set up already.
$host = 'localhost'; // or your database host
$dbname = 'payroll';
$username = 'root';
$password = 'pavani19';

// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Function to add a new employee
function addEmployee($pdo,$id, $firstname,$lastname, $position_id, $department_id,$salary) {
    $sql = "INSERT INTO employee (id,firstname,lastname, position_id, department_id,salary) VALUES (?, ?, ?, ?, ?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id, $firstname,$lastname, $position_id, $department_id,$salary]);
}


// Function to delete an employee
function deleteEmployee($pdo, $id) {
    $sql = "DELETE FROM employee WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
}

// Function to update an employee
function updateEmployee($pdo, $id, $salary) {
    $sql = "UPDATE employee SET salary = :salary WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    // Explicitly bind parameters
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':salary', $salary, PDO::PARAM_INT);

    // Execute the statement and check for errors
    if (!$stmt->execute()) {
        // Handle error appropriately
        $errorInfo = $stmt->errorInfo();
        echo "Error updating employee: " . $errorInfo[2];
    } else {
        echo "Employee salary updated successfully.";
    }
}


// Check if the add form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    addEmployee($pdo,$_POST['id'], $_POST['firstname'],$_POST['lastname'], $_POST['position_id'], $_POST['department_id'],$_POST['salary']);
}

// Check if the delete form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    deleteEmployee($pdo, $_POST['id']);
}

// Check if the update form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'], $_POST['id'], $_POST['salary'])) {
    // Validate the inputs
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $salary = filter_input(INPUT_POST, 'salary', FILTER_VALIDATE_FLOAT);

    // Check if the validation passed
    if ($id === false || $salary === false) {
        // Handle validation failure appropriately
        echo "Invalid input.";
    } else {
        // Call the function to update the employee
        updateEmployee($pdo, $id, $salary);
    }
}


// Retrieve employees from the database to display
$stmt = $pdo->query("SELECT * FROM employee");
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Management</title>
</head>
<body>
    <h2>Add Employee</h2>
    <form method="post">
        id:<input type="int" name="id">
        First Name: <input type="text" name="firstname">
        Last Name: <input type="text" name="lastname">
        Position: <input type="int" name="position_id">
        Department: <input type="int" name="department_id">
        Salary:<input type="int" name="salary">
        <input type="submit" name="add" value="Add Employee">
    </form>
    <h2>update Employee</h2>
    <form method="post">
        id:<input type="int" name="id">
        Salary:<input type="int" name="salary">
        <input type="submit" name="update" value="Update employee">
    </form>

    <h2>Employees</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Position_id</th>
                <th>Department_id</th>
                <th>Salary</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employees as $employee): ?>
            <tr>
                <td><?= htmlspecialchars($employee['id']) ?></td>
                <td><?= htmlspecialchars($employee['firstname']) ?></td>
                <td><?= htmlspecialchars($employee['lastname']) ?></td>
                <td><?= htmlspecialchars($employee['position_id']) ?></td>
                <td><?= htmlspecialchars($employee['department_id']) ?></td>
                <td><?= htmlspecialchars($employee['salary']) ?></td>
                
                <td>
                    <form method="post">
                        <input type="hidden" name="id" value="<?= $employee['id'] ?>">
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
