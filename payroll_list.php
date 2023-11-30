<nav class="sidebar">
  <!-- Other menu items... -->
  <a href="index.php">Home</a>
  <!-- Other menu items... -->
</nav>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="style_employee.css">
    <meta charset="UTF-8">
    <title>Payroll List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        // Database connection
        $servername = "localhost";
        $username = "root"; // replace with your username
        $password = "pavani19"; // replace with your password
        $dbname = "payroll"; // replace with your database name

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "CALL GetEmployeeNetSalary()";

        // $result = $conn->query($sql);
        // // SQL query to fetch employee salary details
        // $sql = "SELECT e.id, e.firstname, e.lastname, e.salary, 
        //                SUM(a.amount) AS total_allowances, 
        //                SUM(d.amount) AS total_deductions
        //         FROM employee e
        //         LEFT JOIN employee_allowances a ON e.id = a.employee_id
        //         LEFT JOIN employee_deductions d ON e.id = d.employee_id
        //         GROUP BY e.id";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table><tr><th>Employee</th><th>Net Salary</th></tr>";
            // Process each row
            while($row = $result->fetch_assoc()) {
                $base_salary = $row["salary"];
                $total_allowances = $row["total_allowances"];
                $total_deductions = $row["total_deductions"];

                // Calculate total salary with allowances and deductions
                $gross_salary = $base_salary + $total_allowances - $total_deductions;

                // Apply tax
                $tax = 0;
                if ($gross_salary >= 600000) {
                    $tax = $gross_salary * 0.20; // 20% tax
                }

                // Calculate final salary
                $net_salary = $gross_salary - $tax;

                echo "<tr><td>" . $row["firstname"] . " " . $row["lastname"] . 
                     "</td><td>" . $net_salary . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "0 results";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>