<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payroll Management System</title>
    <link rel="stylesheet" href="style1.css">
    <!-- Add your JavaScript file if needed -->
</head>
<body>
    <div class="sidebar">
        <?php
        // Menu items with their corresponding file names
        $menu_items = [
            'Home' => 'index.php',
            'Attendance' => 'attendance.php',
            'Payroll List' => 'payroll_list.php',
            'Employee List' => 'employee_list.php',
            'Department List' => 'department_list.php',
            'Position List' => 'position_list.php',
            'Allowance List' => 'allowance_list.php',
            'Deduction List' => 'deduction_list.php',
            // 'Users' => 'users.php'
        ];


        // echo '<ul>';
        // foreach ($menu_items as $name => $link) {
        //     if ($name == 'Attendance') {
        //         echo "<li><button onclick=\"window.location.href='{$link}'\">{$name}</button></li>";
        //     } else {
        //         echo "<li><a href='{$link}'>{$name}</a></li>";
        //     }
        // }
        // echo '</ul>';
        ?>
    </div>
    <div class="main-content">
        <header>
            <h1>Welcome back, Administrator!</h1>
        </header>
        <div class="dashboard">
            <p>Content related to payroll management system</p>
        </div>
    </div>
</body>

