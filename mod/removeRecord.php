<?php
    require_once('../inc/config.php');
    if(isset($_POST['object']) && (isset($_POST['id']) || isset($_POST['params']) )) {
        $id = $_POST['id'];        
        $case = $_POST['object'];
        $params = isset($_POST['params']) && is_array($_POST['params']) && !empty($_POST['params']) ? $_POST['params']: '';
        
        $objDbase = new dbase();
        
        switch($case) {
                
            case 'member':
                $objDbase->remove('involvement', '', array('member_id' => $id));
                //remove involvement of that member
            break;
            
            case 'school_abbr':
                //nothing to handle after
            break;
            
            case 'page':
                //remove access criteria of that page
                $objPage = new Page();
                $objDbase->remove('page_criteria','',array('page_id' => $id));
            break;
            
            case 'page_group':
                $objPage = new Page;
                $pages = $objPage->getPages(array('group_id' => $id));
                foreach($pages as $page) {
                    $objDbase->remove('page_criteria','',array('page_id' => $page['id']));
                    $objDbase->remove('page',$page['id']);
                }
            
            //remove all access criteria of pages in group
            //remove all pages in group                
            break;
            
            case 'page_criteria':
            //nothing to handle after
            break;
            
            case 'involvement':
            //nothing to handle after
            break;
            
            case 'team':
            //reset order
            $objTeam = new Team();
            $objTeam->resetOrder('exco');
            $objTeam->resetOrder('project');
            break;
            
            case 'question':
                $question = $objDbase->get('question', array('id' => $id))[0];
                if($question['type'] == 'radio' || $question['type'] == 'dropdown' || $question['type'] == 'checkbox') {
                    $choices = $objDbase->get('choice', array('question_id' => $id));
                    foreach ($choices as $choice) {
                        $objDbase->remove('choice', $choice['id']);
                    }
                } 
            break;
            
        }
        
        if($objDbase->remove($case, $id, $params)) {
            
            echo Helper::json(array('success' => true));
        } else {
            echo Helper::json(array('success' => false));
        }
    } else {
        echo Helper::json(array('success' => false));
    }
?>