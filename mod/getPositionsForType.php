<?php
    require_once('../inc/config.php');
    if(isset($_POST['type'])) {
        $type = $_POST['type'];
        $objPosition = new Position();
        $positions = $objPosition->getAllPositionsInProject($type);       
        if(empty($group)) {
            echo Helper::json(array('success' => true, 'positions' => $positions));
        } else {
            echo Helper::json(array('success' => false));
        }
    } else {
        echo Helper::json(array('success' => false));
    }
?>