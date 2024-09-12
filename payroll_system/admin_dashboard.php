<?php
include('dbconnect.php');
date_default_timezone_set('Asia/Manila');
session_start(); 

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

// Get user info
$user = isset($_SESSION['fname']) ? $_SESSION['fname'] : "User";

// Count the total number of accounts
$count_query = "SELECT COUNT(*) AS total FROM registration";
$count_result = $con->query($count_query);
$count_row = $count_result->fetch_assoc();
$total_accounts = $count_row['total'];

// Calculate total salary
$total_salary_query = "SELECT SUM(salary) AS total_salary FROM payroll";
$total_salary_result = $con->query($total_salary_query);
$total_salary_row = $total_salary_result->fetch_assoc();
$total_salary = $total_salary_row['total_salary'];

// Count total attendance
$total_attendance_query = "SELECT COUNT(*) AS total_attendance FROM attendance";
$total_attendance_result = $con->query($total_attendance_query);
$total_attendance_row = $total_attendance_result->fetch_assoc();
$total_attendance = $total_attendance_row['total_attendance'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Homepage</title>
    <link rel="stylesheet" href="admin_dashboard.css">
</head>
<body>
<div class="container">
    <div class="sidebar">
        <h1 class="title">Payroll M S</h1>
        <span class="weller">Welcome
            <strong class="user-name">
                <?php echo htmlspecialchars(ucfirst($user)); ?>
            </strong>
        </span>

        <nav class="nav-container">
          <?php  
          include('nav.php');
          ?>
        </nav>

        <div class="copyright">
            <p><b>This is a working progress</b></p>
            <p><b>© 2024 Dummy Test. All rights reserved.</b></p>
        </div>
    </div>
    <!-- Add a symbol home right here -->  <!-- Burger symbol to minimize  -->
    <div class="content">
        <div class="header">
            <input class="search" type="text" placeholder="Search...">
        </div>
        <div class="content1"> 
            
            <!-- Total Accounts box -->
            <div class="box1">
                <h2>Total Personnel</h2>
                <?php echo "<b>Total Accounts: $total_accounts</b>"; ?>
                <a href="Information-table.php">
                    <br>
                    <br>
                    <button>Show Accounts</button>
                </a>
            </div>

            <!-- Total Salary box -->
            <div class="box2">
                <h3>Total Salary</h3>
                <?php echo "<b>₱" . number_format($total_salary, 2) . "</b>"; ?>
                <a href="AdminSalary.php">
                    <br>
                    <br>
                    <button>Show Salary Details</button>
                </a>
            </div>

            <!-- Total Attendance box -->
            <div class="box3">
                <h4>Total Attendance</h4>
                <?php echo "<b>Total Attendance: $total_attendance</b>"; ?>
                <a href="AdminAttendance.php">
                    <br>
                    <br>
                    <button>Show Attendance Records</button>
                </a>
            </div>

        </div>
    </div>
</div>
</body>
</html>
