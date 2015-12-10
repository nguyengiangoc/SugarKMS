<?php       
    if($objMember->canEditMember($profile['id'], $id)) {
        
        $objForm = new Form();
        $objValid = new Validation($objForm);

        if($objForm->isPost('current')) {
            
            $objValid->_expected = array(
                'current', 'new', 'retype'  
            );
            $objValid->_required = array(
                'current', 'new', 'retype'  
            );
            
            $current = $objForm->getPost('current');
            $new = $objForm->getPost('new');
            $retype = $objForm->getPost('retype');
            
            $current_db = $objMember->getMemberById($profile['id'])['password'];
            if($current != '' && $current != $current_db) {
                $objValid->add2Errors('current_mismatch');
            }
            
            if($new != '' && $retype != '' && $new != $retype) {
                $objValid->add2Errors('new_mismatch');
            }
                        
            if($objValid->isValid()) {
                if($objMember->updateMember(array('password' => $new), $id)) {                       
                    Helper::redirect('/sugarkms/'.$this->objURL->getCurrent(array('action', 'id'), false, array('action', 'password-changed', 'id', $id)));
                } else {
                    Helper::redirect('/sugarkms/'.$this->objURL->getCurrent(array('action', 'id'), false, array('action', 'password-change-failed', 'id', $id)));
                }
            }
        }
    
        require_once('_header.php'); 
?>
        <h1><?php echo $member['name']; ?> :: Change Pasword</h1>
        <form action="" method="post">
            <table cellpadding="0" cellspacing="0" border="0" class="tbl_insert">
                <tr>
                    <th><label for="current">Current Password: *</label></th>
                    <td>
                        <input type="password" name="current" id="name" value="" class="fld" />
                        <?php echo $objValid->validate('current'); ?>
                        <?php echo $objValid->validate('current_mismatch'); ?>
                    </td>
                </tr>
                <tr>
                    <th><label for="new">New Password: *</label></th>
                    <td>
                        <input type="password" name="new" id="name" value="" class="fld" />
                        <?php echo $objValid->validate('new'); ?>
                    </td>
                </tr>
                <tr>
                    <th><label for="retype">Retype New Password: *</label></th>
                    <td>
                        <input type="password" name="retype" id="name" value="" class="fld" />
                        <?php echo $objValid->validate('retype'); ?>
                        <?php echo $objValid->validate('new_mismatch'); ?>
                    </td>
                </tr>
            </table>
            <label for="btn" class="sbm sbm_blue fl_l" style="length:40px;"><input type="submit" class="btn" value="Save changes" /></label>
            <br />
            <br />
        </form>
        <br />
        
    
<?php 
        require_once('_footer.php');    
    } else {
        require_once('_header.php');
?>
    <h1>Access Denied</h1>
    You are not allowed to change <?php $member['name'] ?>'s password.
<?php
        require_once('_footer.php');
    }            

?>