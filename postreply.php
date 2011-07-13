<?php

    include_once('core.php');

    $forum = new forum();
    $forum->registerApp('Post Reply');
    
    if(!$_SESSION['username']){
        header('location:index.php');
    }
    
    $topicID = htmlentities($_GET['t']);
    
    if(!$forum->getTopicName($topicID)){
        header('location:index.php');
    }
    
    if($_POST['postContent']){
        if($forum->checkPostContent($_POST['postContent'])){
            $newPostID = $forum->postReply($topicID, $_POST['postContent'], $_SESSION['username']);
            
            header('location:viewtopic.php?id=' . $topicID . '&p=' . $forum->pageCount($topicID) . '#p' . $newPostID);
        } else {
            header('location:postreply.php?t=' . $topicID . '&validity=false');
        }
    }
    
    include_once('theme/' . $config['theme'] . '/header.php');
    
    $forum->viewNav(' &raquo; ');
    
?>
<h2><?php echo $forum->getTopicName($topicID); ?></h2>
<table>
<tr>
    <td class="appTitle">Post Reply</td>
</tr>
<tr>
    <td>
        <?php if($_GET['validity'] == 'false'){ ?>
            <div class="error">Your message is too short.</div>
        <?php } ?>
        Replying to topic: <a href="viewtopic.php?id=<?php echo $topicID; ?>"><?php echo $forum->getTopicName($topicID); ?></a><br /><br />
        <form id="postreply" method="post" action="postreply.php?t=<?php echo $topicID; ?>">
            <label>Message</label><textarea name="postContent" id="postContent"></textarea><br /><br />
            <input type="submit" value="Submit" class="submitButton" />
        </form>
    </td>
</tr>
</table>

<?php

    include_once('theme/' . $config['theme'] . '/footer.php');
    
?>