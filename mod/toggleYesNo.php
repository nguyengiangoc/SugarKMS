<?php
    require_once('../inc/config.php');
    if(isset($_POST['id'], $_POST['object'], $_POST['field'], $_POST['value'])) {
        $id = $_POST['id'];
        $case = $_POST['object'];
        $field = $_POST['field'];
        $value = $_POST['value'];
        if($value == 0) { 
            $new_value = 1; 
            
            if($case == 'page' && $field == 'default') {
                
                $objDbase = new dbase();
                $objPage = new Page();
                $page = $objPage->getPages(array('id' => $id))[0];
                
                $pages = $objPage->getPages(array('default' => 1, 'group_id' => $page['group_id']));
                
                foreach($pages as $page) {
                    $objDbase->changeField('page',$page['id'],array('default' => 0));
                }
            }
            
        }
        if($value == 1) {
            $new_value = 0;
        }
        $objDbase = new dbase;
        $params = array($field => $new_value);
        
        if($objDbase->changeField($case, $id, array($field => $new_value))) {
            echo Helper::json(array('success' => true));
        } else {
            echo Helper::json(array('success' => false));
        }

    } else {
        echo Helper::json(array('success' => false));
    }
?>