<?php
    require_once('../inc/config.php');
    if(isset($_POST['id']) && isset($_POST['name'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $objPosition = new Position();
        $position = $objPosition->getPositionById($id);
        $details = array('name' => $name);
        
        if(!empty($position)) {
            if($objPosition->updatePosition($details, $id)) {
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