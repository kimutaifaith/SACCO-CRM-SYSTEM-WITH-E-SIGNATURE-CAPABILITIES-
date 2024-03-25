<?php
//include auth_session.php file on all user panel pages
session_start();
    if(!isset($_SESSION["username"])) {
        header("Location: login.php");
        exit();
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dashboard - Client area</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="icon" type="image/png" href="asset/images/favicon2.jpg">
    <style>
        body {
            background-image: url('asset/images/Background3.png'); /* Replace 'path/to/your/image.jpg' with the path to your image */
            background-size: Cover; /* Cover the entire background */
        }
       </style> 
</head>
<body>
    <div class="form">
        <p>Hey, <?php echo $_SESSION['username']; ?>!</p>
        <p>You have successfully logged in. Do you want to logout?</p>
        <p><a href="logout.php">Logout</a></p>
    </div>
</body>
</html>