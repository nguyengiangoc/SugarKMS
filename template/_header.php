
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
    <?php
        if(!empty($page_details)) {
        
            if($page_details['jquery']) {
                ?>
                <script src="/sugarkms/js/jquery.js" type="text/javascript"></script>
                <?php
            }
            if($page_details['jquery_ui']) {
                ?>
                <script src="/sugarkms/js/jquery-ui.js" type="text/javascript"></script> 
                <?php
            }
            if($page_details['livequery']) {
                ?>
                <script src="/sugarkms/js/jquery.livequery.js" type="text/javascript"></script>
                <?php
            }
            if($page_details['tablednd']) {
                ?>
                 <script src="/sugarkms/js/jquery.tablednd.0.7.min.js" type="text/javascript"></script> 
                <?php
            }
            if($page_details['common_js']) {
                ?>
                <script src="/sugarkms/js/common.js" type="text/javascript"></script>
                <?php
            }
            if($page_details['change_order']) {
                ?>
                <script src="/sugarkms/js/changeOrder.js" type="text/javascript"></script>
                <?php
            }
            if(!empty($page_details['js_file_directory'])) {
                $js_directory = str_replace(DS,'/',$page_details['js_file_directory']);
                ?>
                <script src="/sugarkms/js/<?php echo $js_directory; ?>" type="text/javascript"></script>
                <?php
            }
        }
        
    ?>
         
          <!--
<link rel="stylesheet" href="/sugarkms/css/tab.css" />
-->
<!--
      <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
      <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
-->
    
</head>

<body>
    <div id="pageParams" data-cpage="<?php echo $this->cPage; ?>" 
        <?php  
            if(!empty($this->cPage_params)) {
                foreach($this->cPage_params as $key => $value) {
                    echo 'data-'.$key.'="'.$value.'"';
                } 
            }
            
        ?>
         ></div>
    <div style="background:white;">
        <?php //echo $this->cPage_id; ?>
    </div>
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
                if(Login::isLogged()) {
                    echo '<div id="logged_as">Logged in as: <a href="'.$this->objPage->generateURL('member', array('id' => $current_user['id'])).'"><strong>'.$current_user['name'].'</strong></a> | <a href="/sugarkms/logout">Logout</a></div>';
                } 
            ?>
        </div>
    </div>
    <div id="outer">
        <div id="wrapper">
            <div id="left">
                <?php if(Login::isLogged()) { ?>
                    <h2>Directory Panels </h2>                     
                    <ul class="navigation">
                        <li><a href="<?php echo $this->objPage->generateURL('member'); ?>">Search Member</a></li>
                        <li><a href="<?php echo $this->objPage->generateURL('project'); ?>" >Search Project</a></li>
                        <li><a href="<?php echo $this->objPage->generateURL('exco'); ?>">Search EXCO</a></li>
                        <li><a href="<?php echo $this->objPage->generateURL('event'); ?>">Search Event</a></li>
                        <li><a href="<?php echo $this->objPage->generateURL('recruitment'); ?>">Search Recruitment</a></li>
                    </ul>
                    <div class="dev borderTop"></div>
                    
                    <h2>Admin Panels </h2>                      
                    <ul class="navigation">                        
                        
                        <li><a href="<?php echo $this->objPage->generateURL('member', array('action' => 'add')); ?>">Add New Member</a></li>
                        <li><a href="<?php echo $this->objPage->generateURL('project', array('action' => 'add')); ?>">Add New Project</a></li>
                        <li><a href="<?php echo $this->objPage->generateURL('exco', array('action' => 'add')); ?>">Add New EXCO</a></li>
                        <li><a href="<?php echo $this->objPage->generateURL('event', array('action' => 'add')); ?>">Add New Event</a></li>
                        <li><a href="<?php echo $this->objPage->generateURL('recruitment', array('action' => 'manage')); ?>">Manage Recruitment</a></li>
                        <li><a href="<?php echo $this->objPage->generateURL('application', array('action' => 'manage')); ?>">Manage Application</a></li>
                    </ul>
                     
                    <div class="dev borderTop"></div>
                    
                    <h2>Personal Panels </h2>
                    <ul class="navigation">            
                        <li><a href="<?php echo $this->objPage->generateURL('member', array('id' => $current_user['id'])); ?>">View Your Profile</a></li>            
                        <li><a href="<?php echo $this->objPage->generateURL('member', array('id' => $current_user['id'], 'action' => 'edit')); ?>">Edit Your Profile</a></li>
                        <li><a href="<?php echo $this->objPage->generateURL('member', array('id' => $current_user['id'], 'action' => 'edit')); ?>">Change Password</a></li>
                        <li><a href="https://www.facebook.com/BlueMoon.NS" target="_blank">Contact Admin</a></li>
                    </ul>
                    
                    <div class="dev borderTop"></div>
                    
                    <?php if($objMember->isAdmin($current_user['id'])) { ?>
                    <h2>Database Panels </h2>
                    <ul class="navigation">                        
                       
                        <li><a href="/sugarkms/database/high-schools">High Schools</a></li>
                        <li><a href="/sugarkms/database/universities">Univerisities</a></li>                       
                        <li><a href="/sugarkms/database/positions">Positions</a></li>
                        <li><a href="/sugarkms/database/teams">Teams</a></li>
                        <li><a href="/sugarkms/database/project-types">Project Types</a></li>
                        <li><a href="/sugarkms/database/pages">Pages</a></li>
                        <li><a href="/sugarkms/database/page-groups">Page Groups</a></li>
                        <br />
                        <li><a href="<?php echo $this->objPage->generateURL('database', array('table' => 'contact-access')); ?>">Manage Contact Access</a></li>
                        
                        
                        
                    </ul>
                    <?php } ?>
                    <div class="dev "></div>
                <?php } else { ?>
                    &nbsp;                
                <?php } ?>  
            </div>
            <div id="right">
                <div id="commonLinks"
                    data-add="/sugarkms/mod/addRecord.php"
                    data-remove="/sugarkms/mod/removeRecord.php"
                    data-get="/sugarkms/mod/getRecords.php"
                    data-change_field="/sugarkms/mod/changeField.php"
                    data-change_order="/sugarkms/mod/changeOrder.php"
                    data-toggle_yes_no="/sugarkms/mod/toggleYesNo.php"
                    data-reload_section="/sugarkms/mod/reloadSection.php"
                    data-get_position="/sugarkms/mod/getPositionsForType.php"
                    data-get_team="/sugarkms/mod/getTeamsForPosition.php"
                    
                ></div>
                