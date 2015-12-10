<?php
    require_once('../inc/config.php');
    $objProject = new Project();
    $projects = $objProject->getAllProjects();
    /*$out = array();
    foreach($projects as $project) {
        $out[] = '<option value="'.$project['id'].'">'.$project['name'].'</option>';
    } 
    echo Helper::json($out);*/
    echo json_encode($projects);
?>