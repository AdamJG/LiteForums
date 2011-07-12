<?php

    class forum {
        private $forumID;
        private $topicID;
        private $appName;
    
        function __construct(){
            if($this->currentPage() == 'viewforum.php'){
                if($_GET['id']){
                    $this->forumID = mysql_real_escape_string($_GET['id']);
                } else {
                    $error = new error('Error: Page not found.');
                }
            } elseif($this->currentPage() == 'viewtopic.php') {
                $this->topicID = mysql_real_escape_string($_GET['id']);
                
                $this->forumID = $this->getForumID($this->topicID);
            } else {
                $this->topicID = false;
                $this->forumID = false;
            }
            
            $this->appName = false;
        }
        
        public function viewNav($seperator = ' &raquo; ')
        {
            echo '<div id="forumNav">';
        
            if($this->currentPage() == 'index.php'){
                echo 'Index';
            } else {
                echo '<a href="index.php">Index</a>';
            }
            
            if($this->forumID != false){
                $selectForum = mysql_query("SELECT name FROM forum_forums WHERE id = {$this->forumID}");
                
                if($selectForum){
                    $forum = mysql_fetch_assoc($selectForum);
                    
                    if($this->topicID != false){
                        echo $seperator . '<a href="viewforum.php?id=' . $this->forumID . '">' . $forum['name'] . '</a>';
                    } else {
                        echo $seperator . $forum['name'];
                    }
                } else {
                    $error = new error('Error: Page not found.');
                }
            } elseif($this->appName != false){
                echo $seperator . $this->appName;
            }
            
            if($this->topicID != false){
                $selectTopic = mysql_query("SELECT name FROM forum_topics WHERE id = {$this->topicID}");
                
                if($selectTopic){
                    $topic = mysql_fetch_assoc($selectTopic);
                    
                    echo $seperator . $topic['name'];
                } else {
                    $error = new error('Error: Page not found.');
                }
            }
            
            echo '</div>';
        }
    
        public function viewIndex()
        {        
            $selectCats = mysql_query("SELECT * FROM forum_categories");
            
            if($selectCats){
                while($cat = mysql_fetch_array($selectCats)){
                    $selectForums = mysql_query("SELECT * FROM forum_forums WHERE cat = {$cat['id']}");
                    
                    if($selectForums){
                        echo '<table><tr class="catName"><td colspan="10">' . $cat['name'] . '</td></tr>
                            <tr class="tableKey"><td width="25px"></td><td width="50%">Name</td><td class="topicsKey">Topics</td><td class="postsKey">Posts</td><td>Last Post</td></tr>';
                        
                        $members = new members();
                        
                        while($forum = mysql_fetch_array($selectForums)){
                            $lastPost = $this->lastPostFromForum($forum['id']);
                            
                            echo '<tr>
                                <td><a href="viewforum.php?id=' . $forum['id'] . '"><img src="theme/default/images/forumIcon.png" alt="" /></a></td>
                                <td class="forumName"><a href="viewforum.php?id=' . $forum['id'] . '">' . $forum['name'] . '</a></td>
                                <td class="forumTopicCount">' . $this->forumTopicCount($forum['id']) . '</td>
                                <td class="forumPostCount">' . $this->forumPostCount($forum['id']) . '</td>';
                            
                            if($lastPost['topic']){
                                $user = $members->getInfo($lastPost['username']);
                                
                                echo '<td class="forumsLastPost">
                                        <div class="lastPostTimestamp">'. date('d M Y, H:i', $lastPost['timestamp']) . '</div>
                                        In <a href="viewtopic.php?id='. $lastPost['topic'] . '#' . $lastPost['id'] . '">' . $this->getTopicName($lastPost['topic']) . '</a><br />
                                        By <a href="viewprofile.php?id=' . $user['id'] . '">' . $lastPost['username'] . '</a>
                                    </td>';
                            } elseif($lastPost['id']){
                                $user = $members->getInfo($lastPost['username']); 
                            
                                echo '<td class="forumsLastPost">
                                        <div class="lastPostTimestamp">'. date('d M Y, H:i', $lastPost['timestamp']) . '</div>
                                        In <a href="viewtopic.php?id='. $lastPost['id'] . '">' . $lastPost['name'] . '</a><br />
                                        By <a href="viewprofile.php?id=' . $user['id'] . '">' . $lastPost['username'] . '</a>
                                    </td>';
                            } else {
                                echo '<td class="forumsLastPost">None</td>';
                            }
                            
                            echo '</tr>';
                        }
                        
                        echo '</table>';
                    } else {
                        $error = new error('Error: Couldn\'t retrieve the forums');
                    }
                    
                }
            } else {
                $error = new error('Error: Couldn\'t retrieve the categories');
            }
            
            echo '</table>';
        }
        
        public function viewForum()
        {
            $selectTopics = mysql_query("SELECT * FROM forum_topics WHERE forum = {$this->forumID}");
            
            $selectForum = mysql_query("SELECT * FROM forum_forums WHERE id = {$this->forumID}");
            
            $members = new members();
            
            if($selectTopics && $selectForum){
                $forum = mysql_fetch_assoc($selectForum);
                
                echo '<h2>' . $forum['name'] . '</h2>';
                
                if($_SESSION['username']){
                    echo '<div class="newTopic button"><a href="newtopic.php?f=' . $forum['id'] . '">New Topic</a></div>';
                }
                
                echo '<div id="currentCategory">Category: <a href="#">' . $this->getCategoryName($forum['cat']) . '</a></div>';
                
                if(mysql_num_rows($selectTopics) > 0){
                    echo '<table><tr class="topicsKey"><td width="25px"></td><td>Name</td><td>Username</td><td>Views</td><td>Replies</td><td>Latest Reply</td></td></tr>';
                
                    while($topic = mysql_fetch_array($selectTopics)){
                        $user = $members->getInfo($topic['username']);
                        
                        $lastPost = $this->lastPostFromTopic($topic['id']);
                        
                        echo '<tr>
                            <td><a href="viewforum.php?id=' . $forum['id'] . '"><img src="theme/default/images/forumIcon.png" alt="" /></a></td>
                            <td><a href="viewtopic.php?id=' . $topic['id'] . '">' . $topic['name'] . '</a></td>
                            <td><a href="viewprofile.php?id=' . $user['id'] . '">' . $user['username'] . '</td>
                            <td>' . $topic['views'] . '</td>
                            <td>' . $this->topicPostCount($topic['id']) . '</td>';
                            
                        if($lastPost['id']){
                            $lastPostUser = $members->getInfo($lastPost['username']);
                        
                            echo '<td class="topicLastReply">
                                <div class="lastReplyTimestamp">' . date('d M Y, H:i', $lastPost['timestamp']) . '</div>
                                By <a href="viewprofile.php?id=' . $lastPostUser['id'] . '">' . $lastPostUser['username'] . '</a>
                            </td>';
                        } else {
                            echo '<td>None</td>';
                        }
                        
                        echo '</tr>';
                    }
                    
                    echo '</table>';
                    
                    if($_SESSION['username']){
                        echo '<div class="newTopic button"><a href="newtopic.php?f=' . $forum['id'] . '">New Topic</a></div>';
                    }
                    
                    echo '<div class="pagination">Page: [1]</div>';
                } else {
                    echo 'No topics found.';
                }
            } else {
                $error = new error('Error: Couldn\'t retrieve topics in forum: ' . $this->forumID);
            }
        }
        
        public function viewTopic()
        {
            global $config;
        
            $selectTopic = mysql_query("SELECT * FROM forum_topics WHERE id = {$this->topicID}");
            
            if($selectTopic){
                while($topic = mysql_fetch_array($selectTopic)){
                    $updateViews = mysql_query("UPDATE forum_topics SET views = views + 1 WHERE id = {$this->topicID}");
                
                    if(!$updateViews){
                        $error = new error('Error: Couldn\'t update topic\'s view count');
                    }
                    
                    echo '<h2>' . $topic['name'] . '</h2>';
                    
                    if($_SESSION['username']){
                        echo '<div class="postReply button"><a href="postreply.php?t=' . $topic['id'] . '">Post Reply</a></div>';
                    }
                    
                    echo '<div id="topicStats">Views: ' . $topic['views'] . ', Replies: ' . $this->topicPostCount($topic['id']) . '</div>';

                    echo '<table><tr class="postKey"><td>Author</td><td>Content</td></tr>';
                    
                    $user = new members();
                    $userInfo = $user->getInfo($topic['username']);

                    echo '<tr>
                        <td class="miniProfile">
                            <div class="postAvatar"><img style="max-height:' . $config['maxAvatarHeight'] . 'px;max-width:' . $config['maxAvatarWidth'] . 'px;" src="' . $userInfo['avatar'] . '" alt="" /></div>
                            <div class="postUsername"><a href="viewprofile.php?id=' . $userInfo['id'] . '">' . $userInfo['username'] . '</a></div>
                            <div class="postUserRank">' . $userInfo['rank'] . '</div>
                            <div class="postUserInfo">Posts: ' . $userInfo['posts'] . '</div>
                        </td>
                        <td class="postContent">' . $this->displayPostContent($topic['content']) . '</td>
                    </tr>';
                    
                    $selectPosts = mysql_query("SELECT * FROM forum_posts WHERE topic = {$this->topicID} ORDER BY timestamp ASC");
                    
                    if($selectPosts){
                        while($post = mysql_fetch_array($selectPosts)){
                            $postContent = $this->displayPostContent($post['content']);
                            
                            $userInfo = $user->getInfo($post['username']);
                            
                            echo '<tr>
                                <td class="miniProfile">
                                    <div class="postAvatar"><img style="max-height:' . $config['maxAvatarHeight'] . 'px;max-width:' . $config['maxAvatarWidth'] . 'px;" src="' . $userInfo['avatar'] . '" alt="" /></div>
                                    <div class="postUsername"><a href="viewprofile.php?id=' . $userInfo['id'] . '">' . $userInfo['username'] . '</a></div>
                                    <div class="postUserRank">' . $userInfo['rank'] . '</div>
                                    <div class="postUserInfo">Posts: ' . $userInfo['posts'] . '</div>
                                </td>
                                <td class="postContent">' . $postContent . '</td>
                            </tr>';
                        }
                    } else {
                        $error = new error('Error: Couldn\'t retrieve posts');
                    }
                    
                    echo '</table>';
                    
                    if($_SESSION['username']){
                        echo '<div class="postReply button"><a href="postreply.php?t=' . $topic['id'] . '">Post Reply</a></div>';
                    }
                    
                    echo '<div class="pagination">Page: [1]</div>';
                    
                    break;
                }
            } else {
                $error = new error('Error: Couldn\'t retrieve topic infomation');
            }
        }
        
        public function displayPostContent($content)
        {
            $content = $this->bbcode($content);
            
            $content = nl2br($content);
            
            return $content;
        }
        
        public function getForumID($topicID)
        {
            $selectTopic = mysql_query("SELECT forum FROM forum_topics WHERE id = {$topicID}");
            
            $topic = mysql_fetch_assoc($selectTopic);
            
            if($topic){
                return $topic['forum'];
            } else {
                $error = new error('Error: Couldn\'t find selected topic @ getForumID()');
            }
        }
        
        public function currentPage()
        {
            return substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);
        }
        
        public function forumTopicCount($forumID)
        {
            $selectTopics = mysql_query("SELECT id FROM forum_topics WHERE forum = {$forumID}");
            
            if($selectTopics){
                return mysql_num_rows($selectTopics);
            } else {
                $error = new error('Error: Couldn\'t count topics in forum ID = ' . htmlentities($forumID));
            }
        }
        
        public function forumPostCount($forumID)
        {
            $selectPosts = mysql_query("SELECT id FROM forum_posts WHERE forum = {$forumID}");
            $selectTopics = mysql_query("SELECT id FROM forum_topics WHERE forum = {$forumID}");
            
            if($selectPosts){
                return (mysql_num_rows($selectPosts) + mysql_num_rows($selectTopics));
            } else {
                $error = new error('Error: Couldn\'t count posts in forum ID = ' . htmlentities($forumID));
            }
        }
        
        public function topicPostCount($topicID)
        {
            $selectPosts = mysql_query("SELECT id FROM forum_posts WHERE topic = {$topicID}");
            
            if($selectPosts){
                return mysql_num_rows($selectPosts);
            } else {
                $error = new error('Error: Couldn\'t count posts in topic ID = ' . htmlentities($topicID));
            }
        }
        
        public function registerApp($name)
        {
            $this->appName = $name;
        }
        
        public function getTopicName($topicID)
        {
            $topicID = mysql_real_escape_string($topicID);
        
            $selectTopic = mysql_query("SELECT name FROM forum_topics WHERE id = '$topicID'");
            
            $topic = mysql_fetch_assoc($selectTopic);
            
            if($topic){
                return $topic['name'];
            } else {
                return false;
            }
        }
        
        public function getForumName($forumID)
        {
            $forumID = mysql_real_escape_string($forumID);
        
            $selectForum = mysql_query("SELECT name FROM forum_forums WHERE id = '$forumID'");
            
            $forum = mysql_fetch_assoc($selectForum);
            
            if($forum){
                return $forum['name'];
            } else {
                return false;
            }
        }
        
        public function getCategoryName($catID)
        {
            $catID = mysql_real_escape_string($catID);
        
            $selectCat = mysql_query("SELECT name FROM forum_categories WHERE id = '$catID'");
            
            $cat = mysql_fetch_assoc($selectCat);
            
            if($cat){
                return $cat['name'];
            } else {
                return false;
            }
        }
        
        public function getCategoryID($forumID)
        {
            $forumID = mysql_real_escape_string($forumID);
        
            $selectForum = mysql_query("SELECT cat FROM forum_forums WHERE id = '$forumID'");
            
            $forum = mysql_fetch_assoc($selectForum);
            
            if($forum){
                return $cat['cat'];
            } else {
                return false;
            }
        }
        
        public function checkPostContent($content)
        {
            if(strlen($content) > 10){
                return true;
            } else {
                return false;
            }
        }
        
        public function checkTopicName($topicName)
        {
            if(strlen($topicName) > 3){
                return true;
            } else {
                return false;
            }
        }
        
        public function curTimestamp()
        {
            return date('U');
        }
        
        public function postReply($topicID, $content, $username)
        {
            $topicID = mysql_real_escape_string($topicID);
            $username = mysql_real_escape_string($username);
            $content = mysql_real_escape_string($content);
            $content = htmlentities($content);
            $timestamp = $this->curTimestamp();
            $forumID = $this->getForumID($topicID);
            
            $submitPost = mysql_query("INSERT INTO forum_posts (username, content, timestamp, topic, forum) VALUES ('$username', '$content', '$timestamp', '$topicID', '$forumID')");
                
            if($submitPost){
                $members = new members();
                $members->incrementPostCount($username);
            
                return true;
            } else {
                $error = new error('Error: Couldn\'t post reply.');
            }
        }
        
        public function newTopic($forumID, $topicName, $content, $username)
        {
            $topicName = mysql_real_escape_string($topicName);
            $username = mysql_real_escape_string($username);
            $content = mysql_real_escape_string($content);
            $content = htmlentities($content);
            $timestamp = $this->curTimestamp();
            $forumID = mysql_real_escape_string($forumID);
            $catID = $this->getCategoryID($forumID);
            
            $submitTopic = mysql_query("INSERT INTO forum_topics (name, username, timestamp, forum, cat, content) VALUES ('$topicName', '$username', '$timestamp', '$forumID', '$catID', '$content')");
       
            if($submitTopic){
                $selectNewTopic = mysql_query("SELECT id FROM forum_topics WHERE name = '$topicName' AND timestamp = '$timestamp' AND username = '$username'");
            
                $newTopic = mysql_fetch_assoc($selectNewTopic);
                
                if($newTopic){
                    $members = new members();
                    $members->incrementPostCount($username);
                
                    return $newTopic['id'];
                } else {
                    $error = new error('Error: Couldn\'t retrieve new topic ID.');
                }
            } else {
                $error = new error('Error: Couldn\'t post new topic.');
            }
        }
        
        public function bbcode($content)
        {
            $bbcode = array("<", ">",
                        "[list]", "[*]", "[/list]", 
                        "[img]", "[/img]", 
                        "[b]", "[/b]", 
                        "[u]", "[/u]", 
                        "[i]", "[/i]",
                        '[color="', "[/color]",
                        "[size=\"", "[/size]",
                        '[url="', "[/url]",
                        "[mail=\"", "[/mail]",
                        "[code]", "[/code]",
                        "[quote]", "[/quote]",
                        '"]');
            $htmlcode = array("&lt;", "&gt;",
                        "<ul>", "<li>", "</ul>", 
                        "<img src=\"", "\">", 
                        "<b>", "</b>", 
                        "<u>", "</u>", 
                        "<i>", "</i>",
                        "<span style=\"color:", "</span>",
                        "<span style=\"font-size:", "</span>",
                        '<a href="', "</a>",
                        "<a href=\"mailto:", "</a>",
                        "<code>", "</code>",
                        "<table width=100% bgcolor=lightgray><tr><td bgcolor=white>", "</td></tr></table>",
                        '">');
            $newContent = str_replace($bbcode, $htmlcode, $content);
            return $newContent;
        }
        
        public function lastPostFromForum($forumID)
        {
            $selectPost = mysql_query("SELECT id, username, timestamp, topic FROM forum_posts WHERE forum = '$forumID' ORDER BY timestamp DESC");
            $post = mysql_fetch_assoc($selectPost);
            
            $selectTopic = mysql_query("SELECT id, name, username, timestamp FROM forum_topics WHERE forum = '$forumID' ORDER BY timestamp DESC");
            $topic = mysql_fetch_assoc($selectTopic);
            
            if($post['timestamp'] > $topic['timestamp']){
                return $post;
            } else {
                return $topic;
            }
        }
        
        public function lastPostFromTopic($topicID)
        {
            $selectPost = mysql_query("SELECT id, username, timestamp, topic FROM forum_posts WHERE topic = '$topicID' ORDER BY timestamp DESC");
            $post = mysql_fetch_assoc($selectPost);
            
            return $post;
        }
        
        public function viewStatistics()
        {
            $members = new members();
        
            echo '<table>';
            
            echo '<tr class="catName"><td colspan="10">Forum Infomation</td></tr>
                <tr class="tableKey"><td>Users Online (In the past 30 minutes)</td></tr>
                <tr><td>' . $members->usersOnline() . '</td></tr>
                <tr class="tableKey"><td>Statistics</td></tr>
                <tr><td id="statistics">Total Posts: <strong>' . $this->totalPosts() . '</strong> &#183; Total Topics: <strong>' . $this->totalTopics() . '</strong> &#183; Total Members: <strong>' . $members->memberCount() . '</strong></td></tr>';
            
            echo '</table>';
        }
        
        public function totalTopics()
        {
            $selectTopics = mysql_query("SELECT id FROM forum_topics");
            
            if($selectTopics){
                return mysql_num_rows($selectTopics);
            } else {
                $error = new error('Error: Can\'t retrieve total posts');
            }
        }
        
        public function totalPosts()
        {
            $selectPosts = mysql_query("SELECT id FROM forum_posts");
            
            if($selectPosts){
                return (mysql_num_rows($selectPosts) + $this->totalTopics());
            } else {
                $error = new error('Error: Can\'t retrieve total posts');
            }
        }
    }

?>