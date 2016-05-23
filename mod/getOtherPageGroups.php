<?php
    require_once('../inc/config.php');
    if(isset($_POST['group_id'])) {
        $name = $_POST['group_id'];
        $objPage = new Page();
        $result = $objPage->getOtherGroups($group_id);
        if(!empty($result)) {
            echo json_encode(array('success' => 1, 'results' => $result));
        } else {
            echo json_encode(array('success' => 0));
        }
    } else {
        echo json_encode(array('success' => 0));
    }
?>