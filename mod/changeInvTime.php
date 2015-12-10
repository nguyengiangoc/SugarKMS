<?php
    require_once('../inc/config.php');
    if(isset($_POST['id']) && isset($_POST['month_start']) && isset($_POST['year_start']) && isset($_POST['month_end']) && isset($_POST['year_end'])) {
        $out = array();
        $id = $_POST['id'];
        $month_start = $_POST['month_start'];
        $year_start = $_POST['year_start'];
        $month_end = $_POST['month_end'];
        $year_end = $_POST['year_end'];
        $objMember = new Member();
        $involvement = $objMember->getInvolvement($id);
        $details = array('month_start' => $month_start, 'year_start' => $year_start, 'month_end' => $month_end, 'year_end' => $year_end);
        
        if(!empty($involvement)) {
            if($objMember->updateInvolvement($id, $details)) {
                echo Helper::json(array('success' => true));
            } else {
                echo Helper::json(array('success' => false));
            }
        } else {
            echo Helper::json(array('success' => false));
        }
    } else {
        echo Helper::json(array('success' => false));
    }
?>