<?php
    require_once('../inc/config.php'); 
    if(isset($_POST['data'])  && isset($_POST['type'])) {
        $type = $_POST['type'];
        $objTeam = new Team();
        $errors = array();
        $data = explode('&', $_POST['data']);
        foreach($data as $order => $row) {
            $id = explode('=', $row)[1];
            
            $order++;
            switch($type) {
                case 'exco':
                $field = 'exco_order';
                $type_id = 5;
                break;
                
                case 'project':
                $field = 'project_order';
                $type_id = 6;
                break;
                
                default:
                echo Helper::json(array('error' => true));
            }
            if(!$objTeam->updateTeam(array($field => $order), $id)) {
                $errors[] = $id;
            }
            
        }
        if(empty($errors)) {
            $teams = $objTeam->getAllTeamsInProject($type_id);
            echo Helper::json(array('error' => false, 'teams' => $teams));
        } else {
            throw new Exception(count($errors).' records could not be updated');
        }
    } else {
        throw new Exception('Missing parameter');
    }
?>