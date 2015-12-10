<?php       
    if($objMember->isAdmin($profile['id'])) {
        
        $objForm = new Form();
        $objValid = new Validation($objForm);

        if($objForm->isPost('name')) {
            
            $objValid->_expected = array(
                'name', 'new', 'retype'  
            );
            $objValid->_required = array(
                'name', 'new', 'retype'  
            );
            
            $new = $objForm->getPost('new');
            $retype = $objForm->getPost('retype');
            
            if($new != '' && $retype != '' && $new != $retype) {
                $objValid->add2Errors('new_mismatch');
            }
            $id = $objForm->getPost('id');
                        
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
        <h1>Member :: Reset Pasword</h1>
        <form action="" method="post">
            <table cellpadding="0" cellspacing="0" border="0" class="tbl_insert">
                <tr>
                    <th><label for="current">Member Name: *</label></th>
                    <td>
                        <input type="text" name="name" id="autocomplete" value="<?php echo $objForm->stickyText('name'); ?>" class="fld" data-url="/sugarkms/mod/getNameList.php"/>
                        <input type="hidden" name="id" id="memberId" value="" />
                        <input type="hidden" name="verified" id="verified" value="<?php echo $objForm->stickyText('verified'); ?>" />
                        <?php echo $objValid->validate('name'); ?>
                        <span style="vertical-align:middle;
                            <?php if(!(isset($_POST['verified']) && $_POST['verified'] == 'true')) { ?>visibility:hidden;<?php } ?>"
                            id="checkIcon"><img src="/sugarkms/images/icon_check.png" style="width:15px;height:15px;" /></span>
                    </td>
                </tr>
                <tr>
                    <th><label for="new">New Password: *</label></th>
                    <td>
                        <input type="password" name="new" id="new" value="" class="fld" 
                            <?php if(!(isset($_POST['verified']) && $_POST['verified'] == 'true')) { ?>disabled=""<?php } ?>
                         />
                        <?php echo $objValid->validate('new'); ?>
                    </td>
                </tr>
                <tr>
                    <th><label for="retype">Retype New Password: *</label></th>
                    <td>
                        <input type="password" name="retype" id="retype" value="" class="fld" 
                            <?php if(!(isset($_POST['verified']) && $_POST['verified'] == 'true')) { ?>disabled=""<?php } ?>
                        />
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
    You are not allowed to get access to this page.
<?php
        require_once('_footer.php');
    }            

?>