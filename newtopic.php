<?php

    include_once('core.php');
    
    $forum->registerApp('New Topic');
    
    if(!$_SESSION['username']){
        header('location:index.php');
    }
    
    $forumID = htmlentities($_GET['f']);
    
    if(!$forum->getForumName($forumID)){
        header('location:index.php');
    }

    if($_POST['postContent'] && $_POST['topicName']){
        if($forum->checkPostContent($_POST['postContent']) && $forum->checkTopicName($_POST['topicName'])){
            $newTopicID = $forum->newTopic($forumID, $_POST['topicName'], $_POST['postContent'], $_SESSION['username']);
            
            header('location:viewtopic.php?id=' . $newTopicID);
        } else {
            header('location:newtopic.php?f=' . $forumID . '&validity=false1');
        }
    } elseif($_POST['postContent'] && !$_POST['topicName']){
        header('location:newtopic.php?f=' . $forumID . '&validity=false2');
    } elseif(!$_POST['postContent'] && $_POST['topicName']){
        header('location:newtopic.php?f=' . $forumID . '&validity=false2');
    }
    
    include_once('theme/' . $config['theme'] . '/header.php');
    
    $forum->viewNav(' &raquo; ');
    
?>
<h2><?php echo $forum->getForumName($forumID); ?></h2>
<table>
<tr>
    <td class="appTitle">New Topic</td>
</tr>
<tr>
    <td>
        <?php if($_GET['validity'] == 'false1'){ ?>
            <div class="error">Your message or topic name is too short.</div>
        <?php }elseif($_GET['validity'] == 'false2'){ ?>
            <div class="error">You must fill in all the required fields.</div>
        <?php } ?>
        Posting in the forum: <a href="viewforum.php?id=<?php echo $forumID; ?>"><?php echo $forum->getForumName($forumID); ?></a><br /><br />
        <form id="newtopic" method="post" action="newtopic.php?f=<?php echo $forumID; ?>">
            <label>Name</label><input type="text" name="topicName" id="topicNameInput" /><br /><br />
            <label>Message</label><textarea name="postContent" id="postContent"></textarea><br /><br />
            <input type="submit" value="Submit" class="submitButton" />
        </form>
    </td>
</tr>
</table>

<?php

    include_once('theme/' . $config['theme'] . '/footer.php');
    
?>