<?php
include('dbconnect.php');
date_default_timezone_set('Asia/Manila');
session_start();

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

$user = isset($_SESSION['fname']) ? $_SESSION['fname'] : "User";

if(isset($_POST['submit'])){
    $deduction = $_POST['deduction']; 
    $salary = $_POST['salary'];

    $sql = "INSERT INTO payroll (deduction, salary) VALUES ('$deduction', '$salary')";

    if ($con->query($sql) !== TRUE) {
        echo "Error: " . $sql . "<br>" . $con->error;
    } else {
        $_SESSION['success_message'] = "Account added successfully!";
        // Redirect to success.php after successful insertion
        header("Location: success.php");
        exit();
    }
}

if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $edit_sql = "SELECT * FROM payroll WHERE id=$id";
    $edit_result = $con->query($edit_sql);
    if ($edit_result->num_rows > 0) {
        $row = $edit_result->fetch_assoc();
        $edit_deduction = $row['deduction'];   
        $edit_salary = $row['salary'];   
    }
}

if(isset($_POST['update'])){
    $id = $_POST['id'];
    $deduction = $_POST['deduction'];
    $salary = $_POST['salary'];

    $update_sql = "UPDATE payroll SET deduction = '$deduction', salary = '$salary' WHERE id=$id";

    if ($con->query($update_sql) === TRUE) {
        header("Location: AdminSalary.php");
        exit(); // Stop further execution
    } else {
        echo "Error updating record: " . $con->error;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title> Information Table</title>
    <link rel="stylesheet" type="text/css" href="AddSalary.css">
</head>
<body>
    <nav class="sidebar">
        <h1>Payroll M S</h1>
        <span class="weller">Welcome 
            <strong class="user-name"><?php echo htmlspecialchars(ucfirst($user)); ?></strong>
        </span>
        <nav class="nav-container">
            <ul>
                <li><a href="admin_dashboard.php" class="special">Dashboard</a></li>
                <li><a href="AdminMyprofile.php" class="special">Profile</a></li>
                <li><a href="Information-table.php" class="special">Accounts</a></li>
                <li><a href="AdminArchives.php" class="special">Logs</a></li>
                <li><a href="AdminSalary.php" class="special">Salary</a></li> 
                <li><a href="AdminAttendance.php" class="special">Attendance</a></li>
                <li><a href="logout.php" class="special">Logout</a></li>
                <div class="copyright">
            <p><b>This is a working progress</b></p>
            <p><b>© 2024 Dummy Test. All rights reserved.</b></p>
        </div>
            </ul>
        </nav>
    </nav>
    <div class="content">
        <div class="header">
            <h1>Admin Homepage!</h1>
        </div>
        <div class="container">
            <div class="form-container">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <h2><?php echo isset($_GET['edit']) ? 'Add Salary': ''; ?></h2>
                    <p>Salary:</p>
                    <input class="input" type="number" id="salary" name="salary" min="0" step="any" value="<?php echo isset($edit_salary) ? $edit_salary : ''; ?>" placeholder="Salary" required> <br>
                    <p>Deduction:</p>
                    <input class="input" type="number" id="deduction" name="deduction" min="0" step="any" value="<?php echo isset($edit_deduction) ? $edit_deduction : ''; ?>" placeholder="Deduction" required> <br>
                    <p>Total:</p>
                    <input class="input" type="text" id="total" name="total" value="<?php echo isset($edit_salary) && isset($edit_deduction) ? ($edit_salary - $edit_deduction) : ''; ?>" placeholder="Total" readonly> <br>
                    <br>
                    <input type="hidden" name="id" value="<?php echo isset($id) ? $id : ''; ?>">
                    <?php if(isset($_GET['edit'])): ?>
                    <input class="button" type="submit" name="update" value="Update">
                    <?php else: ?>
                    <input class="button" type="submit" name="submit" value="Submit">
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
    <div class="time" id="current-time">
        <p>Date & Time:</p> 
        <p><?php echo date("Y-m-d H:i:s"); ?></p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var deductionInput = document.getElementById('deduction');
            var salaryInput = document.getElementById('salary');
            var totalInput = document.getElementById('total');

            if (deductionInput && salaryInput && totalInput) {
                deductionInput.addEventListener('input', function() {
                    var deduction = parseFloat(this.value);
                    var salary = parseFloat(salaryInput.value);
                    var total = isNaN(deduction) || isNaN(salary) ? '' : salary - deduction;
                    totalInput.value = total;
                });

                salaryInput.addEventListener('input', function() {
                    var deduction = parseFloat(deductionInput.value);
                    var salary = parseFloat(this.value);
                    var total = isNaN(deduction) || isNaN(salary) ? '' : salary - deduction;
                    totalInput.value = total;
                });
            } else {
                console.error('One or more input fields not found.');
            }
        });
    </script>
</body>
</html>
