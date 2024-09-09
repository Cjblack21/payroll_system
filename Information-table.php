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

function togglePassword() {
    return isset($_POST['show_password']) ? 'text' : 'password';
}

if(isset($_POST['submit'])){
    $fname = $_POST['fname']; 
    $mname = $_POST['mname']; 
    $lname = $_POST['lname']; 
    $address = $_POST['address']; 
    $user = $_POST['username']; 
    $pw = $_POST['password']; 
    $roles = $_POST['roles']; 
    $email = $_POST['email'];
    $hashed_pw = password_hash($pw, PASSWORD_DEFAULT);

    $added_by = isset($_SESSION['fname']) ? $_SESSION['fname'] : "Unknown";
    $added_on = date("Y-m-d H:i:s");
    $sql = "INSERT INTO registration (fname, mname, lname, address, username, password, email, roles, added_by, added_on)
            VALUES ('$fname', '$mname', '$lname', '$address', '$user', '$hashed_pw', '$email', '$roles', '$added_by', '$added_on')";

if ($con->query($sql) !== TRUE) {
    echo "Error: " . $sql . "<br>" . $con->error;
} else {
    $_SESSION['success_message'] = "Account added successfully!";
    // Redirect to success.php after successful insertion
    header("Location: success.php");
    exit();
    }
}

if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $delete_sql = "DELETE FROM registration WHERE id=$id";
    if ($con->query($delete_sql) === TRUE) {
        header("location: Information-table.php");
        echo "Record deleted successfully!";
    } else {
        echo "Error deleting record: " . $con->error;
    }
}

if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $edit_sql = "SELECT * FROM registration WHERE id=$id";
    $edit_result = $con->query($edit_sql);
    if ($edit_result->num_rows > 0) {
        $row = $edit_result->fetch_assoc();
        $edit_fname = $row['fname'];
        $edit_mname = $row['mname'];
        $edit_lname = $row['lname'];
        $edit_address = $row['address'];
        $edit_user = $row['username'];
        // No need to retrieve password for editing
        $edit_roles = $row['roles'];
        $edit_email = $row['email'];       
    }
}

if(isset($_POST['update'])){
    $id = $_POST['id'];
    $fname = $_POST['fname']; 
    $mname = $_POST['mname']; 
    $lname = $_POST['lname']; 
    $address = $_POST['address']; 
    $user = $_POST['username']; 
    // No need to update password if not changed
    $roles = $_POST['roles'];
    $email = $_POST['email'];

    $update_sql = "UPDATE registration SET fname='$fname', mname='$mname', lname='$lname', address='$address', roles = '$roles', username='$user' WHERE id=$id";

    if ($con->query($update_sql) === TRUE) {
        header("Location: Information-table.php");
        exit(); // Stop further execution
    } else {
        echo "Error updating record: " . $con->error;
    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <title> Accounts</title>
    <link rel="stylesheet" type="text/css" href="CSS/info1.css">
</head>
<body>
    <nav class="sidebar">
        <h1>Payroll M S</h1>
        <span class="weller">Welcome 
            <strong class="user-name"><?php echo htmlspecialchars(ucfirst($user)); ?></strong>
        </span>
        <nav class="nav-container">
            <ul>
            <nav class="nav-container">
          <?php  
          include('nav.php');
          ?>
        </nav>
            <p><b>This is a working progress</b></p>
            <p><b>© 2024 Dummy Test. All rights reserved.</b></p>
        </div>
            </ul>
        </nav>
    </nav>
    <div class="content">
        <div class="header">
            <h1>Personnel</h1>
        </div>
        <div class="container">
            <div class="form-container">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <h2><?php echo isset($_GET['edit']) ? 'Edit Personnel Info' : 'Add Personel'; ?></h2>
    <p>First Name:</p>
    <input class="input" type="text" name="fname" value="<?php echo isset($edit_fname) ? $edit_fname : ''; ?>" placeholder="First Name" required> <br>
    <p>Middle Name:</p>
    <input class="input" type="text" name="mname" value="<?php echo isset($edit_mname) ? $edit_mname : ''; ?>" placeholder="Middle Name" required> <br>
    <p>Last Name:</p>
    <input class="input" type="text" name="lname" value="<?php echo isset($edit_lname) ? $edit_lname : ''; ?>" placeholder="Last Name" required> <br>
    <p>Your Address:</p>
    <input class="input" type="text" name="address" value="<?php echo isset($edit_address) ? $edit_address : ''; ?>" placeholder="Address" required> <br>
    <p>Your Username:</p>
    <input class="input" type="text" name="username" value="<?php echo isset($edit_user) ? $edit_user : ''; ?>" placeholder="Username" required> <br>
    <p>Your Email:</p>
    <input class="input" type="email" name="email" value="<?php echo isset($edit_email) ? $edit_email : ''; ?>" placeholder="Email" required> <br>   
    <p>Password:</p>
    <input class="input" type="<?php echo togglePassword(); ?>" name="password" placeholder="Password" required>
<input type="checkbox" onclick="myFunction()"> Show Password
<br>
    <p>Type of Personnel:</p>
    <select class="input" name="roles" required>
        <option value="Employee"<?php if(isset($edit_roles) && $edit_roles == 'Employee') echo 'selected'; ?>>User</option>
        <option value="admin" <?php if(isset($edit_roles) && $edit_roles == 'admin') echo 'selected'; ?>>Admin</option>
    </select>
    <input type="hidden" name="id" value="<?php echo isset($id) ? $id : ''; ?>">
    <?php if(isset($_GET['edit'])): ?>
    <input class="button" type="submit" name="update" value="Update">
    <?php else: ?>
    <input class="button" type="submit" name="submit" value="Submit">
    <?php endif; ?>
</form>


            </div>
            <div class="table-container">
                <table class="table" border="5px solid">
                    <p class="title">Personnels</p>

                    
    <thead>
    <tr>
        <th>First Name</th>
        <th>Middle Name</th>
        <th>Last Name</th>
        <th>Address</th>
        <th>Username</th>
        <th>Email</th>
        <th>Password</th>
        <th>Roles</th>
        <th>Action</th>
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
            $email = $row['email'];
            $pw = $row['password'];
            $roles = $row['roles'];



            echo "<tr>";
            echo "<td>$fname</td>";
            echo "<td>$mname</td>";
            echo "<td>$lname</td>";
            echo "<td>$address</td>";
            echo "<td>$user</td>";
            echo "<td>$email</td>";
            echo "<td>########</td>";
            echo "<td>$roles</td>";
            echo "<td>
                    <a href='Information-table.php?edit=$id' style='color: #fff; background-color: #007bff; padding: 0px 19px; text-decoration: none; border-radius: 2px;'>Edit</a>
                    <br>
                    <a href='Information-table.php?delete=$id' style='color: #fff; background-color: #dc3545; padding: 0px 10px; text-decoration: none; border-radius: 2px;' onclick=\"return confirm('Are you sure you want to delete $fname?')\">Delete</a>
                  </td>";
            echo "</tr>";
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
