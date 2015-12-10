<?php
    require_once('../inc/config.php');
    $objMember = new Member();
    $result = $objMember->addMemberToProject($_POST);
    echo json_encode($result);
?>