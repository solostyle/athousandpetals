<?php
ob_start(); // keeping in case something is outputted before header() is called
session_start();


if (($_SERVER['REQUEST_URI']) == '/members/log_out') {

    session_unset();
    session_destroy();
    $_SESSION = array();


    // If its desired to kill the session, also delete the session cookie.
    // Note: This will destroy the session, and not just the session data!
    if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
    }

    $back = (isset($_SERVER['HTTP_REFERER']))? htmlspecialchars($_SERVER['HTTP_REFERER']) : make_url('');
    header("Location: ".$back);  // won't work after <html>
}

if(isset($_POST['login_submit'])) {

    // check to see if username and password have been entered
    if (!$_POST['username']) echo "enter a username. \n";
    else $u_login = mysql_real_escape_string($_POST['username']);
    if (!$_POST['password']) echo "enter a password. \n";
    else $p_login = mysql_real_escape_string($_POST['password']);
    
    if ($u_login && $p_login) {
        select_db();
        $q = "SELECT `user_id`, `username`, `last` FROM `users` WHERE `username`='$u_login' AND `password`= MD5('$p_login')";
        $result = mysql_query($q);

        if (mysql_num_rows($result)>0) { // a match was made
            // start session
            session_regenerate_id();
            $user=mysql_fetch_assoc($result);
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id']=$user['user_id'];
            $_SESSION['username']=$user['username'];
            $_SESSION['last_last']= $last_last = $user['last'];
            session_write_close();
            // save the time logged in as LAST, and previous last as LAST LAST
            $now  = my_mktime();
            $now_f = strftime('%G.%m.%d %H:%M',$now);
            $update_lasts = "UPDATE `users` SET `last` = '$now_f', `last_last` = '$last_last' WHERE `username` = '$u_login'";
            mysql_query($update_lasts);

            header("Location: http://" . HOST . $_SERVER['REQUEST_URI']);  // won't work after <html>

        } else {
        // no match was made
        echo 'user does not exist, or bad password';
        }

    }
    else // one of the data tests failed
        echo 'technical problem. try again.';
}
?>

<html>
<head>
<title>a thousand petals | home</title>
<!-- Individual YUI JS files --> 
<?php $html = new HTML();?>
<?php echo $html->includeJs('yui28yahoo');?>
<?php echo $html->includeJs('yui28event');?>
<?php echo $html->includeJs('yui28connection');?>
<?php echo $html->includeJs('yui28dom');?>
<?php echo $html->includeJs('atp');?>
<?php echo $html->includeJs('atp.shell');?>
<?php echo $html->includeJs('atp.admin');?>
<?php echo $html->includeJs('atp.massage');?>
<?php echo $html->includeJs('atp.massage.policies');?>
<?php echo $html->includeJs('atp.yoga');?>
<?php echo $html->includeJs('atp.meditation');?>
<?php echo $html->includeJs('jquery-1.6.2.min');?>
<?php echo $html->includeJs('jquery.dimensions.min');?>
<?php echo $html->includeCss('layout');?>
<?php echo $html->includeCss('format');?>

<script language="javascript">
var name = "#left", menuYloc = null;
$(document).ready(function(){
	menuYloc = parseInt($(name).css("top").substring(0,$(name).css("top").indexOf("px")));
	$(window).scroll(function(){
		var offset = menuYloc+$(document).scrollTop()+"px";
        $(name).animate({top:offset},{duration:500,queue:false});
	});
});
</script>

</head>
<body>
<div id="page">
    <h1 id="pagetitle"><a href="/">Archana Sriram</a></h1>

    <!-- some lame tagline -->
    <p id="pagesubtitle"><em>Massage Therapist, Yoga and Meditation Teacher</em></p>

    <!-- show top-level navigation -->
    <ul id="topnav"><?php 
        // find the current category for highlighting later
        $uriArray = explode('/', $_SERVER['REQUEST_URI']);
        $currentCat = (count($uriArray)>=2)?$uriArray[1]:'';
        $currentPage = (count($uriArray)>=3)?$uriArray[2]:'';

        select_db();
        $cats = rtrv_categories();

        foreach ($cats as $c) {
            $link = str_replace(" ", "_", $c);
            // highlight the category
            if ($link == $currentCat) {
                echo '<li class="currentDivision">' . make_link($c, make_url($link.'/')). '</li>';
            } else {
                echo make_list_item(make_link($c, make_url($link.'/')));
            }
        }
        mysql_close();
        ?>
    </ul>

    <ul id="midnav" class="hidden">
        <?php 
        switch ($currentCat) {
            case "Massage_Therapy":
                $pages = array('about','benefits','therapies','reviews','policies','forms','blog','contact');
                break;
            case "Therapeutic_Yoga":
                $pages = array('bio','therapy','sessions','style','policies','blog','contact');
                break;
            case "Meditation_Classes":
                $pages = array('about','benefits','instruction','research','blog','contact');
                break;
            default:
                $pages = array();
        }

        foreach ($pages as $p) {
            $link = str_replace(" ", "_", $p);
            // highlight the category
            if ($link == $currentPage) {
                echo '<li class="currentPage">' . make_link($p, make_url($currentCat.'/'.$link)). '</li>';
            } else {
                echo make_list_item(make_link($p, make_url($currentCat.'/'.$link)));
            }
        }
        ?>
    </ul>

    <div id="loginToggle" onmouseup="Ydom.get('login').style.display = (Ydom.get('login').style.display=='none')? 'block' : 'none';"><?php if (isset($_SESSION['logged_in'])):?>Funcs<?php else:?>Login<?php endif;?></div>
    <div id="login" style="display:none">

        <?php if (isset($_SESSION['logged_in']) AND substr($_SERVER['REQUEST_URI'],-8) != 'log_out'): ?>
            <ul><?php 
                $adminFuncs = array('publish_feeds' => 'publish feeds',
                                'tag_entries' => 'tag entries',
                                'categorize_entries' => 'categorize entries');
                foreach ($adminFuncs as $link => $name) {
                    echo make_list_item(make_link($name, make_url('admin/'.$link)));
                }
                ?>
            </ul>
            <ul>
            <?php
                $loginFuncs = array('change_pw' => 'change password',
                                'login_woe' => 'login woe?',
                                'log_out' => 'log out');
                foreach ($loginFuncs as $link => $name) {
                    echo make_list_item(make_link($name, make_url('members/'.$link)));
                }
            ?>
            </ul>

        <?php else: ?>

            <ul>
                <form action="<?php echo make_url(substr($_SERVER['REQUEST_URI'], 1))?>" method="post">
                <li>Name: <input type="text" size="8" name="username" tabindex="1" /> </li>
                <li>Pass: <input type="password" size="7" name="password" tabindex="2" /> </li>
                <li><input type="submit" name="login_submit" value="Log in" tabindex="3" /> </li>
                </form>
            </ul>

        <?php endif; ?>

    </div><!-- end div#login -->