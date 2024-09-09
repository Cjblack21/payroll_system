<?php
include('dbconnect.php');
date_default_timezone_set('Asia/Manila');
session_start();

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

$email = $_SESSION['email'];

$sql = "SELECT * FROM registration WHERE email='$email'";
$result = $con->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $fname = $row['fname'];
    $mname = $row['mname'];
    $lname = $row['lname'];
    $address = $row['address'];
    $user = $row['username'];
    $roles = $row['roles'];
    $password = $row['password'];
    $email = $row['email'];
} else {
    echo "User details not found.";
}

$con->close();
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <link rel="stylesheet" href="admin_dashboard2.css">
</head>
<body>
<div class="container">
    <div class="sidebar">
        <h1>Payroll M S</h1>
        <span class="weller">Welcome <strong class="user-name"><?php echo htmlspecialchars(ucfirst($fname)); ?></strong></span>
        <nav class="nav-container">
        <nav class="nav-container">
          <?php  
          include('nav.php');
          ?>
        </nav>
                <div class="copyright">
            <p><b>This is a working progress</b></p>
            <p><b>© 2024 Dummy Test. All rights reserved.</b></p>
        </div>
            </ul>
        </nav>
    </div>
    <div class="content">
        <div class="header">
            <h1>Profile</h1>
        </div>
        <info class="about-section">
            <p><strong>First Name:</strong> <?php echo $fname; ?></p>
            <p><strong>Middle Name:</strong> <?php echo $mname; ?></p>
            <p><strong>Last Name:</strong> <?php echo $lname; ?></p>
            <p><strong>Address:</strong> <?php echo $address; ?></p>
            <p><strong>Username:</strong> <?php echo $user; ?></p>
            <p><strong>Roles:</strong> <?php echo $roles; ?></p>
            <p><strong>Email:</strong> <?php echo $email; ?></p>
            <p><a href="#add a function here"><button>Password</button></a></p>

</info>
       
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
</div>
</body>
</html>
