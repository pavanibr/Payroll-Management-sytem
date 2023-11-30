<nav class="sidebar">
  <!-- Other menu items... -->
  <a href="index.php">Home</a>
  <!-- Other menu items... -->
</nav>
<?php
include 'db_connect.php'; // Your database connection file

// Function to add an deduction to an employee
function addEmployeededuction($pdo, $employee_id, $deduction_id, $amount, $effective_date) {
    $sql = "INSERT INTO employee_deductions (employee_id, deduction_id, type, amount, effective_date) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$employee_id, $deduction_id, 1, $amount, $effective_date]); // Assuming 'type' is always 1 for this example
}

// Function to update an employee's deduction
function updateEmployeededuction($pdo, $id, $amount, $effective_date) {
    $sql = "UPDATE employee_deductions SET amount = ?, effective_date = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$amount, $effective_date, $id]);
}

// Function to delete an employee's deduction
function deleteEmployeededuction($pdo, $id) {
    $sql = "DELETE FROM employee_deductions WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
}

// Handling form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        // Call function to add deduction
        // You should validate and sanitize these values before use
        addEmployeededuction($pdo, $_POST['employee_id'], $_POST['deduction_id'], $_POST['amount'], $_POST['effective_date']);
    } elseif (isset($_POST['update'])) {
        // Call function to update deduction
        // You should validate and sanitize these values before use
        updateEmployeededuction($pdo, $_POST['id'], $_POST['amount'], $_POST['effective_date']);
    } elseif (isset($_POST['delete'])) {
        // Call function to delete deduction
        // You should validate and sanitize these values before use
        deleteEmployeededuction($pdo, $_POST['id']);
    }
}

// Fetch existing deductions to display in a form
$deductionsStmt = $pdo->query("SELECT * FROM deductions");
$deductions = $deductionsStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch existing employee deductions to display
$employeedeductionsStmt = $pdo->query("SELECT * FROM employee_deductions");
$employeedeductions = $employeedeductionsStmt->fetchAll(PDO::FETCH_ASSOC);



?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="style_employee.css">
    <meta charset="UTF-8">
    <title>Manage Employee deductions</title>
</head>
<body>
    <h2>Add Employee deduction</h2>
    <form method="post" action="employee_deduction.php">
        Employee ID:<input type="number" name="employee_id" required>
        deduction:
        <select name="deduction_id" required>
            <?php foreach ($deductions as $deduction): ?>
                <option value="<?= $deduction['id'] ?>"><?= htmlspecialchars($deduction['deduction']) ?></option>
            <?php endforeach; ?>
        </select>
        Amount:<input type="number" name="amount" required>
        Effective Date:<input type="date" name="effective_date" required>
        <input type="submit" name="add" value="Add deduction">
    </form>

    <h2>Employee deductions</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Employee ID</th>
                <th>deduction ID</th>
                <th>Amount</th>
                <th>Effective Date</th>
                <th>Date Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employeedeductions as $employeededuction): ?>
            <tr>
                <td><?= htmlspecialchars($employeededuction['id']) ?></td>
                <td><?= htmlspecialchars($employeededuction['employee_id']) ?></td>
                <td><?= htmlspecialchars($employeededuction['deduction_id']) ?></td>
                <td><?= htmlspecialchars($employeededuction['amount']) ?></td>
                <td><?= htmlspecialchars($employeededuction['effective_date']) ?></td>
                <td><?= htmlspecialchars($employeededuction['date_created']) ?></td>
                
                <td>
                    <form method="post" action="employee_deduction.php">
                        <input type="hidden" name="id" value="<?= $employeededuction['id'] ?>">
                        Amount:<input type="number" name="amount" value="<?= $employeededuction['amount'] ?>" required>
                        Effective Date:<input type="date" name="effective_date" value="<?= $employeededuction['effective_date'] ?>" required>
                        <input type="submit" name="update" value="Update deduction">
                        <input type="submit" name="delete" value="Delete deduction" onclick="return confirm('Are you sure?');">
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
