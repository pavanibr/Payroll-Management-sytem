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

// Function to add a new deductions
function adddeductions($pdo,$id, $deduction,$description) {
    $sql = "INSERT INTO deductions (id,deduction,description) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id, $deduction,$description]);
}


// Function to delete an deductions
function deletedeductions($pdo, $id) {
    $sql = "DELETE FROM deductions WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
}

// Function to update an deductions
function updatedeductions($pdo, $id,$description) {
    $sql = "UPDATE deductions SET description = :description WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    // Explicitly bind parameters
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);



    // Execute the statement and check for errors
    if (!$stmt->execute()) {
        // Handle error appropriately
        $errorInfo = $stmt->errorInfo();
        echo "Error updating deductions: " . $errorInfo[2];
    } else {
        echo "deductions description updated successfully.";
    }
}


// Check if the add form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    adddeductions($pdo,$_POST['id'], $_POST['deduction'],$_POST['description']);
}

// Check if the delete form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    deletedeductions($pdo, $_POST['id']);
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
        // Call the function to update the deductions
        updatedeductions($pdo, $id, $description);
    }
}



// Retrieve deductionss from the database to display
$stmt = $pdo->query("SELECT * FROM deductions");
$deductions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="style_employee.css">
    <meta charset="UTF-8">
    <title>deductions Management</title>
</head>
<body>
    <h2>Add deductions</h2>
    <form method="post">
        id:<br><input type="int" name="id"><br>
        deduction:<input type="text" name="deduction">
        description:<input type="text" name="description">
        <input type="submit" name="add" value="Add deductions">
    </form>
    <h2>update deductions</h2>
    <form method="post">
    id:<input type="number" name="id">

        description:<input type="text" name="description">
        <input type="submit" name="update" value="Update deductions">
    </form>

    <h2>deductionss</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>deduction</th>
                <th>description</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($deductions as $deductions): ?>
            <tr>
                <td><?= htmlspecialchars($deductions['id']) ?></td>
                <td><?= htmlspecialchars($deductions['deduction']) ?></td>
                <td><?= htmlspecialchars($deductions['description']) ?></td>
                
                <td>
                    <form method="post">
                        <input type="hidden" name="id" value="<?= $deductions['id'] ?>">
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
