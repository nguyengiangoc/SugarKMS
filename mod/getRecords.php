<?php
    require_once('../inc/config.php');
    if(isset($_POST['object'])) {
        $case = $_POST['object'];
        
        $params = isset($_POST['params']) ? $_POST['params'] : '';
        $objDbase = new dbase();     
        $result = $objDbase->get($case, $params);   
        if(!empty($result)) {
            echo Helper::json(array('success' => true, 'result' => $result));
        } else {
            echo Helper::json(array('success' => false, 'case' => 1));
        }
          
        

    } else {
        echo Helper::json(array('success' => false, 'case' => 2));
    }
?>