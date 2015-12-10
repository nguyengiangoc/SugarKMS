<?php 
    Login::restrictAdmin($this->objURL);
    $id = $this->objURL->get('id');
    $action = $this->objURL->get('action');
    $objProject = new Project();
    if(!empty($id)) {
        $project = $objProject->getProjectById($id);
        if(!empty($project)) {
            switch($action) {
                
                case 'added':
                $header =  $project['name'].' '.$project['project_time'].' :: Added';
                require_once('projects/added.php');
                break;
                
                case 'edit':
                $header =  $project['name'].' '.$project['project_time'].' :: Edit';
                require_once('projects/edit.php');
                break;
                
                default:
                $header =  $project['name'].' '.$project['project_time'].' :: View';
                require_once('projects/view.php');
                
                
            } 
        } else {
            $header = 'Project &amp; EXCO :: Error';
            require_once('projects/error.php');
        }
        
    } else {
        switch($action) {
            
            case 'add':
                $header = 'Project &amp; EXCO :: Add';
                require_once('projects/add.php');
            break;
            
            case 'add-failed':
                $header = 'Project &amp; EXCO :: Add Failed';
                require_once('projects/add-failed.php');
            break;

            default:
            $header = 'Project &amp; EXCO :: Search';
            require_once('projects/list.php');
        } 
    }
    
?>