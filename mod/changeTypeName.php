<?php
    require_once('../inc/config.php');
    if(isset($_POST['id']) && isset($_POST['name'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $objProject = new Project();
        $project = $objProject->getProjectById($id);
        $details = array('name' => $name);
        
        if(!empty($project)) {
            if($objProject->updateProjectType($details, $id)) {
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