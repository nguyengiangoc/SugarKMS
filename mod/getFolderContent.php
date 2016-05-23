<?php
    require_once('../inc/config.php');
    if(isset($_POST['folder']) && isset($_POST['type'])) {
        switch($_POST['type']) {
            case 'js':
            $folder = ROOT_PATH.DS.'js'.DS.$_POST['folder'];      
            break;
            
            case 'php':
            $folder = ROOT_PATH.DS.'pages'.DS.$_POST['folder'];      
            break;
        }
        $content = scandir($folder);     
        echo json_encode($content);
    } else {
        echo null;
    }
?>