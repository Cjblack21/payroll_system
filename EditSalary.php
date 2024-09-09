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

$edit_deduction = $edit_salary = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deduction = $_POST['deduction'];
    $salary = $_POST['salary'];

    if ($registration_id > 0) {
        // Check if the record exists
        $check_sql = "SELECT * FROM payroll WHERE registration_id = ?";
        if ($check_stmt = $con->prepare($check_sql)) {
            $check_stmt->bind_param("i", $registration_id);
            $check_stmt->execute();
            $result = $check_stmt->get_result();
            
            if ($result->num_rows > 0) {
                // Record exists, update it
                $update_sql = "UPDATE payroll SET deduction = ?, salary = ?, updated_at = NOW() WHERE registration_id = ?";
                if ($update_stmt = $con->prepare($update_sql)) {
                    $update_stmt->bind_param("ddi", $deduction, $salary, $registration_id);
                    if ($update_stmt->execute()) {
                        header("Location: AdminSalary.php"); // Redirect to the salary table page
                        exit();
                    } else {
                        echo "Error updating record: " . $update_stmt->error;
                    }
                } else {
                    echo "Error preparing statement: " . $con->error;
                }
            } else {
                echo "No record found with Registration ID $registration_id.";
            }
        } else {
            echo "Error preparing statement: " . $con->error;
        }
    } else {
        echo "Invalid Registration ID.";
    }
} else if ($registration_id > 0) {
    // Fetch existing record for pre-filling the form
    $fetch_sql = "SELECT * FROM payroll WHERE registration_id = ?";
    if ($fetch_stmt = $con->prepare($fetch_sql)) {
        $fetch_stmt->bind_param("i", $registration_id);
        $fetch_stmt->execute();
        $fetch_result = $fetch_stmt->get_result();
        
        if ($fetch_result->num_rows > 0) {
            $row = $fetch_result->fetch_assoc();
            $edit_deduction = $row['deduction'];
            $edit_salary = $row['salary'];
        } else {
            echo "No record found with Registration ID $registration_id.";
        }
    } else {
        echo "Error preparing statement: " . $con->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Salary</title>
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
            <h1>Edit Salary</h1>
        </div>
        <div class="container">
            <div class="form-container">
                <form action="editsalary.php?edit=<?php echo $registration_id; ?>" method="POST">
                    <h2>Edit Salary</h2>
                    <p>Salary:</p>
                    <input class="input" type="number" id="salary" name="salary" min="0" step="any" value="<?php echo htmlspecialchars($edit_salary); ?>" placeholder="Salary" required> <br>
                    <p>Deduction:</p>
                    <input class="input" type="number" id="deduction" name="deduction" min="0" step="any" value="<?php echo htmlspecialchars($edit_deduction); ?>" placeholder="Deduction" required> <br>
                    <p>Total:</p>
                    <input class="input" type="text" id="total" name="total" placeholder="Total" readonly> <br>
                    <input class="button" type="submit" value="Update">
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
