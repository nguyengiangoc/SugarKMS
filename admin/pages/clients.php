<?php
    Login::restrictAdmin();
    $action = $this->objURL->get('action');
    switch($action) {
        case 'edit':
        require_once('clients/edit.php');
        break;
        case 'edited':
        require_once('clients/edited.php');
        break;
        case 'edited-failed':
        require_once('clients/edited-failed.php');
        break;
        case 'remove':
        require_once('clients/remove.php');
        break;
        default:
        require_once('clients/list.php');
    }
?>