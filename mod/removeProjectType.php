<?php
    require_once('../inc/config.php');
    if(isset($_POST['id'])) {
        $id = $_POST['id'];
        $objProject = new Project();
        $type = $objProject->getProjectById($id);
        if(!empty($type)) {
            if($objProject->removeProjectType($id)) {
                echo Helper::json(array('success' => true));
            } else {
                echo Helper::json(array('success' => false));
            }
        } else {
            echo Helper::json(array('success' => false));
        }
        
    } else {
        echo Helper::json(array('success' => false));
    }
?>