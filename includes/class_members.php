<?php

    class members {
        public function auth($username, $password)
        {
            $username = mysql_real_escape_string($username);
            $password = mysql_real_escape_string($password);
            
            $password = md5($password);
        
            $selectUser = mysql_query("SELECT id, username FROM forum_members WHERE username = '$username' AND password = '$password'");
        
            if($selectUser){
                if(mysql_num_rows($selectUser) > 0){
                    $_SESSION['username'] = $selectUser['username'];
                    return true;
                } else {
                    return false;
                }
            } else {
                $error = new error('Error: Invalid MYSQL query in $members->auth()');
            }
        }
        
        public function deauth()
        {
            session_destroy();
            //need to improve this
        }
        
        public function getInfo($username)
        {
            $selectUserInfo = mysql_query("SELECT * FROM forum_members WHERE username = '$username'");
            
            $userInfo = mysql_fetch_assoc($selectUserInfo);
            
            if($userInfo){
                return $userInfo;
            } else {
                $error = new error('Error: Couldn\'t retrieve user info.');
            }
        }
        
        public function getInfoFromID($userID)
        {
            $userID = mysql_real_escape_string($userID);
        
            $selectUserInfo = mysql_query("SELECT * FROM forum_members WHERE id = '$userID'");
            
            $userInfo = mysql_fetch_assoc($selectUserInfo);
            
            if($userInfo){
                return $userInfo;
            } else {
                $error = new error('Error: Couldn\'t retrieve user info.');
            }
        }
        
        public function incrementPostCount($username)
        {
            $updateUserInfo = mysql_query("UPDATE forum_members SET posts = posts+1 WHERE username = '$username'");
        
            if(!$updateUserInfo){
                $error = new error('Error: Couldn\'t increment member\'s post count.');
            }
        }
        
        public function updateLatestActivity()
        {
            $username = $_SESSION['username'];
            $forum = new forum();
            $timestamp = $forum->curTimestamp();
            
            $updateLatestActivity = mysql_query("UPDATE forum_members SET latestActivityTimestamp = '$timestamp' WHERE username = '$username'");
        
            if(!$updateLatestActivity){
                $error = new error('Error: Couldn\'t update latest Activity.');
            }
        }
        
        public function memberCount()
        {
            $selectMembers = mysql_query("SELECT id FROM forum_members");
            
            if($selectMembers){
                return mysql_num_rows($selectMembers);
            } else {
                $error = new error('Error: Couldn\'t get memberCount.');
            }
        }
        
        public function usersOnline($separator = ', ')
        {
            $forum = new forum();
            $timestamp = $forum->curTimestamp() - (60 * 30);
        
            $selectOnlineUsers = mysql_query("SELECT id, username, rank FROM forum_members WHERE latestActivityTimestamp >= '$timestamp'");
        
            $usersOnlineStr = false;
            while($getOnlineUser = mysql_fetch_array($selectOnlineUsers)){
                if($usersOnlineStr){
                    $usersOnlineStr = $usersOnlineStr . $separator . '<a href="viewprofile.php?id=' . $getOnlineUser['id'] . '">' . $getOnlineUser['username'] . '</a>';
                } else {
                    $usersOnlineStr = '<a href="viewprofile.php?id=' . $getOnlineUser['id'] . '">' . $getOnlineUser['username'] . '</a>';
                }
            }
            
            return $usersOnlineStr;
        }
        
        public function usersOnlineCount()
        {
            $forum = new forum();
            $timestamp = $forum->curTimestamp() - (60 * 30);
        
            $selectOnlineUsers = mysql_query("SELECT id, username, rank FROM forum_members WHERE latestActivityTimestamp >= '$timestamp'");
        
            if($selectOnlineUsers){
                return mysql_num_rows($selectOnlineUsers);
            } else {
                $error = new error('Error: Can\'t retrieve users online count.');
            }
            
            return $usersOnlineStr;
        }

        public function getGravatar($email)
        {
            $hash = md5(strtolower(trim($email)));

            return 'http://www.gravatar.com/avatar/' . $hash;
        }
    }

?>