<?php
    require_once('../inc/config.php');
    if(isset($_POST['position_id']) && isset($_POST['team_id']) && isset($_POST['value'])) {
        $position_id = $_POST['position_id'];
        $team_id = $_POST['team_id'];
        $value = $_POST['value'];
        $objPosition = new Position();
        
        switch($value) {
            
            case 'Yes':
            if($objPosition->removePositionTeam($position_id, $team_id)) {
                echo Helper::json(array('success' => true));
            } else {
                echo Helper::json(array('success' => false));
            }
            break;
            
            case 'No':
            $result = $objPosition->getPositionTeam($position_id, $team_id);
            if(empty($result)) {
                if($objPosition->addPositionTeam(array('position_id' => $position_id, 'team_id' => $team_id))) {
                    echo Helper::json(array('success' => true));
                } else {
                    echo Helper::json(array('success' => false));
                }
                
            } else {
                echo Helper::json(array('success' => false));
            }
            break;
            
            default:
            echo Helper::json(array('success' => false));
        }
        

    } else {
        echo Helper::json(array('success' => false));
    }
?>