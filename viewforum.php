<?php

    include_once('core.php');
 
    include_once('theme/default/header.php');
    
    $forum = new forum();
    
    $forum->viewNav(' &raquo; ');
    
    $forum->viewForum();

    include_once('theme/default/footer.php');
    
?>