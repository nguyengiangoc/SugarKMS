<?php
    require_once('../inc/config.php');
    $objMember = new Member();
    $result = $objMember->checkNameExists($_POST['name']);
    echo json_encode($result);
?>