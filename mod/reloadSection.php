<?php
    require_once('../inc/config.php');
    if(isset($_POST['plugin'])) {
        $plugin = $_POST['plugin'];
        $params = isset($_POST['params']) ? $_POST['params'] : '';
        echo Plugin::get($plugin, array('params' => $params));
    }
?>