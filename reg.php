
<?php
// This is the original reg.php for employee only 
// remember
include('dbconnect.php');

// Function to toggle password visibility
function togglePassword() {
    return isset($_POST['show_password']) ? 'text' : 'password';
}

// Insert functionality
if(isset($_POST['submit'])){
    $fname = $_POST['fname']; 
    $mname = $_POST['mname']; 
    $lname = $_POST['lname']; 
    $address = $_POST['address']; 
    $user = $_POST['username']; 
    $pw = $_POST['password']; 
    $roles = $_POST['roles']; // Fetch roles from dropdown
    $email = $_POST['email'];
    $hashed_pw = password_hash($pw, PASSWORD_DEFAULT);

    // Insert the data into the registration table
    $sql = "INSERT INTO registration (fname, mname, lname, address, username, password, email, roles)
            VALUES ('$fname', '$mname', '$lname', '$address', '$user', '$hashed_pw', '$email', '$roles')";
    if ($con->query($sql) !== TRUE) {
        echo "Error: " . $sql . "<br>" . $con->error;
    } else {
        echo "<p class= 'text-center' style='font-size: 24px;'>Information Added!!</p>";
    }
}

// Delete functionality
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $delete_sql = "DELETE FROM registration WHERE id=$id";
    if ($con->query($delete_sql) === TRUE) {
        header("location: reg.php");
        echo "Record deleted successfully!";
    } else {
        echo "Error deleting record: " . $con->error;
    }
}

// Edit functionality
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    // Get the record to be edited
    $edit_sql = "SELECT * FROM registration WHERE id=$id";
    $edit_result = $con->query($edit_sql);
    if ($edit_result->num_rows > 0) {
        $row = $edit_result->fetch_assoc();
        $edit_fname = $row['fname'];
        $edit_mname = $row['mname'];
        $edit_lname = $row['lname'];
        $edit_address = $row['address'];
        $edit_user = $row['username'];
        $edit_pw = $row['password'];
        $edit_roles = $row['roles'];
        $edit_email = $row['email'];       
    }
}

// Update functionality
if(isset($_POST['update'])){
    $id = $_POST['id'];
    $fname = $_POST['fname']; 
    $mname = $_POST['mname']; 
    $lname = $_POST['lname']; 
    $address = $_POST['address']; 
    $user = $_POST['username']; 
    $pw = $_POST['password'];
    $roles = $_POST['roles']; // Fetch roles from dropdown
    $email = $_POST['email'];
    echo "Unable to update Email"; 
    $hashed_pw = password_hash($pw, PASSWORD_DEFAULT);

    // Update the data in the registration table
    $update_sql = "UPDATE registration SET fname='$fname', mname='$mname', lname='$lname', address='$address', roles = '$roles', username='$user', password='$hashed_pw' WHERE id=$id";

    if ($con->query($update_sql) === TRUE) {
        header("Location: reg.php");
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $con->error;
    }
}

?>
<html>
<head>
    <title>Registration form</title>
    <link rel="stylesheet" href="StylesReg.css">
    <style>
    </style>
</head>
<body align='center'>

    <div class="container">
        <div class="box" align='center'></div>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST"><br>
            <p class="special">Payroll Registration Form</p>
            <p>First name: </p>
            <input class="input" type="text" name="fname" placeholder="First Name" value="<?php echo isset($edit_fname) ? $edit_fname : ''; ?>" required> <br>
            <p>Middle name:</p>
            <input class="input" type="text" name="mname" placeholder="Middle Name" value="<?php echo isset($edit_mname) ? $edit_mname : ''; ?>" required> <br>
            
            <p>Last name:</p>
            <input class="input" type="text" name="lname" placeholder="Last Name" value="<?php echo isset($edit_lname) ? $edit_lname : ''; ?>" required> <br>
            
            <p>Your address:</p>
            <input class="input" type="text" name="address" placeholder="Address" value="<?php echo isset($edit_address) ? $edit_address : ''; ?>" required> <br>
            
            <p>Your username:</p>
            <input class="input" type="text" name="username" placeholder="Username" value="<?php echo isset($edit_user) ? $edit_user : ''; ?>" required> <br>
            
            <p>Employee type:</p>
            <select class="input" name="roles" required>
                <option value="user" <?php if(isset($edit_roles) && $edit_roles == 'user') echo 'selected'; ?>>User</option>
                <option value="admin" <?php if(isset($edit_roles) && $edit_roles == 'admin') echo 'selected'; ?>>Admin</option>
            </select>
            
            <p>Your email:</p>
            <input class="input" type="email" name="email" placeholder="Email" value="<?php echo isset($edit_pw) ? $edit_email : ''; ?>" required> <br>   
            
            <p>Password:</p>
            <input class="input" type="<?php echo togglePassword(); ?>" name="password" id="myInput" placeholder="Password" value="<?php echo isset($edit_pw) ? $edit_pw : ''; ?>" required> <br>
            <input type="checkbox" onclick="myFunction()"> Show Password <br> <!-- Toggle password visibility -->
            <br>
            <?php if(isset($_GET['edit'])): ?>
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input class="button" type="submit" name="update" value="Update">
            <?php else: ?>
            <input class="button" type="submit" name="submit" value="Submit">
            <?php endif; ?>
            <p>Already have an account? <a href="login.php" class="log">Login Here!</a></p>
        </form>
    </div>
    <br>

    
<script>
    function myFunction() {
        var x = document.getElementById("myInput");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>
</body>
</html>
