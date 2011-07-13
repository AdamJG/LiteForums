<?php

    include_once('core.php');

    $forum = new forum();
    $forum->registerApp('Members');
    
    $members = new members();
    
    include_once('theme/' . $config['theme'] . '/header.php');
    
    $forum->viewNav(' &raquo; ');
    
?>

<table>
<tr><td class="appTitle" colspan="10">Members</td></tr>

<tr class="tableKey"><td width="25px"></td><td>Username</td><td>Date Joined</td><td>Last Online</td></tr>

<?php

    $selectMembers = mysql_query("SELECT * FROM forum_members");
    
    while($member = mysql_fetch_array($selectMembers)){
        $user = $members->getInfo($member['username']);
    
        echo '<tr>
            <td><img style="max-width:25px;max-height:25px;" src="' . $member['avatar'] . '" alt="" /></td>
            <td><a href="viewprofile.php?id=' . $user['id'] . '">' . $member['username'] . '</a></td>
            <td>' . date('d F Y', $member['regTimestamp']) . '</td>
            <td>' . date('d M Y, H:i', $member['latestActivityTimestamp']) . '</td>
        </tr>';
    }

?>
</table>

Total Members: <?php echo $members->memberCount(); ?>

<?php

    include_once('theme/' . $config['theme'] . '/footer.php');
    
?>