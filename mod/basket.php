<?php
    require_once('../inc/config.php');
    if(isset($_POST['job']) && isset($_POST['id'])) {
        $out = array();
        $job = $_POST['job'];
        $id = $_POST['id'];
        $objCatalogue = new Catalogue();
        $product = $objCatalogue->getProduct($id);
        
        if(!empty($product)) {
            switch($job) {
                case 0:
                Session::removeItem($id);
                $out['job'] = 1;
                //$out = 1;
                break;
                
                case 1:
                Session::setItem($id);
                $out['job'] = 0;
                //$out = 0;
                break;
            }
            
            //echo json_encode($out);
            echo Helper::json($out) ;
        }
    }
?>