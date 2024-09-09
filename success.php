<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success Message</title>
    <link rel="stylesheet" href="popup.css"> 
</head>
<body>
<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}
// THIS WILL Check if the success message session variable is set
if (isset($_SESSION['success_message'])) {
    // IT WILL Display the success message
    echo "<div class='container'>";
    echo "<h1>Account Added Successfully!</h1>";
    echo "<p class='message'>" . $_SESSION['success_message'] . "</p>";
    echo "<a href='Information-table.php' id='backLink'>Back to Information Table</a>";
    echo "</div>";

    // Unset the success message session variable to remove it
    unset($_SESSION['success_message']);
} else {
    // If the success message session variable is not set, redirect the user to the information table page
    header("Location: Information-table.php");
    exit();
}

?>
</body>
</html>
