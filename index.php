<?php
    $con = mysqli_connect("localhost", "root", "", "social");
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_errno();
    }

    $query = mysqli_query($con, "INSERT INTO test VALUES(NULL, 'Darius')");
?>

<html>
    <head>
        <title>
            TITLE
        </title>
    </head>
    <body>
        Hello World!
    </body>
</html>