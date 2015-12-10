<?php
    require_once('../inc/config.php');
    if(isset($_POST['id']) && isset($_POST['value']) && isset($_POST['type'])) {
        $id = $_POST['id'];
        $value = $_POST['value'];
        $type = $_POST['type'];
        $objPosition = new Position();
        $position = $objPosition->getPositionById($id);
        
        if(!empty($position)) {
            switch($type) {
                
                case 'exco':
                if($value == 'Yes') { 
                    $update = 'No'; 
                }
                if($value == 'No') { 
                    $update = 'Yes'; 
                }
                $details = array('exco' => $update);
                break;
                
                case 'project':
                if($value == 'Yes') { 
                    $update = 'No'; 
                }
                if($value == 'No') { 
                    $update = 'Yes';
                }
                $details = array('project' => $update);
                break;
                
                default:
                echo Helper::json(array('success' => false));
            }
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