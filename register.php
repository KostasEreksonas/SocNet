<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "social");
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_errno();
}

// Declare variables to prevent errors
$fname = ""; // First Name
$lname = ""; // Last name
$email = ""; // Email
$email2 = ""; // Confirm Email
$password = ""; // Password
$password2 = ""; // Confirm Password
$date = ""; // Signup date
$error_array = array(); // Holds error message

if (isset($_POST['register_button'])) {
    // Registration form values

    // First Name
    $fname = strip_tags($_POST['reg_fname']); // Remove html tags
    $fname = str_replace(" ", "", $fname); // Remove spaces
    $fname = ucfirst(strtolower($fname)); // Uppercase first letter
    $_SESSION['reg_fname'] = $fname; // Stores first name into session variable

    // Last Name
    $lname = strip_tags($_POST['reg_lname']); // Remove html tags
    $lname = str_replace(" ", "", $lname); // Remove spaces
    $lname = ucfirst(strtolower($lname)); // Uppercase first letter
    $_SESSION['reg_lname'] = $lname; // Stores last name into session variable

    // Email
    $email = strip_tags($_POST['reg_email']); // Remove html tags
    $email = str_replace(" ", "", $email); // Remove spaces
    $email = ucfirst(strtolower($email)); // Uppercase first letter
    $_SESSION['reg_email'] = $email; // Store email into session variable

    // Email 2
    $email2 = strip_tags($_POST['reg_email2']); // Remove html tags
    $email2 = str_replace(" ", "", $email2); // Remove spaces
    $email2 = ucfirst(strtolower($email2)); // Uppercase first letter
    $_SESSION['reg_email2'] = $email2; // Store repeated email into session variable

    // Password
    $password = strip_tags($_POST['reg_password']); // Remove html tags

    // Password 2
    $password2 = strip_tags($_POST['reg_password2']); // Remove html tags

    // Date
    $date = date("Y-m-d"); // Current date

    if ($email == $email2) {
        // Check if email is in valid format
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $email = filter_var($email, FILTER_VALIDATE_EMAIL);

            // Check if email already exists
            $email_check = mysqli_query($con, "SELECT email FROM users WHERE email='$email'");

            // Count the number of rows returned
            $num_rows = mysqli_num_rows($email_check);

            if ($num_rows > 0) {
                array_push($error_array, "Email already in use<br>");
            }
        } else {
            array_push($error_array, "Please enter a valid email address<br>");
        }
    } else {
        array_push($error_array, "Emails do not match<br>");
    }

    if (strlen($fname) > 25 || strlen($fname) < 2) {
        array_push($error_array, "First name must be between 2 and 25 characters<br>");
    }

    if (strlen($lname) > 25 || strlen($lname) < 2) {
        array_push($error_array, "Last name must be between 2 and 25 characters<br>");
    }

    if ($password != $password2) {
        array_push($error_array, "Passwords do not match<br>");
    } else {
        if (preg_match('/[^A-Za-z0-9]/', $password)) {
            array_push($error_array, "Password must contain only english letters and numbers<br>");
        }
    }

    if (strlen($password) > 30 || strlen($password) < 5) {
        array_push($error_array, "Password must be between 5 and 30 characters<br>");
    }
}

    if (empty($error_array)) {
        $password = md5($password); // Hash password before sending to database

        // Generate username - concatenate first name with last name
        $username = strtolower($fname . "_" . $lname);
        $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");

        $i = 0;
        // If username exists, append number to the new username
        while(mysqli_num_rows($check_username_query) != 0) {
            $i++; // Add 1 to i
            $username = $username . "_" . $i;
            $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");
        }

        // Profile picture assignment
        $rand = rand(1,2); // Random number between 1 and 2
        if ($rand == 1) {
            $profile_pic = "assets/images/profile_pics/defaults/head_deep_blue.jpg";
        } else if ($rand == 2) {
            $profile_pic = "assets/images/profile_pics/defaults/head_emerald.jpg";
        }

        $query = mysqli_query($con, "INSERT INTO users VALUES (NULL, '$fname', '$lname', '$username', '$email', '$password', '$date', '$profile_pic', '0', '0', 'no', ',')");
        array_push($error_array, "<span style='color: #14C800'>You're all set! Go ahead and login!</span><br>");

        // Clear session variables
        $_SESSION['reg_fname'] = "";
        $_SESSION['reg_lname'] = "";
        $_SESSION['reg_email'] = "";
        $_SESSION['reg_email2'] = "";
    }
?>

<html>
<head>
    <title>Welcome to Swirlfeed!</title>
</head>
<body>
<form action="register.php" method="post">
    <input type="text" name="reg_fname" placeholder="First Name" value="<?php
    if (isset($_SESSION['reg_fname'])){
        echo $_SESSION['reg_fname'];
    }; ?>" required>
    <br>
    <?php if (in_array("First name must be between 2 and 25 characters<br>", $error_array)) { echo "First name must be between 2 and 25 characters<br>"; } ?>

    <input type="text" name="reg_lname" placeholder="Last Name" value="<?php
    if (isset($_SESSION['reg_lname'])){
        echo $_SESSION['reg_lname'];
    }; ?>" required>
    <br>
    <?php if (in_array("Last name must be between 2 and 25 characters<br>", $error_array)) { echo "Last name must be between 2 and 25 characters<br>"; } ?>

    <input type="email" name="reg_email" placeholder="Email" value="<?php
    if (isset($_SESSION['reg_email'])){
        echo $_SESSION['reg_email'];
    }; ?>" required>
    <br>
    <input type="email" name="reg_email2" placeholder="Confirm Email" value="<?php
    if (isset($_SESSION['reg_email2'])){
        echo $_SESSION['reg_email2'];
    }; ?>" required>
    <br>
    <?php if (in_array("Email already in use<br>", $error_array)) { echo "Email already in use<br>"; }
    else if (in_array("Password must contain only english letters and numbers<br>", $error_array)) { echo "Password must contain only english letters and numbers<br>"; }
    else if (in_array("Please enter a valid email address<br>", $error_array)) { echo "Please enter a valid email address<br>"; } ?>

    <input type="password" name="reg_password" placeholder="Password" required>
    <br>
    <input type="password" name="reg_password2" placeholder="Confirm Password" required>
    <br>
    <?php if (in_array("Passwords do not match<br>", $error_array)) { echo "Passwords do not match<br>"; }
    else if (in_array("Emails do not match<br>", $error_array)) { echo "Emails do not match<br>"; }
    else if (in_array("Password must be between 5 and 30 characters<br>", $error_array)) { echo "Password must be between 5 and 30 characters<br>"; } ?>

    <input type="submit" name="register_button" value="Register">
    <br>
    <?php if (in_array("<span style='color: #14C800'>You're all set! Go ahead and login!</span><br>", $error_array)) { echo "<span style='color: #14C800'>You're all set! Go ahead and login!</span><br>"; } ?>

</form>
</body>
</html>