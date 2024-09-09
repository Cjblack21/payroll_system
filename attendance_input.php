<?php
include('dbconnect.php');
session_start();

$attendance_message = '';
$time_in = '';
$time_out = '';
$show_time_in = false;
$show_time_out = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_id = isset($_POST['id_or_email']) ? intval($_POST['id_or_email']) : null;
    $input_email = filter_var($_POST['id_or_email'], FILTER_VALIDATE_EMAIL) ? $_POST['id_or_email'] : null;
    $current_time = date("H:i:s");

    if ($input_id) {
        $sql = "SELECT * FROM registration WHERE registration_id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $input_id);
    } elseif ($input_email) {
        $sql = "SELECT * FROM registration WHERE email = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $input_email);
    } else {
        $attendance_message = "Please enter a valid ID or email.";
    }

    if (isset($stmt)) {
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $registration_id = $user['registration_id'];
            
            $attendance_sql = "SELECT * FROM attendance WHERE registration_id = ? ORDER BY time_in DESC LIMIT 1";
            $attendance_stmt = $con->prepare($attendance_sql);
            $attendance_stmt->bind_param("i", $registration_id);
            $attendance_stmt->execute();
            $attendance_result = $attendance_stmt->get_result();
            
            if ($attendance_result->num_rows > 0) {
                $attendance = $attendance_result->fetch_assoc();
                $time_in = $attendance['time_in'];
                $time_out = $attendance['time_out'];

                if (!$time_in) {
                    $show_time_in = true;
                } elseif (!$time_out) {
                    $show_time_out = true;
                }
            } else {
                $show_time_in = true;
            }

            if ($show_time_in && $_POST['action'] == 'Time In') {
                $time_in_sql = "INSERT INTO attendance (registration_id, time_in) VALUES (?, ?)";
                $time_in_stmt = $con->prepare($time_in_sql);
                $time_in_stmt->bind_param("is", $registration_id, $current_time);
                $time_in_stmt->execute();
                $attendance_message = "Checked in successfully.";
            } elseif ($show_time_out && $_POST['action'] == 'Time Out') {
                $time_out_sql = "UPDATE attendance SET time_out = ? WHERE registration_id = ? AND time_out IS NULL";
                $time_out_stmt = $con->prepare($time_out_sql);
                $time_out_stmt->bind_param("si", $current_time, $registration_id);
                $time_out_stmt->execute();
                $attendance_message = "Checked out successfully.";
            }
        } else {
            $attendance_message = "No user found with this ID or email.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Login</title>
    <link rel="stylesheet" href="CSS/styles.css">
</head>
<body>
    <div class="container">
        <h1>Attendance Login</h1>
        <form method="post" action="">
            <label for="id_or_email">ID Number or Email:</label>
            <input type="text" name="id_or_email" id="id_or_email" required>
            <button type="submit" name="action" value="Time In" <?php echo !$show_time_in && $show_time_out ? 'disabled' : ''; ?>>Time In</button>
            <button type="submit" name="action" value="Time Out" <?php echo !$show_time_out ? 'disabled' : ''; ?>>Time Out</button>
        </form>
        <p><?php echo htmlspecialchars($attendance_message); ?></p>
        <?php if ($time_in || $time_out): ?>
            <p>Current Time In: <?php echo htmlspecialchars($time_in); ?></p>
            <p>Current Time Out: <?php echo htmlspecialchars($time_out); ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
