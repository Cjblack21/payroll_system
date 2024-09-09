<?php
include('dbconnect.php');
date_default_timezone_set('Asia/Manila');
session_start();
// This is the information table remember 1111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111
//11111111111111111111111111111111111111 for admin only 

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

$user = isset($_SESSION['fname']) ? $_SESSION['fname'] : "User";

?>

<!DOCTYPE html>
<html>
<head>
    <title> Logs</title>
    <link rel="stylesheet" type="text/css" href="AdminArchives.css">
</head>
<body>
    <nav class="sidebar">
        <h1>Payroll M S</h1>
        <span class="weller">Welcome 
            <strong class="user-name"><?php echo htmlspecialchars(ucfirst($user)); ?></strong>
        </span>
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
    </nav>
    <div class="content">
        <div class="header">
            <h1>Logs</h1>
        </div>
        <div class="container">
            <div class="form-container">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <h2>
</form>


            </div>
            <div class="table-container">
            <table class="table" border="5px solid">
                    

                    
    <thead>
    <tr>
        <th>First Name</th>
        <th>Middle Name</th>
        <th>Last Name</th>
        <th>Address</th>
        <th>Username</th>
        <th>Roles</th>
        <th>Email</th>
        <th>Added By</th>
        <th>Added On</th> 
    </tr>
</thead>
<tbody>
    <?php
    $sql = "SELECT * FROM registration";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $id = $row['registration_id'];
            $fname = $row['fname'];
            $mname = $row['mname'];
            $lname = $row['lname'];
            $address = $row['address'];
            $user = $row['username'];
            $roles = $row['roles'];
            $email = $row['email'];
            $added_by = $row['added_by']; 
            $added_on = date("Y-m-d h:i:s A", strtotime($row['added_on']));



            echo "<tr>";
            echo "<td>$fname</td>";
            echo "<td>$mname</td>";
            echo "<td>$lname</td>";
            echo "<td>$address</td>";
            echo "<td>$user</td>";
            echo "<td>$roles</td>";
            echo "<td>$email</td>";
            echo "<td>$added_by</td>"; 
            echo "<td>$added_on</td>"; 
            echo "<td>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr>No records available </td></tr>";
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
</div>

<script>
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
