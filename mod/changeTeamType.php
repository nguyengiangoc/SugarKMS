<?php
    require_once('../inc/config.php');
    if(isset($_POST['id']) && isset($_POST['value']) && isset($_POST['type'])) {
        $id = $_POST['id'];
        $value = $_POST['value'];
        $type = $_POST['type'];
        $objTeam = new Team();
        $team = $objTeam->getTeamById($id);
        
        
        
        if(!empty($team)) {
            switch($type) {
                case 'exco':
                if($value == 'Yes') { 
                    $update = 'No'; 
                    $order = '';
                }
                if($value == 'No') { 
                    $update = 'Yes'; 
                    $last = $objTeam->getLastPosition(true);
                    $order = intval($last) + 1;
                }
                
                $details = array('exco' => $update, 'exco_order' => $order);
                
                break;
                
                case 'project':
                if($value == 'Yes') { 
                    $update = 'No'; 
                    $order = '';
                }
                if($value == 'No') { 
                    $update = 'Yes';
                    $last = $objTeam->getLastPosition();
                    $order = intval($last) + 1;
                }
                
                $details = array('project' => $update, 'project_order' => $order);
                break;
                
                default:
                echo Helper::json(array('success' => false));
            }
            if($objTeam->updateTeam($details, $id)) {
                if($update == 'No') {
                    $result = $objTeam->resetOrder($type);
                    echo Helper::json(array('success' => true, 'reset' => 'yes', 'type' => $type, 'result' => $result));
                } else {
                    echo Helper::json(array('success' => true));
                }
                
                //echo Helper::json(array('success' => true));
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