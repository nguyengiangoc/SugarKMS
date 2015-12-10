<?php
    if($objMember->removeMember($id)) {
        echo Helper::json(array('error' => false));
        
    } else {
        throw new Exception('Record can not be removed');
    }
    
?>