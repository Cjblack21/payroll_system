<?php
include('dbconnect.php');
session_start();


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
    <title>User Attendance</title>
</head>
<body>
<div class="container">
<link rel="stylesheet" type="text/css" href="information1111.css">
<a href="User-dashboard.php" class="special">Home </a> &nbsp;&nbsp;&nbsp;
<br>
<a href="UserMyprofile.php" class="special">Profile </a> &nbsp;&nbsp;&nbsp;
    <br>
    <a href="AttendanceUser.php" class="special">Attendance </a> &nbsp;&nbsp;&nbsp;
    <br>
    <a href="logout.php" class="special">Logout</a> &nbsp;&nbsp;&nbsp;
    <br>
</div>
    <header>
        <h1>Attendance</h1>
    </header>
    <main>
    <section class="about-section">
    <h2>This is a working progress!</h2>
</section>

    </main>
    <footer>
        <p>&copy; 2024 Dummy Test. All rights reserved.</p>
    </footer>
</body>
</html>