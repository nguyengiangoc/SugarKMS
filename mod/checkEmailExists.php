<?php
    require_once('../inc/config.php');
    $objMember = new Member();
    if(isset($_POST['id'])) {
        $id = $_POST['id'];
    } else {
        $id = '';
    }
    $result = $objMember->isDuplicateEmail($_POST['email'], $id);
    echo json_encode(array('result' => $result));
?>