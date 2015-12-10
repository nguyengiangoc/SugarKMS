<?php
    require_once('../inc/config.php');
    if(isset($_POST['name']) && isset($_POST['exco']) && isset($_POST['project'])) {
        $name = $_POST['name'];
        $exco = $_POST['exco'];
        $project = $_POST['project'];
        $objTeam = new Team();
        
        if($exco == 'No') { 
            $exco_order = '';
        }
        if($exco == 'Yes') { 
            $last = $objTeam->getLastPosition(true);
            $exco_order = intval($last) + 1;
        }
        
        if($project == 'No') { 
            $project_order = '';
        }
        if($project == 'Yes') { 
            $last = $objTeam->getLastPosition(true);
            $project_order = intval($last) + 1;
        }
                
        $details = array('name' => $name, 'exco' => $exco, 'exco_order' => $exco_order, 'project' => $project, 'project_order' => $project_order);
        //echo Helper::json($details);
        
        if($objTeam->addTeam($details)) {
            echo Helper::json(array('success' => true));
        } else {
            echo Helper::json(array('success' => false));
        }

    } else {
        echo Helper::json(array('success' => false));
    }
?>