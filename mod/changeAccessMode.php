<?php
    require_once('../inc/config.php');
    if(isset($_POST['id']) && isset($_POST['everyone'])) {
        $id = $_POST['id'];
        $everyone = $_POST['everyone'];
        $objPage = new Page();
        $action = $objPage->getPages(array('id' => $id));
        
        
        
        if(!empty($action)) {
            switch($everyone) {
                case 0:
                $mode = 1;                
                break;
                
                case 1:
                $mode = 0;
                break;
                
                default:
                echo Helper::json(array('success' => false));
            }
            if($objPage->updateAction(array('everyone' => $mode), $id)) {
                if($mode == 1) {
                    $objPage->removeAllPageAccess($id);
                }
               
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