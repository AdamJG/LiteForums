<?php

    include_once('core.php');

    $forum->updateTopicViews();
 
    include_once('theme/' . $config['theme'] . '/header.php');
    
    $forum->viewNav(' &raquo; ');
    
    $forum->viewTopic();

    include_once('theme/' . $config['theme'] . '/footer.php');
    
?>