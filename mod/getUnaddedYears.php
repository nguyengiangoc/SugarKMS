<?php
    require_once('../inc/config.php');
    if(isset($_POST['id'])) {
        $objProject = new Project();
        $years = $objProject->getUnaddedYears($_POST['id']);
        echo json_encode($years);
    } else {
        echo null;
    }
?>