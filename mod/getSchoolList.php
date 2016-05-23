<?php
    require_once('../inc/config.php');
    if(isset($_POST['term']) && isset($_POST['high_school'])) {
        $objSchool = new School();
        if($_POST['high_school'] == 1) {
            $schoolList = $objSchool->getHighSchoolList($_POST['term']);
        } else if($_POST['high_school'] == 0) {
            $schoolList = $objSchool->getUniversityList($_POST['term']);
        }
        echo json_encode($schoolList);
    } else {
        echo null;
    }
?>