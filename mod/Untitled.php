<?php
    require_once('../inc/config.php');
    $objDbase = new dbase();
    $objMember = new Member();
    $members = $objMember->getMembers();
    
    foreach ($members as $member) {
        $entity = $objMember->generateURLentity($member['name']);
        $sql = "UPDATE `members` SET `entity` = '".$entity."' WHERE `id` = ".$member['id'];
        $objDbase->query($sql);
    }
    
?>