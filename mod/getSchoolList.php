<?php
    require_once('../inc/config.php');
    if(isset($_POST['high_school'])) {
        $objSchool = new School();
        $schoolList = $objSchool->getHighSchoolList($_POST['high_school']);
        echo json_encode($schoolList);
    } else if(isset($_POST['uni'])) {
        $objSchool = new School();
        $schoolList = $objSchool->getUniversityList($_POST['uni']);
        echo json_encode($schoolList);
    } else {
        echo null;
    }
?>