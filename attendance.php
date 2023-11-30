<nav class="sidebar">
  <!-- Other menu items... -->
  <a href="index.php">Home</a>
  <!-- Other menu items... -->
</nav>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style_employee.css">
    <title>Attendance Management</title>
</head>
<body>

    <h2>Employee Check-In</h2>
    <form method="post" action="attendance.php">
        Employee ID: <input type="text" name="employee_id">
        <input type="submit" name="check_in" value="Check In">
    </form>

    <h2>Employee Check-Out</h2>
    <form method="post" action="attendance.php">
        Employee ID: <input type="text" name="employee_id">
        <input type="submit" name="check_out" value="Check Out">
    </form>

    <h2>Update Attendance Record</h2>
    <form method="post" action="attendance.php">
        Record ID: <input type="text" name="id">
        Employee ID: <input type="text" name="employee_id">
        Log Type (1 for Check-In, 2 for Check-Out): <input type="text" name="log_type">
        Date and Time (YYYY-MM-DD HH:MM:SS): <input type="text" name="datetime_log">
        <input type="submit" name="update" value="Update Record">
    </form>

    <h2>Delete Attendance Record</h2>
    <form method="post" action="attendance.php">
        Record ID: <input type="text" name="id">
        <input type="submit" name="delete" value="Delete Record">
    </form>

    <h2>Current Attendance Records</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Employee ID</th>
            <th>Log Type</th>
            <th>Date and Time</th>
        </tr>
        <?php
        $host = 'localhost'; // Database host
        $dbname = 'payroll'; // Database name
        $username = 'root'; // Database username
        $password = 'pavani19'; // Database password

        $conn = new mysqli($host, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        function sanitize($data) {
            return htmlspecialchars(stripslashes(trim($data)));
        }

        if (isset($_POST['check_in'])) {
            $employee_id = sanitize($_POST['employee_id']);
            $datetime_log = date('Y-m-d H:i:s');
            $stmt = $conn->prepare("INSERT INTO attendance (employee_id, log_type, datetime_log) VALUES (?, 1, ?)");
            $stmt->bind_param("is", $employee_id, $datetime_log);
            $stmt->execute();
            $stmt->close();
        }

        if (isset($_POST['check_out'])) {
            $employee_id = sanitize($_POST['employee_id']);
            $datetime_log = date('Y-m-d H:i:s');
            $stmt = $conn->prepare("INSERT INTO attendance (employee_id, log_type, datetime_log) VALUES (?, 2, ?)");
            $stmt->bind_param("is", $employee_id, $datetime_log);
            $stmt->execute();
            $stmt->close();
        }

        if (isset($_POST['update'])) {
            $id = sanitize($_POST['id']);
            $employee_id = sanitize($_POST['employee_id']);
            $log_type = sanitize($_POST['log_type']);
            $datetime_log = sanitize($_POST['datetime_log']);
            $stmt = $conn->prepare("UPDATE attendance SET employee_id=?, log_type=?, datetime_log=? WHERE id=?");
            $stmt->bind_param("iisi", $employee_id, $log_type, $datetime_log, $id);
            $stmt->execute();
            $stmt->close();
        }

        if (isset($_POST['delete'])) {
            $id = sanitize($_POST['id']);
            $stmt = $conn->prepare("DELETE FROM attendance WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }

        $result = $conn->query("SELECT * FROM attendance");

        while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['employee_id']; ?></td>
                <td><?php echo $row['log_type'] == 1 ? 'Check-In' : 'Check-Out'; ?></td>
                <td><?php echo $row['datetime_log']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <?php $conn->close(); ?>

</body>
</html>