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
    
    include_once('theme/' . $config['theme'] . '/header.php');
    
    $forum->viewNav(' &raquo; ');
    
?>

<table>
<tr><td class="appTitle" colspan="10"><?php echo $user['username']; ?>'s Profile</td></tr>

<tr>
    <td style="width: <?php echo $config['maxAvatarWidth']; ?>px;text-align:center;"><div class="profileAvatar"><img <?php echo 'style="max-height:' . $config['maxAvatarHeight'] . 'px;max-width:' . $config['maxAvatarWidth'] . 'px;"'; ?> src="<?php echo $user['avatar']; ?>" alt="" /></a></td>
    <td style="vertical-align: top">
        <strong>Rank:</strong> <?php echo $user['rank']; ?><br />
        <strong>Posts:</strong> <?php echo $user['posts']; ?><br />
        <strong>Joined:</strong> <?php echo date('d F Y', $user['regTimestamp']); ?><br />
        <strong>Last Online:</strong> <?php echo date('d F Y, H:i', $user['latestActivityTimestamp']); ?><br /><br />
        
        <a href="#">Send a Personal Message</a>
    </td>
</tr>
</table>

<?php

    include_once('theme/' . $config['theme'] . '/footer.php');
    
?>