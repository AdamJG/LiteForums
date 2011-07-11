<?php

    include_once('core.php');

    $forum = new forum();
    $forum->registerApp('Profile');
    
    $members = new members();
    
    if($_GET['id']){
        $user = $members->getInfoFromID($_GET['id']);
    } else {
        header('location:index.php');
    }
    
    include_once('theme/default/header.php');
    
    $forum->viewNav(' &raquo; ');
    
?>

<table>
<tr><td class="appTitle" colspan="10"><?php echo $user['username']; ?>'s Profile</td></tr>

<tr><td><div class="profileAvatar"><img <?php echo 'style="max-height:' . $config['maxAvatarHeight'] . 'px;max-width:' . $config['maxAvatarWidth'] . 'px;"'; ?> src="<?php echo $user['avatar']; ?>" alt="" /></a></td></tr>
</table>

<?php

    include_once('theme/default/footer.php');
    
?>