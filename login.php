<?php

    include_once('core.php');

    $forum->registerApp('Login');
    
    $members = new members();
    
    if($_POST['username']  && $_POST['password']){
        if($members->auth($_POST['username'], $_POST['password'])){
            header('location:index.php?login=true');
        } else {
            header('location:login.php?login=false');
        }
    }
    
    if($_GET['logout'] == true){
        $members->deauth();
    }
    
    if($_SESSION['username']){
        header('location:index.php');
    }
    
    include_once('theme/' . $config['theme'] . '/header.php');
    
    $forum->viewNav(' &raquo; ');
    
?>

<table>
<tr>
    <td class="appTitle">Login</td>
</tr>
<tr>
    <td>
        <?php if($_GET['login'] == 'false'){ ?>
            <div class="error">Invalid Username or Password.</div>
        <?php } ?>
        Login with your username and password.<br /><br />
        <form id="login" method="post" action="login.php">
            <label for="usernameInput">Username</label><input type="text" name="username" id="usernameInput" /><br /><br />
            <label for="passwordInput">Password</label><input type="password" name="password" id="passwordInput" /><br /><br />
            <input type="submit" value="Login" class="submitButton" /><span style="margin-left:10px;"><a href="register.php">Register</a></span>
        </form>
    </td>
</tr>
</table>

<?php

    include_once('theme/' . $config['theme'] . '/footer.php');
    
?>