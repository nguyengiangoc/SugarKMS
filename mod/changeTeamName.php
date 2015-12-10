<?php
    require_once('../inc/config.php');
    if(isset($_POST['id']) && isset($_POST['name'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $objTeam = new Team();
        $team = $objTeam->getTeamById($id);
        $details = array('name' => $name);
        
        if(!empty($team)) {
            if($objTeam->updateTeam($details, $id)) {
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