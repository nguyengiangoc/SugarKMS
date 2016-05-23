<?php
    require_once('../inc/config.php'); 
    if(isset($_POST['params']) && isset($_POST['object'])) {
        $case = $_POST['object'];
        $errors = array();
        $params = explode('&', $_POST['params']);
        $objDbase = new dbase();
        foreach($params as $order => $row) {
            $field = str_replace('[]', '', explode('=', $row)[0]);
            $id = explode('=', $row)[1];
            $order++;
            if(!$objDbase->changeField($case, $id, array($field => $order))) {
                $errors[] = $id;
            }
            
        }
        if(empty($errors)) {
            echo Helper::json(array('error' => false));
        } else {
            throw new Exception(count($errors).' records could not be updated');
        }
    } else {
        throw new Exception('Missing parameter');
    }
?>