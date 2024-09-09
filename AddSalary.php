<?php
include('dbconnect.php');
date_default_timezone_set('Asia/Manila');
session_start();

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

$user = isset($_SESSION['fname']) ? $_SESSION['fname'] : "User";

// Retrieve registration_id from URL parameter
$registration_id = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deduction = $_POST['deduction'];
    $salary = $_POST['salary'];

    if ($registration_id > 0) {
        $sql = "INSERT INTO payroll (registration_id, deduction, salary, created_at) VALUES (?, ?, ?, NOW())";
        if ($stmt = $con->prepare($sql)) {
            $stmt->bind_param("idd", $registration_id, $deduction, $salary);
            if ($stmt->execute()) {
                echo "Record added successfully!";
                header("Location: AdminSalary.php"); // Redirect to the salary table page
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            echo "Error preparing statement: " . $con->error;
        }
    } else {
        echo "Invalid Registration ID.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Salary</title>
    <link rel="stylesheet" type="text/css" href="AddSalary.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var deductionInput = document.getElementById('deduction');
            var salaryInput = document.getElementById('salary');
            var totalInput = document.getElementById('total');

            function updateTotal() {
                var deduction = parseFloat(deductionInput.value) || 0;
                var salary = parseFloat(salaryInput.value) || 0;
                var total = salary - deduction;
                totalInput.value = total.toFixed(2);
            }

            deductionInput.addEventListener('input', updateTotal);
            salaryInput.addEventListener('input', updateTotal);
        });
    </script>
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
            <h1>Add Salary</h1>
        </div>
        <div class="container">
            <div class="form-container">
                <form action="AddSalary.php?edit=<?php echo $registration_id; ?>" method="POST">
                    <h2>Add Salary</h2>
                    <p>Salary:</p>
                    <input class="input" type="number" id="salary" name="salary" min="0" step="any" placeholder="Salary" required> <br>
                    <p>Deduction:</p>
                    <input class="input" type="number" id="deduction" name="deduction" min="0" step="any" placeholder="Deduction" required> <br>
                    <p>Total:</p>
                    <input class="input" type="text" id="total" name="total" placeholder="Total" readonly> <br>
                    <input class="button" type="submit" value="Submit">
                </form>
            </div>
        </div>
    </div>
    <div class="time" id="current-time">
        <p>Date & Time:</p> 
        <p><?php echo date("Y-m-d H:i:s"); ?></p>
    </div>
</body>
</html>
