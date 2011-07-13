<?php

    include_once('core.php');
 
    include_once('theme/' . $config['theme'] . '/header.php');
    
    $forum = new forum();
    
    $forum->viewNav(' &raquo; ');
    
    $forum->viewForum();

    include_once('theme/' . $config['theme'] . '/footer.php');
    
?>