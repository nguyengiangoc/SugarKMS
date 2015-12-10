<?php
    require_once('../inc/config.php');
    if(isset($_POST['name']) && isset($_POST['exco']) && isset($_POST['project'])) {
        $name = $_POST['name'];
        $exco = $_POST['exco'];
        $project = $_POST['project'];
        $objPosition = new Position();
                        
        $details = array('name' => $name, 'exco' => $exco, 'project' => $project);
        //echo Helper::json($details);
        
        if($objPosition->addPosition($details)) {
            echo Helper::json(array('success' => true));
        } else {
            echo Helper::json(array('success' => false));
        }

    } else {
        echo Helper::json(array('success' => false));
    }
?>