<?php
    require_once('../inc/config.php');
    if(isset($_POST['id']) && isset($_POST['object']) && isset($_POST['params'])) {
        $id = $_POST['id'];
        $case = $_POST['object'];
        $params = $_POST['params'];
        $objDbase = new dbase;
        
        // HANDLE SERIALIZE ARRAY
        if(array_key_exists(0,$params)) {
            $temp = array();
            foreach($params as $array) {
                if(isset($array['value'])) {
                    $temp[$array['name']] = $array['value'];
                } else {
                    $temp[$array['name']] = '';
                }
                
            }
            $params = $temp;
        }
        
        switch($case) {
            
            case 'recruitment':
                if(array_key_exists('deadline', $params)) {
                    $deadline = date('Y-m-d', strtotime($params['deadline']));
                    $params['deadline'] = $deadline;
                }
                
            break;
            
            
            case 'question':
                if($params['type'] == 'radio' || $params['type'] == 'checkbox' || $params['type'] == 'dropdown') {
                    foreach($params['existing_choices'] as $choice) {
                        $objDbase->changeField('choice', $choice['id'], array('label' => $choice['label']));
                    }
                    
                    unset($params['existing_choices']);
                    
                    if(!empty($params['new_choices'])) {
                        foreach($params['new_choices'] as $choice) {
                            $objRecruitment = new Recruitment();
                            $order = $objRecruitment->getChoiceLastPosition($id) + 1;
                            $objDbase->add('choice', array('label' => $choice, 'question_id' => $id, 'order' => $order));
                        }
                        
                        
                            
                    }
                    
                    unset($params['new_choices']);
                    
                    
                } else {
                    if($params['max'] == 50) {
                        $params['min'] = 10;
                    } else {
                        $params['min'] = $params['max'] - 50;
                    }
                    
                }
                
            break;
        }
        
        if($objDbase->changeField($case, $id, $params)) {
            echo Helper::json(array('success' => true));
        } else {
            echo Helper::json(array('success' => false));
        }

    } else {
        echo Helper::json(array('success' => false));
    }
?>