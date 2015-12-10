<?php 
    Login::restrictAdmin($this->objURL);
    $id = $this->objURL->get('id');
    $action = $this->objURL->get('action');
    $objMember = new Member();
    if(!empty($id)) {
        $member = $objMember->getMemberById($id);        
        if(!empty($member)) {
            switch($action) {
                
                case 'added':
                $header = $member['name'].' :: Added';
                require_once('members/added.php');
                break;
                               
                case 'edit':
                $header = $member['name'].' :: Edit';
                require_once('members/edit.php');
                break;
                            
                case 'edited':
                $header = $member['name'].' :: Edited';
                require_once('members/edited.php');
                break;
                    
                case 'edit-failed':
                $header = $member['name'].' :: Edit Failed';
                require_once('members/edit-failed.php');
                break;
                
                case 'edited-upload':
                $header = $member['name'].' :: Edited Upload';
                require_once('members/edited-upload.php');
                break;
                
                case 'edited-no-upload':
                $header = $member['name'].' :: Edited No Upload';
                require_once('members/edited-no-upload.php');
                break;
                                
                case 'password':
                $header = $member['name'].' :: Change Password';
                require_once('members/password-change.php');
                break;
                
                case 'password-changed':
                $header = $member['name'].' :: Password Changed';
                require_once('members/password-changed.php');
                break;
                
                case 'password-change-failed':
                $header = $member['name'].' :: Password Change Failed';
                require_once('members/password-change-failed.php');
                break;
                
                default:
                $header = $member['name'].' :: View';
                require_once('members/view.php');
                
            
            }
        } else {
            require_once('members/error.php');
        }
            
        
    } else {
        switch($action) {
            case 'add':
            $header = 'Member :: Add';
            require_once('members/add.php');
            break;
    
            case 'added-failed':
            $header = 'Member :: Password Changed';
            require_once('members/added-failed.php');
            break;
            
            case 'reset':
            $header = 'Member :: Password Reset';
            require_once('members/password-reset.php');
            break;
            
            default:
            $header = 'Member :: Search';
            require_once('members/list.php');            
        }
    }
    
?>