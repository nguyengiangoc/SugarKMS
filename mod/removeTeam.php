<?php
    require_once('../inc/config.php');
    if(isset($_POST['id'])) {
        $id = $_POST['id'];
        $objTeam = new Team();
        $team = $objTeam->getTeamById($id);
        if(!empty($team)) {
            if($objTeam->removeTeam($id)) {
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