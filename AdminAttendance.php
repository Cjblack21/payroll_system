<?php
include('dbconnect.php');
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance List</title>
    <link rel="stylesheet" href="AdminAttendance1.css">
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <h1 class="title">Payroll M S</h1>
            <span class="weller">Welcome
                <strong class="user-name">
                    <?php echo htmlspecialchars(ucfirst($_SESSION['fname'])); ?>
                </strong>
            </span>
            <nav class="nav-container">
            <?php include('nav.php'); ?>
            </nav>
            
            <div class="copyright">
                <p><b>This is a working progress</b></p>
                <p><b>© 2024 Dummy Test. All rights reserved.</b></p>
            </div>
        </div>
        
        <div class="content">
            <div class="header">
                <h1>Attendance Records</h1>
            </div>
            
            <br>
            <table class="attendance-table" border="5px solid">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Date</th>
                        <th>Time In </th>
                        <th>Time Out </th>
                        <th>Status </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch all accounts from the registration table
                    $accounts_sql = "SELECT * FROM registration";
                    $accounts_result = $con->query($accounts_sql);

                    if ($accounts_result->num_rows > 0) {
                        while ($account_row = $accounts_result->fetch_assoc()) {
                            $account_id = $account_row['registration_id'];
                            $account_fname = $account_row['fname'];
                            $account_email = $account_row['email'];
                            
                            // Fetch attendance records for the current account
                            $attendance_sql = "SELECT * FROM attendance WHERE registration_id = ?";
                            $attendance_stmt = $con->prepare($attendance_sql);
                            $attendance_stmt->bind_param("i", $account_id);
                            $attendance_stmt->execute();
                            $attendance_result = $attendance_stmt->get_result();

                            if ($attendance_result->num_rows > 0) {
                                while ($attendance_row = $attendance_result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $account_fname . "</td>"; 
                                    echo "<td>" . $account_email . "</td>"; 
                                    echo "<td>" . $attendance_row['date'] . "</td>"; 
                                    echo "<td>" . $attendance_row['time_in'] . "</td>"; 
                                    echo "<td>" . $attendance_row['time_out'] . "</td>"; 
                                    $status = ($attendance_row['time_out'] == null) ? 'N/A' : 'Completed';
                                    echo "<td>$status</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr>";
                                echo "<td>" . $account_fname . "</td>";
                                echo "<td>" . $account_email . "</td>";
                                echo "<td colspan='4'>N/A</td>";
                                echo "</tr>";
                            }
                        }
                    } else {
                        echo "<tr><td colspan='6'>No accounts found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
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
