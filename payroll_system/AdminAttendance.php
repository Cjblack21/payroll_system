<?php
include('dbconnect.php');
session_start();

// This function Remember James This will Redirect to login page if user is not logged in (it will not take you back to the log in page unless you log in)
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
                <ul>
                    <li><a href="admin_dashboard.php" class="special">Dashboard</a></li>
                    <li><a href="AdminMyprofile.php" class="special">Profile</a></li>
                    <li><a href="Information-table.php" class="special">Accounts</a></li>
                    <li><a href="AdminArchives.php" class="special">Logs</a></li>
                    <li><a href="AdminSalary.php" class="special">Salary</a></li> 
                    <li><a href="AdminAttendance.php" class="special">Attendance</a></li>
                    <li><a href="logout.php" class="special">Logout</a></li>
                </ul>
            </nav>
            
            <div class="copyright">
                <p><b>This is a working progress</b></p>
                <p><b>© 2024 Dummy Test. All rights reserved.</b></p>
            </div>
        </div>
        
        <div class="content">
        <div class="header">
            <h1>Attendance</h1>
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
                    // Fetch or Get all accounts from the registration table
                    $accounts_sql = "SELECT * FROM registration";
                    $accounts_result = $con->query($accounts_sql);

                    // Check if accounts are fetched/Get successfully
                    if ($accounts_result->num_rows > 0) {
                        while ($account_row = $accounts_result->fetch_assoc()) {
                            $account_id = $account_row['id'];
                            $account_fname = $account_row['fname'];
                            $account_email = $account_row['email'];
                            
                            // Fetch or Get attendance records for the current account
                            $attendance_sql = "SELECT * FROM attendance WHERE user_id = $account_id";
                            $attendance_result = $con->query($attendance_sql);

                          
                            if ($attendance_result->num_rows > 0) {
                                while ($attendance_row = $attendance_result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $account_fname . "</td>"; 
                                    echo "<td>" . $account_email . "</td>"; 
                                    echo "<td>" . $attendance_row['date'] . "</td>"; 
                                    echo "<td>" . $attendance_row['time_in'] . "</td>"; 
                                    echo "<td>" . $attendance_row['time_out'] . "</td>"; 
                                    
                                   
                                    $status = ($attendance_row['time_out'] == null) ? 'N/A' : 'N/A';
                                    echo "<td>$status</td>";
                                    echo "</tr>";
                                }
                            } else {
                                // If no attendance records found for the account, display 'N/A' in the table
                                echo "<tr>";
                                echo "<td>" . $account_fname . "</td>"; // Display account's name
                                echo "<td>" . $account_email . "</td>"; // Display account's email
                                echo "<td colspan='4'>N/A</td>"; // Display 'N/A' for date, time in, time out, and status
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

    function myFunction() {
        var x = document.getElementsByName("password")[0];
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>
</body>
</html>
