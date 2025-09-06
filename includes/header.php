<?php
require 'config/config.php';

if (isset($_SESSION['username'])) {
    $user_logged_in = $_SESSION['username'];
    $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username = '$user_logged_in'");
    $user = mysqli_fetch_array($user_details_query);
} else {
    header("Location: register.php");
}
?>

<html>
<head>
    <title>Swirlfeed</title>
    <!-- Javascript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="../assets/js/bootstrap.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="top_bar">
    <div class="logo">
        <a href="index.php">Swirlfeed!</a>
    </div>
    <nav>
        <a href="<?php echo $user_logged_in; ?>">
            <?php echo $user['first_name']; ?>
        </a>
        <a href="">
            <i class="fa fa-home fa-lg" aria-hidden="true"></i>
        </a>
        <a href="">
            <i class="fa fa-envelope fa-lg" aria-hidden="true"></i>
        </a>
        <a href="">
            <i class="fa fa-bell-o fa-lg" aria-hidden="true"></i>
        </a>
        <a href="">
            <i class="fa fa-users fa-lg" aria-hidden="true"></i>
        </a>
        <a href="">
            <i class="fa fa-cog fa-lg" aria-hidden="true"></i>
        </a>
        <a href="includes/handlers/logout.php">
            <i class="fa fa-sign-out fa-lg" aria-hidden="true"></i>
        </a>
    </nav>
</div>

<div class="wrapper">
