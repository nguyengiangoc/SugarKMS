<?php
    require_once('../inc/config.php');
    if(isset($_POST['position_id']) && isset($_POST['project_type_id'])) {
        $objTeam = new Team();
        $teams = $objTeam->getTeamsForPosition($_POST['position_id'], $_POST['project_type_id']);
        $result = array();
        foreach($teams as $team) {
            $result[] = $team;
         
                            
        } 
        header('Content-type: application/json');
                       
        echo json_encode($result);
    }

?>