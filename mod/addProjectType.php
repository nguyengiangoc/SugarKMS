<?php
    require_once('../inc/config.php');
    $objProject = new Project();
    
    if($objProject->addProjectType($_POST)) {
        echo Helper::json(array('success' => true));
    } else {
        echo Helper::json(array('success' => false));
    }

?>