<?php

    session_start();

    include_once('includes/class_database.php');
    
    $config = array();
    
    $db = new database();
    $db->connect('localhost', 'rollzart_adam', 'adammada', 'rollzart_forumindev');
    
    $selectConfig = mysql_query("SELECT * FROM forum_config");
    
    while($getConfig = mysql_fetch_array($selectConfig)){
        $config[$getConfig['key']] = $getConfig['value'];
    }
    
    include_once('includes/class_error.php');
    include_once('includes/class_forum.php');
    include_once('includes/class_members.php');
    
    $members = new members();
    $forum = new forum();
    
    if($_SESSION['username']){
        $members->updateLatestActivity();
    }
    
    if($_POST['themeChanger']){
        setcookie('lf_theme', $_POST['themeChanger'], time() + 60 * 60 * 24 * 365);
        header('location: index.php');
    }
    
    if($_COOKIE['lf_theme']){
        //need to check if theme exists
        $config['theme'] = $_COOKIE['lf_theme'];
    }

?>