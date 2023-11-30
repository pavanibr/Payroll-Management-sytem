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

// Function to add a new allowances
function addallowances($pdo,$id, $allowance,$description) {
    $sql = "INSERT INTO allowances (id,allowance,description) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id, $allowance,$description]);
}


// Function to delete an allowances
function deleteallowances($pdo, $id) {
    $sql = "DELETE FROM allowances WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
}

// Function to update an allowances
function updateallowances($pdo, $id,$description) {
    $sql = "UPDATE allowances SET description = :description WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    // Explicitly bind parameters
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);



    // Execute the statement and check for errors
    if (!$stmt->execute()) {
        // Handle error appropriately
        $errorInfo = $stmt->errorInfo();
        echo "Error updating allowances: " . $errorInfo[2];
    } else {
        echo "allowances description updated successfully.";
    }
}


// Check if the add form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    addallowances($pdo,$_POST['id'], $_POST['allowance'],$_POST['description']);
}

// Check if the delete form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    deleteallowances($pdo, $_POST['id']);
}

// Check if the update form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'], $_POST['id'], $_POST['description'])) {
    // Validate the inputs
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $description = $_POST['description']; // Get raw value

    // Sanitize the description manually
    $description = trim($description); // Remove whitespace
    $description = strip_tags($description); // Remove HTML tags

    // Check if the validation passed
    if ($id === false || $description === '') {
        // Handle validation failure appropriately
        echo "Invalid input.";
    } else {
        // Call the function to update the allowances
        updateallowances($pdo, $id, $description);
    }
}



// Retrieve allowancess from the database to display
$stmt = $pdo->query("SELECT * FROM allowances");
$allowances = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="style_employee.css">
    <meta charset="UTF-8">
    <title>allowances Management</title>
</head>
<body>
    <h2>Add allowances</h2>
    <form method="post">
        id:
        <br><input type="int" name="id">
        <br>
        allowance:<input type="text" name="allowance">
        description:<input type="text" name="description">
        <input type="submit" name="add" value="Add allowances">
    </form>
    <h2>update allowances</h2>
    <form method="post">
    id:<input type="number" name="id">

        description:<input type="text" name="description">
        <input type="submit" name="update" value="Update allowances">
    </form>

    <h2>allowancess</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>allowance</th>
                <th>description</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($allowances as $allowances): ?>
            <tr>
                <td><?= htmlspecialchars($allowances['id']) ?></td>
                <td><?= htmlspecialchars($allowances['allowance']) ?></td>
                <td><?= htmlspecialchars($allowances['description']) ?></td>
                
                <td>
                    <form method="post">
                        <input type="hidden" name="id" value="<?= $allowances['id'] ?>">
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
