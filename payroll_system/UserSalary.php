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
    <title> Information Table</title>
    <link rel="stylesheet" type="text/css" href="adminsalary.css">
    <script>
        function checkSalary(salary) {
            if (salary > 0) {
                alert("This account already has a salary.");
                return false; // Prevent default action
            }
        }
    </script>
</head>
<body>
    <nav class="sidebar">
        <h1>Payroll M S</h1>
        <span class="weller">Welcome 
            <strong class="user-name"><?php echo htmlspecialchars(ucfirst($user)); ?></strong>
        </span>
        <nav class="nav-container">
            <ul>
                <li><a href="AdminMyprofile.php" class="special">Profile</a></li>
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
            <!-- Add User form will go here -->
            </div>
            <div class="table-container">
                <table class="table" border="5px solid" width="100%">
                    <p class="title">Salary table </p>
                    
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $email = $_SESSION['email']; // Get the logged-in user's email

                        $sql = "SELECT * FROM registration WHERE email = '$email'";
                        $result = $con->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $id = $row['id'];
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
                                echo "<td>-$" . number_format($deduction) . "</td>";
                                echo "<td>$" . number_format($salary) . "</td>";
                                echo "<td>$". number_format($total) . "</td>";
                            }
                        } else {
                            echo "<tr><td colspan='10'>No records available </td></tr>";
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

    <script>
        function updateTime() {
            var currentTimeElement = document.getElementById("current-time");
            var currentTime = new Date();
            var hours = currentTime.getHours();
            var ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12;
            var formattedTime = ('0' + hours).slice(-2) + ":" + 
                                ('0' + currentTime.getMinutes()).slice(-2) + ":" + 
                                ('0' + currentTime.getSeconds()).slice(-2) + " " + ampm;
            currentTimeElement.innerHTML = "<p>Date & Time:</p> <p>" + formattedTime + "</p>";
        }
        
        setInterval(updateTime, 1000);
    </script>
</body>
</html>
