<?php 
        $objForm = new Form();       
        $objValid = new Validation($objForm);
        $objSchool = new School();
        
        $result = null;
        
        $objValid->_expected = array(
            'name',
            'gender',
            'day',
            'month',
            'year',
            'personal_email',
            'phone',
            'skype',
            'facebook',
            'high_school',
            'grad_year_h',
            'uni',
            'grad_year_u'
        );
        
        
        
        $objValid->_required = array(
            'name',
            'gender',
            'personal_email'
        );
        
        //var_dump($objValid->_required);
        
        $objValid->_special = array(
            array('field' => 'personal_email', 'case_type' => 'check_is_email')
        );
        
        
        if($objForm->isPost('name')) {
            
            $high_school = $objForm->getPost('high_school');
            $grad_year_h = $objForm->getPost('grad_year_h');
            $uni = $objForm->getPost('uni');
            $grad_year_u = $objForm->getPost('grad_year_u');
            $email = $objForm->getPost('personal_email');
            
            if(empty($high_school) && !empty($grad_year_h)) { $objValid->add2Errors('high_school'); }
            if(!empty($high_school) && empty($grad_year_h)) { $objValid->add2Errors('grad_year_h'); }
            
            if(empty($uni) && !empty($grad_year_u)) { $objValid->add2Errors('uni'); }
            if(!empty($uni) && empty($grad_year_u)) { $objValid->add2Errors('grad_year_u'); }
            
            if($objMember->isDuplicateEmail($email)) { $objValid->add2Errors('duplicate_email'); }
            
            $params = $objValid->objForm->getPostArray($objValid->_expected);
            
            if(!empty($high_school)) {
                $get_high_school = $objSchool->getSchoolByName(trim($high_school), true);
                if(!empty($get_high_school)) {
                    $high_school_id = $get_high_school['id'];
                    $params['high_school'] = $high_school_id;
                } else {
                    $new_high_school = $objSchool->addHighSchool(array('name' => trim($high_school)));
                    if(!empty($new_high_school)) {
                        $high_school_id = $new_high_school['id'];
                        $params['high_school'] = $high_school_id;
                    } else {
                        $success = false;
                    }
                }
                
            }
            
            if(!empty($uni)) {
                $get_uni = $objSchool->getSchoolByName(trim($uni));
                if(!empty($get_uni)) {
                    $uni_id = $get_uni['id'];
                    $params['uni'] = $uni_id;
                } else {
                    $new_uni = $objSchool->addUni(array('name' => trim($uni)));
                    if(!empty($new_uni)) {
                        $uni_id = $new_uni['id'];
                        $params['uni'] = $uni_id;
                    } else {
                        $success = false;
                    }
                }
                $uni_id = $objSchool->getSchoolByName($uni)['id'];
                $params['uni'] = $uni_id;
            }
            
            $entity = $objMember->generateURLentity($objForm->getPost('name'));
            $params['entity'] = $entity;
            
            $params['password'] = Login::hash($objMember->_default_password);
            
            $params['member'] = 1;
            
            if($objValid->isValid()) {  
                if(isset($success) && $success == false) {
                    
                } else {
                    $return = $objMember->addMember($params);
                    if($return['result']) {
                        $new_id = $return['id'];
                        $success = true;
                    } else {
                        $success = false;
                    }
                }
                
                
            }
        }
        
        $header = 'Member :: Add';
        require_once('_header.php'); 
?>
    <h1><?php echo $header; ?></h1>
    <?php if(!isset($success)) { ?>
        <form action="" method="post" enctype="multipart/form-data">
            <h2>Personal Information</h2>
            <table cellpadding="0" cellspacing="0" border="0" class="tbl_insert">
                <tr>
                    <td><label for="name">Name <?php echo $objValid->displayRequired('name'); ?></label></td>
                    <td>
                        <input type="text" name="name" id="checkNameExists" value="<?php echo $objForm->stickyText('name'); ?>" class="fld" data-url="/sugarkms/mod/checkNameExists.php" />
                        <span id="nameMessage" class="red" ></span>
                        <?php echo $objValid->validate('name'); ?>
                    </td>
                </tr>
                <tr>
                    <td><label for="Gender">Gender <?php echo $objValid->displayRequired('gender'); ?></label></td>
                    <td><select name="gender" id="gender" class="gender">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                    </select></td>
                </tr>
                <tr>
                    <td>Date of Birth </td>
                    <td>
                        <select name="day" id="day" style="width:50px;border:1px solid #aaa;">
                            <option value=""></option>
                            <?php for($i=1;$i<=31;$i++) {     
                                echo '<option value="'.$i.'"'.$objForm->stickySelect('day', $i).'>'.$i.'</option>';
                            } ?>
                        </select>
                        <select name="month" id="month" style="width:50px;border:1px solid #aaa;">
                            <option value=""></option>
                            <?php for($i=1;$i<=12;$i++) {     
                                echo '<option value="'.$i.'"'.$objForm->stickySelect('month', $i).'">'.$i.'</option>';
                            } ?>
                        </select>
                        <select name="year" id="year" style="width:60px;border:1px solid #aaa;">
                            <option value=""></option>
                            <?php for($i=1985;$i<=date("Y") - 15;$i++) {
                                echo '<option value="'.$i.'"'.$objForm->stickySelect('year', $i).'>'.$i.'</option>';
                            } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="personal_email">Email <?php echo $objValid->displayRequired('personal_email'); ?></label></td>
                    <td>
                        <input type="text" name="personal_email" id="personal_email" value="<?php echo $objForm->stickyText('personal_email'); ?>" class="fld checkEmailExists" data-url="/sugarkms/mod/checkEmailExists.php"/>
                        <span id="emailMessage" class="red" ></span>
                        <?php echo $objValid->validate('personal_email'); echo $objValid->validate('duplicate_email');?>
                    </td>
                </tr>
                <tr>
                    <td><label for="phone">Phone <?php echo $objValid->displayRequired('phone'); ?></label></td>
                    <td>
                        <input type="text" name="phone" id="phone" value="<?php echo $objForm->stickyText('phone'); ?>" class="fld" />
                        <?php echo $objValid->validate('phone'); ?>
                    </td>
                </tr>
                <tr>
                    <td><label for="skype">Skype <?php echo $objValid->displayRequired('skype'); ?></label></td>
                    <td>
                        <input type="text" name="skype" id="skype" value="<?php echo $objForm->stickyText('skype'); ?>" class="fld" />
                        <?php echo $objValid->validate('skype'); ?>
                    </td>
                </tr>
                <tr>
                    <td><label for="facebook">Facebook Link <?php echo $objValid->displayRequired('facebook'); ?></label></td>
                    <td>
                        <input type="text" name="facebook" id="facebook" value="<?php echo $objForm->stickyText('facebook'); ?>" class="fld" />
                        <?php echo $objValid->validate('facebook'); ?>
                    </td>
                </tr>
                <tr>
                    <td><label for="high_school">High School <?php echo $objValid->displayRequired('high_school'); ?></label></td>
                    <td>
                        <input type="text" name="high_school" id="high_school" class="fld"
                            value="<?php echo $objForm->stickyText('high_school'); ?>" data-url="/sugarkms/mod/getSchoolList.php" data-high_school="1"/>
                        <?php echo $objValid->validate('high_school'); ?>
                    </td>
                </tr>
                <tr>
                    <td><label for="grad_year_h">Year of Graduation <?php echo $objValid->displayRequired('grad_year_h'); ?></label></td>
                    <td>
                        <select name="grad_year_h" id="grad_year_h" style="width:60px;border:1px solid #aaa;">
                            <option value=""></option>
                            <?php for($i=2000;$i<=2025;$i++) {
                                echo '<option value="'.$i.'"'.$objForm->stickySelect('grad_year_h', $i).'>'.$i.'</option>';
                            } ?>
                        </select>
                        <?php echo $objValid->validate('grad_year_h'); ?>
                    </td>
                </tr>
                <tr>
                    <td><label for="uni">University <?php echo $objValid->displayRequired('university'); ?></label></td>
                    <td>
                        <input type="text" name="uni" id="uni" class="fld"
                            value="<?php echo $objForm->stickyText('uni'); ?>" data-url="/sugarkms/mod/getSchoolList.php" data-high_school="0"/>
                        <?php echo $objValid->validate('uni'); ?>
                    </td>
                </tr>
                <tr>
                    <td><label for="grad_year_u"></label> Year of Graduation <?php echo $objValid->displayRequired('grad_year_u'); ?></td>
                    <td>
                        <select name="grad_year_u" id="grad_year_u" style="width:60px;border:1px solid #aaa;" >
                            <option value=""></option>
                            <?php for($i=2005;$i<=2030;$i++) {
                                echo '<option value="'.$i.'"'.$objForm->stickySelect('grad_year_u', $i).'>'.$i.'</option>';
                            } ?>
                        </select>
                        <?php echo $objValid->validate('grad_year_u'); ?>
                    </td>
                </tr>
            </table>
            <label for="btn" class="sbm sbm_blue fl_l" style="margin-bottom:15px;"><input type="submit" id="btn" class="btn" value="Add new member" />
            
        </form>

    <?php } else {
        if($success) { 
            $new_member = $objMember->getMemberById($new_id);
            ?>
            <p>The profile for <strong><?php echo $new_member['name']; ?></strong> has been <strong>added successfully</strong>.<br />
            <a href="<?php echo $this->objPage->generateURL('member', array('id'=> $new_id)); ?>">View this profile.</a><br />
            <a href="<?php echo $this->getCurrentURL(); ?>">Add another member.</a><br />
            <a href="<?php echo $this->objPage->generateURL('member'); ?>">Go to the list of members.</a><br />
        <?php } else { ?>
            <p><strong>There was a problem</strong> adding the profile.<br />Please contact administrator.<br />
            <a href="<?php echo $this->getCurrentURL(); ?>">Try adding again.</a><br />
            <a href="<?php echo $this->objPage->generateURL('member'); ?>">Go to the list of members.</a><br />
        <?php }
    } ?>
    

<?php 
        require_once('_footer.php');
    
?>