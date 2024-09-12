<?php
include('dbconnect.php');

session_start();

// This code will Check if the User/Admin is already logged in
if (isset($_SESSION['email'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: User-dashboard.php");
    }
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login_email = mysqli_real_escape_string($con, $_POST['login_email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    $sql = "SELECT * FROM registration WHERE email = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $login_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        if (password_verify($password, $hashed_password)) {
            // Update the attendance status to 'Online'
            $userId = $row['id'];
            $currentTime = date("Y-m-d H:i:s");
            $updateAttendanceQuery = "INSERT INTO attendance (user_id, time_in, status) VALUES (?, ?, 'Online')";
            $stmt = $con->prepare($updateAttendanceQuery);
            $stmt->bind_param("is", $userId, $currentTime);
            $stmt->execute();

            $_SESSION['email'] = $login_email;
            $_SESSION['fname'] = $row['fname'];
            $_SESSION['role'] = $row['roles'];

            if ($_SESSION['role'] === 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: User-dashboard.php");
            }
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "No user found with that email.";
    }
    $con->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Payroll System</title>
    <link rel="stylesheet" href="StyleLogin1.css">
</head>
<body align="center">
    <div class="box"></div>
    <br> 
    <br> 
    <br> 
    <br> 
    <br> 
    <br> 
    <br> 
    <br> 
    <br> 
    <body>
        <br>
        <br>
        <br>
        <br>
    <b class="name1">Payroll Management System</b> <br>
    <b class="name2">Log in to your account</b>
    <br>
    <div class="current-time"></div>
</div>

<script>
    function updateTime() {
        var currentTimeElement = document.querySelector(".current-time");
        var currentTime = new Date();
        var year = currentTime.getFullYear();
        var month = ('0' + (currentTime.getMonth() + 1)).slice(-2);
        var day = ('0' + currentTime.getDate()).slice(-2);
        var hours = currentTime.getHours();
        var minutes = ('0' + currentTime.getMinutes()).slice(-2);
        var seconds = ('0' + currentTime.getSeconds()).slice(-2);
        var ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12;
        var formattedTime = year + '-' + month + '-' + day + ' ' + hours + ':' + minutes + ':' + seconds + ' ' + ampm;
        currentTimeElement.innerHTML = "<p>Date & Time:</p> <p>" + formattedTime + "</p>";
    }
    
    updateTime(); // Call once to initialize immediately
    setInterval(updateTime, 1000);
</script>
    <br>
    <br>
    <br>

    <?php if(!empty($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
    <form class ="logs" action="" method="post">
        <label class="line" for="login_email">Email:</label><br>
        <input class="input" type="text" id="login_email" name="login_email" required><br><br>
        <label class="line" for="password">Password:</label><br>
        <input class="input" type="password" id="password" name="password" required> <br>
        <br><input type="checkbox" onclick="togglePassword()"> Show Password<br>
        <br>
        <button class="button" type="submit" class="login-button">Login</button>
    </form>
    <div class="login-links">
        
    </div>

    <div class="current-time"></div>
</div>

<script>
    function updateTime() {
        var currentTimeElement = document.querySelector(".current-time");
        var currentTime = new Date();
        var year = currentTime.getFullYear();
        var month = ('0' + (currentTime.getMonth() + 1)).slice(-2);
        var day = ('0' + currentTime.getDate()).slice(-2);
        var hours = currentTime.getHours();
        var minutes = ('0' + currentTime.getMinutes()).slice(-2);
        var seconds = ('0' + currentTime.getSeconds()).slice(-2);
        var ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12;
        var formattedTime = year + '-' + month + '-' + day + ' ' + hours + ':' + minutes + ':' + seconds + ' ' + ampm;
        currentTimeElement.innerHTML = "<p>Date & Time:</p> <p>" + formattedTime + "</p>";
    }
    
    updateTime(); // Call once to initialize immediately
    setInterval(updateTime, 1000);
</script>

<script>
    function togglePassword() {
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>

</body>
</html>
