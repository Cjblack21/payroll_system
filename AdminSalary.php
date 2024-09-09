<?php
include('dbconnect.php');
date_default_timezone_set('Asia/Manila');
session_start();

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

$user = isset($_SESSION['fname']) ? $_SESSION['fname'] : "User";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Salary-Admin</title>
    <link rel="stylesheet" type="text/css" href="Adminsalary.css">
</head>
<body>
    <nav class="sidebar">
        <h1>Payroll M S</h1>
        <span class="weller">Welcome 
            <strong class="user-name"><?php echo htmlspecialchars(ucfirst($user)); ?></strong>
        </span>
        <nav class="nav-container">
            <?php include('nav.php'); ?>
        </nav>
        <div class="copyright">
            <p><b>This is a work in progress</b></p>
            <p><b>© 2024 Dummy Test. All rights reserved.</b></p>
        </div>
    </nav>
    <div class="content">
        <div class="header">
            <h1>Salary Table</h1>
        </div>
        <div class="container">
            <div class="table-container">
                <table class="table" border="1" width="100%">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>Last Name</th>
                            <th>Username</th>
                            <th>Roles</th>
                            <th>Email</th>
                            <th>Deduction</th>
                            <th>Salary</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Join registration and payroll tables
                        $sql = "SELECT r.registration_id, r.fname, r.mname, r.lname, r.username, r.roles, r.email, 
                                       COALESCE(p.deduction, 0) AS deduction, COALESCE(p.salary, 0) AS salary
                                FROM registration r
                                LEFT JOIN payroll p ON r.registration_id = p.registration_id";
                        $result = $con->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $id = $row['registration_id'];
                                $fname = $row['fname'];
                                $mname = $row['mname'];
                                $lname = $row['lname'];
                                $user = $row['username'];
                                $roles = $row['roles'];
                                $email = $row['email'];
                                $deduction = $row['deduction'];
                                $salary = $row['salary'];
                                $total = $salary - $deduction;

                                echo "<tr>";
                                echo "<td>$fname</td>";
                                echo "<td>$mname</td>";
                                echo "<td>$lname</td>";
                                echo "<td>$user</td>";
                                echo "<td>$roles</td>";
                                echo "<td>$email</td>";
                                echo "<td>-₱" . number_format($deduction, 2) . "</td>";
                                echo "<td>₱" . number_format($salary, 2) . "</td>";
                                echo "<td>₱" . number_format($total, 2) . "</td>";
                                echo "<td>";
                                if ($salary <= 0) {
                                    echo "<a href='AddSalary.php?edit=$id' style='color: #fff; background-color: #007bff; padding: 0px 25px; text-decoration: none; border-radius: 2px;'>Add Salary</a>";
                                } else {
                                    echo "<a href='EditSalary.php?edit=$id' style='color: #fff; background-color: green; padding: 0px 25px; text-decoration: none; border-radius: 2px;'>Edit Salary</a>";
                                }
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='10'>No records available</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="time" id="current-time">
        <p>Date & Time:</p> 
        <p><?php echo date("Y-m-d H:i:s"); ?></p>
    </div>
</body>
</html>
