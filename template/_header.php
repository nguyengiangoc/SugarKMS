
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>
        <?php 
            $objMember = new Member();
            if(!isset($header)) { $header = 'Sugar KMS'; }
            echo $header;
        ?>
    </title>
    <meta name="description" content="Sugar KMS" />
    <meta name="keywords" content="Sugar KMS" />
    <meta http-equiv="imagetoolbar" content="no" />
    <link href="/sugarkms/css/core.css" rel="stylesheet" type="text/css" />
          <link href="/sugarkms/css/autocomplete.css" rel="stylesheet" />
         
          <!--
<link rel="stylesheet" href="/sugarkms/css/tab.css" />
-->
<!--
      <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
      <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
-->
</head>

<body>
    <div id="header">
        <div id="header_in">
            <h5><a href="/sugarkms">SugarKMS
            <?php
                //if(isset($_COOKIE['SugarKMSCookie'])) {
//                    echo '1 ';
//                    parse_str($_COOKIE['SugarKMSCookie']);
//                } else {
//                    echo '0 ';
//                }
//                print_r($_SESSION);
//                print_r($_COOKIE);
//                parse_str($_COOKIE['SugarKMSCookie']);
//                $objMember = new Member();
//                $result = $objMember->getMemberByHash($hash);
//                if(!empty($result)) {
//                    echo 'db '.$result['time'].' ';
//                }
//                echo '30 days';
//                echo time() + 2592000;
//                echo '  ';
//                if($result['time'] < time(+2592000)) { echo 1; } else { echo 0;}
//                echo '  ';
//                echo 'id '.$result['id'];
            ?>
            </a></h5>
            <?php 
                if(Login::isLogged(Login::$_login_admin)) {
                    echo '<div id="logged_as">Logged in as: <strong>'.$profile['name'].'</strong> | <a href="/sugarkms/logout">Logout</a></div>';
                } else {
                    echo '<div id="logged_as"><a href="/sugarkms/">Login</a></div>';   
                }
            ?>
        </div>
    </div>
    <div id="outer">
        <div id="wrapper">
            <div id="left">
                <?php if(Login::isLogged(Login::$_login_admin)) { ?>
                    <h2>Directory Panels </h2>                     
                    <ul class="navigation">
                        <li><a href="/sugarkms/members" <?php //echo $this->objNavigation->active('members'); ?>>Search Member</a></li>
                        <li><a href="/sugarkms/projects" <?php //echo $this->objNavigation->active('projects'); ?>>Search Project &amp; EXCO</a></li>
                        
                    </ul>
                    <div class="dev br_td"></div>
                    <h2>Admin Panels </h2>   
                    <ul class="navigation">                        
                        <li><a href="/sugarkms/members/action/add">Add New Member</a></li>
                        <li><a href="/sugarkms/projects/action/add">Add New Project &amp; EXCO</a></li>
                        <?php if($objMember->isAdmin($profile['id'])) { ?>
                        <li><a href="/sugarkms/positions">Manage Positions</a></li>
                        <li><a href="/sugarkms/teams">Manage Teams</a></li>
                        <li><a href="/sugarkms/project_types">Manage Project Types</a></li>
                        <li><a href="/sugarkms/members/action/reset">Reset Password</a></li>
                        <?php } ?>
                    </ul>
                    <div class="dev br_td"></div>
                    <h2>Personal Panels </h2>
                    <ul class="navigation">                        
                        <li><a href="/sugarkms/members/id/<?php echo $profile['id']; ?>/action/edit">Edit Personal Profile</a></li>
                        <li><a href="/sugarkms/members/action/password/id/<?php echo $profile['id']; ?>">Change Password</a></li>
                        <li><a href="https://www.facebook.com/BlueMoon.NS" target="_blank">Contact Admin</a></li>
                    </ul>
                <?php } else { ?>
                    &nbsp;                
                <?php } ?>
            </div>
            <div id="right">