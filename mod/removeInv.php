<?php
    require_once('../inc/config.php');
    if(isset($_POST['id'])) {
        $id = $_POST['id'];
        $objMember = new Member();
        if($objMember->removeInvolvement($id)) {
            echo Helper::json(array('error' => false));
        } else {
            throw new Exception('Can not remove new involvement.');
        }
    }
    
?>