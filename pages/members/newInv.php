<?php
    $objMember = new Member();
    $result = $objMember->newInvolvementInEdit($id);
    $error = $result['error'];
    $id = $result['id'];
    if($error) {
        echo Helper::json(array('error' => false, 'id' => $id));
    } else {
        throw new Exception('Can not create new involvement.');
    }
?>