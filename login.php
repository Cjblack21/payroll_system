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
    <link rel="stylesheet" href="CSS/StyleLogin1.css">
    <style>
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>Payroll MS</h1>
            <p class="login-subtitle">Log in to your account</p>

            <!-- Display error message if login fails -->
            <?php if (!empty($error)) { ?>
                <p class="error-message"><?php echo $error; ?></p>
            <?php } ?>

            <!-- Login Form -->
            <form class="logs" action="" method="post">
                <div class="input-group">
                    <label for="login_email">ID or Email:</label>
                    <input type="text" id="login_email" name="login_email" required>
                </div>
                <div class="input-group">
                    <label for="password">Password:</label>
                    <div class="password-container">
                        <input type="password" id="password" name="password" required>
                        <span class="toggle-password" onclick="togglePassword()">👁</span>
                    </div>
                </div>
                <div class="options">
                    <label>
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                    <a href="#" class="forgot-password">Forgot password?</a>
                </div>
                <button class="login-btn" type="submit">Log In</button>
            </form>

            <!-- Display Current Time -->
            <div class="current-time"></div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var toggleIcon = document.querySelector(".toggle-password");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.textContent = "◡"; // Change to closed eye
            } else {
                passwordField.type = "password";
                toggleIcon.textContent = "👁"; // Change back to open eye
            }
        }

        // Update the current time dynamically
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
        setInterval(updateTime, 1000); // Update every second

        // Automatically hide the error message after 2 seconds
        window.onload = function() {
            setTimeout(function() {
                var errorMessage = document.querySelector('.error-message');
                if (errorMessage) {
                    errorMessage.style.display = 'none';
                }
            }, 2000); // 2000 milliseconds = 2 seconds
        };
    </script>
</body>
</html>
