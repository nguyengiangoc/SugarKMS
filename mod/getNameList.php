<?php
    require_once('../inc/config.php');
    if(isset($_POST['name'])) {
        $name = $_POST['name'];
        $objMember = new Member();
        if(isset($_POST['projectId'])) {
            $projectId = $_POST['projectId'];
            $nameList = $objMember->getNameList($name, $projectId);
            $result = array();
            if(!empty($nameList)) {
                foreach($nameList as $name) {
                    $result[] = array(
                                    'name' => $name['name'], 
                                    'id' => $name['id'], 
                                    'email' => $name['email'], 
                                    'in_project' => $name['in_project']);
                }
            }
        } else {
            $nameList = $objMember->getNameList($name);
            $result = array();
            if(!empty($nameList)) {
                foreach($nameList as $name) {
                    $result[] = array(
                                    'name' => $name['name'], 
                                    'id' => $name['id'],
                                    'email' => $name['personal_email']);
                }
            }
        }
        
        
        echo json_encode($result);
    } else {
        echo null;
    }
?>