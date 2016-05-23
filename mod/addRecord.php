<?php
    require_once('../inc/config.php');
    if(isset($_POST['object']) && isset($_POST['params'])) {
        $case = $_POST['object'];
        $params = $_POST['params'];
        $objDbase = new dbase(); 
        
        // HANDLE SERIALIZE ARRAY
        if(array_key_exists(0,$params)) {
            $temp = array();
            foreach($params as $array) {
                $temp[$array['name']] = $array['value'];
            }
            $params = $temp;
        }
        
        
        switch($case) {
            
            case 'position':
                $objPosition = new Position();
                $exco = $params['exco'];
                $project = $params['project'];
                if($exco == '0') { 
                    $exco_order = '';
                }
                if($exco == '1') { 
                    $last = $objPosition->getLastPosition(true);
                    $exco_order = intval($last) + 1;
                }
                
                if($project == '0') { 
                    $project_order = '';
                }
                if($project == '1') { 
                    $last = $objPosition->getLastPosition();
                    $project_order = intval($last) + 1;
                }
                $params['exco_order'] = $exco_order;
                $params['project_order'] = $project_order;
            break;
            
            case 'team':
                $objTeam = new Team();
                $exco = $params['exco'];
                $project = $params['project'];
                if($exco == '0') { 
                    $exco_order = '';
                }
                if($exco == '1') { 
                    $last = $objTeam->getLastPosition(true);
                    $exco_order = intval($last) + 1;
                }
                
                if($project == '0') { 
                    $project_order = '';
                }
                if($project == '1') { 
                    $last = $objTeam->getLastPosition();
                    $project_order = intval($last) + 1;
                }
                $params['exco_order'] = $exco_order;
                $params['project_order'] = $project_order;
            break;
            
            case 'page':
                $objPage = new Page();
                $group_id = $params['group_id'];
                $order = $objPage->getLastPosition($group_id) + 1;
                $params['order'] = $order;
            
            break;
            
            case 'involvement':
                $objProject = new Project();
                $project = $objProject->getProjectById($params['project_id']);
                $params['month_start'] = $project['month_start'];
                $params['year_start'] = $project['year_start'];
                $params['month_end'] = $project['month_end'];
                $params['year_end'] = $project['year_end'];
            break;
            
            case 'recruitment':
                //echo Helper::json('23:59:59 '.$params['deadline']);
                //echo Helper::json(strtotime('23:59:59 '.$params['deadline']));
                //$deadline = date('Y-m-d H:i:s', strtotime('23:59:59 '.$params['deadline']));
                //echo Helper::json($deadline);
                $deadline = date('Y-m-d', strtotime($params['deadline']));
                $params['deadline'] = $deadline;
                
                //check if an identical recruitment has been added
                $recruitment = $objDbase->get('recruitment', array('project_id' => $params['project_id'], 'team_id' => $params['team_id'], 'position_id' => $params['position_id'], 'deadline' => $params['deadline']));
                if(!empty($recruitment)) {
                    echo Helper::json(array('success' => false));
                    exit();
                }
            break;
            
            
            case 'question':
                $type = $params['type'];
                
                $objRecruitment = new Recruitment();
                
                if($type == 'dropdown' || $type == 'radio' || $type == 'checkbox') {
                    $choices = $params['choices'];
                    unset($params['choices']);
                    unset($params['max']);
                    
                } else {
                    if($params['max'] == 50) {
                        $params['min'] = 10;
                    } else {
                        $params['min'] = $params['max'] - 50;
                    }
                    
                }
                
                $order = $objRecruitment->getQuestionLastPosition($params['recruitment_id']) + 1;
                $params['order'] = $order;
                
            break;
            
            
        }
        
        //echo Helper::json(array('params' => $params));
        
        $result = $objDbase->add($case, $params);
               
        if($result['success']) {
            
            switch($case) {          
                                  
                case 'question':
                    $question_id = $result['id'];
                    if($type == 'dropdown' || $type == 'radio' || $type == 'checkbox') {
                        
                        
                        foreach($choices as $choice) {
                            
                            $order = $objRecruitment->getChoiceLastPosition($result['id']) + 1;
                            $objDbase->add('choice', array('question_id' => $result['id'], 'label' => $choice, 'order' => $order));
                        }
                        
                    } 
                    
                    
                    
                    
                break;
                
                
            }
            
            echo Helper::json(array('success' => true));
        } else {
            echo Helper::json(array('success' => false, 'case' => 1, 'params' => $params));
        }
          
        

    } else {
        echo Helper::json(array('success' => false, 'case' => 2));
    }
?>