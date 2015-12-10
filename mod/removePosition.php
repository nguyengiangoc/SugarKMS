<?php
    require_once('../inc/config.php');
    if(isset($_POST['id'])) {
        $id = $_POST['id'];
        $objPosition = new Position();
        $position = $objPosition->getPositionById($id);
        if($objPosition->removePosition($id)) {
            echo Helper::json(array('success' => true));
        } else {
            echo Helper::json(array('success' => false));
        }
    } else {
        echo Helper::json(array('success' => false));
    }
?>