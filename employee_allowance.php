<nav class="sidebar">
  <!-- Other menu items... -->
  <a href="index.php">Home</a>
  <!-- Other menu items... -->
</nav>
<?php
include 'db_connect.php'; // Your database connection file

// Function to add an allowance to an employee
function addEmployeeAllowance($pdo, $employee_id, $allowance_id, $amount, $effective_date) {
    $sql = "INSERT INTO employee_allowances (employee_id, allowance_id, type, amount, effective_date) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$employee_id, $allowance_id, 1, $amount, $effective_date]); // Assuming 'type' is always 1 for this example
}

// Function to update an employee's allowance
function updateEmployeeAllowance($pdo, $id, $amount, $effective_date) {
    $sql = "UPDATE employee_allowances SET amount = ?, effective_date = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$amount, $effective_date, $id]);
}

// Function to delete an employee's allowance
function deleteEmployeeAllowance($pdo, $id) {
    $sql = "DELETE FROM employee_allowances WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
}

// Handling form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        // Call function to add allowance
        // You should validate and sanitize these values before use
        addEmployeeAllowance($pdo, $_POST['employee_id'], $_POST['allowance_id'], $_POST['amount'], $_POST['effective_date']);
    } elseif (isset($_POST['update'])) {
        // Call function to update allowance
        // You should validate and sanitize these values before use
        updateEmployeeAllowance($pdo, $_POST['id'], $_POST['amount'], $_POST['effective_date']);
    } elseif (isset($_POST['delete'])) {
        // Call function to delete allowance
        // You should validate and sanitize these values before use
        deleteEmployeeAllowance($pdo, $_POST['id']);
    }
}

// Fetch existing allowances to display in a form
$allowancesStmt = $pdo->query("SELECT * FROM allowances");
$allowances = $allowancesStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch existing employee allowances to display
$employeeAllowancesStmt = $pdo->query("SELECT * FROM employee_allowances");
$employeeAllowances = $employeeAllowancesStmt->fetchAll(PDO::FETCH_ASSOC);



?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="style_employee.css">
    <meta charset="UTF-8">
    <title>Manage Employee Allowances</title>
</head>
<body>
    <h2>Add Employee Allowance</h2>
    <form method="post" action="employee_allowance.php">
        Employee ID:<input type="number" name="employee_id" required>
        Allowance:
        <select name="allowance_id" required>
            <?php foreach ($allowances as $allowance): ?>
                <option value="<?= $allowance['id'] ?>"><?= htmlspecialchars($allowance['allowance']) ?></option>
            <?php endforeach; ?>
        </select>
        Amount:<input type="number" name="amount" required>
        Effective Date:<input type="date" name="effective_date" required>
        <input type="submit" name="add" value="Add Allowance">
    </form>

    <h2>Employee Allowances</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Employee ID</th>
                <th>Allowance ID</th>
                <th>Amount</th>
                <th>Effective Date</th>
                <th>Date Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employeeAllowances as $employeeAllowance): ?>
            <tr>
                <td><?= htmlspecialchars($employeeAllowance['id']) ?></td>
                <td><?= htmlspecialchars($employeeAllowance['employee_id']) ?></td>
                <td><?= htmlspecialchars($employeeAllowance['allowance_id']) ?></td>
                <td><?= htmlspecialchars($employeeAllowance['amount']) ?></td>
                <td><?= htmlspecialchars($employeeAllowance['effective_date']) ?></td>
                <td><?= htmlspecialchars($employeeAllowance['date_created']) ?></td>
                <td>
                    <form method="post" action="employee_allowance.php">
                        <input type="hidden" name="id" value="<?= $employeeAllowance['id'] ?>">
                        Amount:<input type="number" name="amount" value="<?= $employeeAllowance['amount'] ?>" required>
                        Effective Date:<input type="date" name="effective_date" value="<?= $employeeAllowance['effective_date'] ?>" required>
                        <input type="submit" name="update" value="Update Allowance">
                        <input type="submit" name="delete" value="Delete Allowance" onclick="return confirm('Are you sure?');">
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
