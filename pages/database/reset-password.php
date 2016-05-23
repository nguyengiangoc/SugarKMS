<?php       
 
        
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
                $encoded = Login::hash($new);
                if($objMember->updateMember(array('password' => $encoded), $id)) {    
                    $success = true;
                } else {
                    $success = false;
                }
            }
        }
    
        require_once('_header.php'); 
?>
        <h1>Member :: Reset Pasword</h1>
        <?php if(!isset($success)) { ?>
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
        <?php } else { 
            if($success) { 
                $member = $objMember->getMemberById($id);
                ?>
                <p>The password for <?php echo $member['name']; ?>'s account has been <strong>changed successfully</strong>.<br />
                <a href="<?php echo $this->objPage->generateURL('member', array('id' => $id)); ?>">View this profile.</a><br />
                <a href="<?php echo $this->objPage->generateURL('member'); ?>">Go to the list of members.</a><br />
            <?php } else { ?>
                <p><strong>There was a problem</strong> saving the new password for <?php echo $member['name']; ?>'s account.<br />Please contact administrator.<br />
                <a href="<?php echo $this->getCurrentURL(); ?>">Go back and attempt edit again.</a><br />
            <?php }
        } ?>
        
<?php
        require_once('_footer.php');
           

?>