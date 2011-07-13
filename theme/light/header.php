<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<link rel="stylesheet" type="text/css" href="theme/<?php echo $config['theme']; ?>/style.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
</head>
<body>
<div id="wrap">
<h1><span style="color:#777;">Lite</span>Forums</h1>
<div id="nav">
    <a href="index.php">Home</a>
    <a href="index.php">Forum</a>
    <a href="members.php">Members</a>
    <?php if($_SESSION['username']){ ?>
    <span style="float:right">
        Hello, <a href="#">Adam</a>
        [ <a href="login.php?logout=true">Logout</a>]
    </span>
    <?php } else { ?>
    <span style="float:right">
        <a href="register.php">Register</a>
        <a href="login.php">Login</a>
    </span>
    <?php } ?>
</div>