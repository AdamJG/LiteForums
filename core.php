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
    
    if($_SESSION['username']){
        $members = new members();
        $members->updateLatestActivity();
    }

?>