<?php       
    
        $districts = $objMember->getAllDistricts();
    
        $objForm = new Form();
        $objValid = new Validation($objForm);
        $objSchool = new School();
        
        $objValid->_expected = array(
                'name', 'gender', 'day', 'month', 'year', 'personal_email', 'phone', 'district',
                'skype','facebook', 'high_school', 'grad_year_h', 'uni', 'grad_year_u'        
        );
        $objValid->_required = array(
            'name', 'gender'
        );
        
        $objValid->_prefilled_fields = $member;
        
        //PERSONAL INFORMATION HANDLING
        if($objForm->isPost('name')) {
            
            
            
            $email = $objForm->getPost('personal_email');
            $phone = $objForm->getPost('phone');
            $facebook = $objForm->getPost('facebook');
            $high_school = $objForm->getPost('high_school');
            $grad_year_h = $objForm->getPost('grad_year_h');
            $uni = $objForm->getPost('uni');
            $grad_year_u = $objForm->getPost('grad_year_u');
            
            
            if(!empty($member['personal_email']) && empty($email)) {  
                $objValid->_special = array('personal_email' => 'email'); 
                $objValid->_special = array(
                    array('field' => 'personal_email', 'case_type' => 'check_is_email')
                );
            }
            if(!empty($member['phone']) && empty($phone)) { $objValid->_required[] = 'phone'; }
            if(!empty($member['facebook']) && empty($facebook)) { $objValid->_required[] = 'facebook'; }
            if(!empty($member['high_school']) && empty($high_school)) { $objValid->_required[] = 'high_school'; }
            if(!empty($member['uni']) && empty($uni)) { $objValid->_required[] = 'uni'; }
            
            if(empty($high_school) && !empty($grad_year_h)) { $objValid->add2Errors('high_school'); }
            if(!empty($high_school) && empty($grad_year_h)) { $objValid->add2Errors('grad_year_h'); }
            
            $email = $objForm->getPost('personal_email');
            $facebook = $objForm->getPost('facebook');
            
            if($objMember->isDuplicateEmail($email, $id)) { $objValid->add2Errors('duplicate_email'); }
            
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
            
            if($objValid->isValid()) {
                if($objMember->updateMember($params, $id)) {          
                    $success = true;
                } else {
                    $success = false;
                }
            }
        }
                
        
        //AVATAR HANDLING
        if($objForm->isPost('action')) {
            $objUpload = new Upload();
            if($objUpload->upload(IMAGES_PATH)) {
                if(is_file(IMAGES_PATH.DS.$member['avatar'])) {
                    unlink(IMAGES_PATH.DS.$member['avatar']);
                }   
                $objMember->updateMember(array('avatar' => $objUpload->_names[0]), $id);
                //neu upload duoc anh thanh cong thi cho duong dan cua anh vao trong database
                Helper::redirect($this->getCurrentURL());
                //tuc la lay phan param page=products, bo cai action=add, id=bao nhieu day
            } else {
                $upload = false;
            }
        }
        
        //PASSWORD CHANGE
        if($objForm->isPost('current')) {
            
            $reset = $objForm->getPost('reset');
            
            if(!empty($reset) && $reset == 1) {
                
                $encoded = Login::hash($objMember->_default_password);
                if($objMember->updateMember(array('password' => $encoded), $id)) {       
                    $success = true;
                } else {
                    $success = false;
                }
                
            } else {
                
                $objValid->_expected = array(
                    'current', 'new', 'retype'  
                );
                $objValid->_required = array(
                    'current', 'new', 'retype'  
                );
                
                $current = $objForm->getPost('current');
                $new = $objForm->getPost('new');
                $retype = $objForm->getPost('retype');
                
                $current_db = $objMember->getMemberById($current_user['id'])['password'];
                if($current != '' && $current != $current_db) {
                    $objValid->add2Errors('current_mismatch');
                }
                
                if($new != '' && $retype != '' && $new != $retype) {
                    $objValid->add2Errors('new_mismatch');
                }
                            
                if($objValid->isValid()) {
                    
                    $encoded = Login::hash($new);
                    if($objMember->updateMember(array('password' => $encoded), $id)) {       
                        $success = true;
                    } else {
                        $success = false;
                    }
                    
                }
            }
            
            
        }
            


        $header = $member['name'].' :: Edit';
        require_once('_header.php'); 
?>
        <h1><?php echo $header; ?>
            <span class="h2rightlink"><a href="<?php echo $this->objPage->generateURL('member', array('id' => $id)); ?>"><u>View this profile</u></a></span>
        </h1>
        <?php if(!isset($success)) { ?>
            <form action="" method="post" enctype="multipart/form-data">
                <h2>Avatar</h2>
                <div style="float:left;">
                    <img style="margin-left:10px; margin-right:25px;" class="avatar" src="/sugarkms/images/<?php echo (empty($member['avatar']) ? "no_avatar.jpg" : $member['avatar']); ?>" alt="avatar" />
                </div>
                Please select a JPEG or PNG image of 512Kb or smaller to use as avatar<br /> 
                <input type="hidden" name="action" value="image_upload" />
                <input type="hidden" name="MAX_FILE_SIZE" value="524288" />
                <input type="file" name="image" /><br /><br />
                <input type="submit" class="btn" style="background:url(/sugarkms/images/sprite.png) repeat-x 0 0;border:solid 1px #005b9a" name="upload" value="Upload new avatar" />
                <?php if(isset($upload) && !$upload) { echo '<span class="red">There was a problem changing the avatar. Please contact administrator.</span>'; } ?>
                <br />
            </form>
            <br /><br /><br />
            
            
            <table class="panelTable" style="width=100%;">
                <tr>
                    <td style="width:50%;">
                        <form action="" method="post" style="width:90%;">
                            <h2>Personal Information</h2>
                            <table cellpadding="0" cellspacing="0" border="0" class="tbl_insert">
                                <tr>
                                    <td>Name <?php echo $objValid->displayPrefilledRequired('name'); ?></td>
                                    <td>
                                        <input type="text" name="name" id="name" value="<?php echo $objForm->stickyText('name', $member['name']); ?>" class="fld" />
                                        <?php echo $objValid->validate('name'); ?>
                                    </td>
                                <tr>
                                    <td><label for="gender">Gender <?php echo $objValid->displayPrefilledRequired('gender'); ?></label></td>
                                    <td><select name="gender" id="gender" class="gender">
                                        <option value="Male" <?php echo $objForm->stickySelect('gender', 'Male', $member['gender']); ?>>Male</option>
                                        <option value="Female" <?php echo $objForm->stickySelect('gender', 'Female', $member['gender']); ?>>Female</option>
                                    </select></td>
                                </tr>
                                <tr>
                                    <td>Date of Birth 
                                        <?php if(!empty($member['day']) && !empty($member['month']) && !empty($member['year'])) echo '*'; ?>
                                    </td>
                                    <td>
                                        <select name="day" id="day" style="width:50px;border:1px solid #aaa;">
                                            <?php
                                                if($member['day'] == '') {
                                                    echo '<option value="" '.$objForm->stickySelect('day', '', $member['day']).'></option>';
                                                }
                                                for($i=1;$i<=31;$i++) {
                                                    echo '<option value="'.$i.'" '.$objForm->stickySelect('day', $i, $member['day']).'>'.$i.'</option>';
                                                } 
                                            ?>
                                        </select>
                                        
                                        <select name="month" id="month" style="width:50px;border:1px solid #aaa;">
                                            
                                            <?php 
                                                if($member['month'] == '') {
                                                    echo '<option value="" '.$objForm->stickySelect('day', '', $member['day']).'></option>';
                                                }
                                                for($i=1;$i<=12;$i++) {     
                                                    echo '<option value="'.$i.'" '.$objForm->stickySelect('month', $i, $member['month']).'">'.$i.'</option>';
                                                } 
                                            ?>
                                        </select>
                                        
                                        <select name="year" id="year" style="width:60px;border:1px solid #aaa;">
                                            
                                            <?php
                                                if($member['year'] == '') {
                                                    echo '<option value="" '.$objForm->stickySelect('day', '', $member['day']).'></option>';
                                                } 
                                                for($i=1985;$i<= date("Y") - 15;$i++) {
                                                    echo '<option value="'.$i.'" '.$objForm->stickySelect('year', $i, $member['year']).'>'.$i.'</option>';
                                                } 
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="personal_email">Email <?php echo $objValid->displayPrefilledRequired('personal_email'); ?>
                                    </label></td>
                                    <td>
                                        <input type="text" name="personal_email" id="personal_email" value="<?php echo $objForm->stickyText('personal_email', $member['personal_email']); ?>" class="fld" />
                                        <span id="emailMessage" class="red" ></span>
                                        <?php echo $objValid->validate('personal_email'); echo $objValid->validate('duplicate_email');?>
                                    </td>
                                <tr>
                                <tr>
                                    <td><label for="phone">Phone <?php echo $objValid->displayPrefilledRequired('phone'); ?>
                                    </label></td>
                                    <td>
                                        <input type="text" name="phone" id="phone" value="<?php echo $objForm->stickyText('phone', $member['phone']); ?>" class="fld" />
                                        <?php echo $objValid->validate('phone'); ?>
                                    </td>
                                <tr>
                                <tr>
                                    <td><label for="skype">Skype <?php echo $objValid->displayPrefilledRequired('skype'); ?>
                                    </label></td>
                                    <td>
                                        <input type="text" name="skype" id="skype" value="<?php echo $objForm->stickyText('skype', $member['skype']); ?>" class="fld" />
                                        <?php echo $objValid->validate('skype'); ?>
                                    </td>
                                <tr>
                                <tr>
                                    <td><label for="facebook">Facebook Link <?php echo $objValid->displayPrefilledRequired('facebook'); ?>
                                    </label></td>
                                    <td>
                                        <input type="text" name="facebook" id="facebook" value="<?php echo $objForm->stickyText('facebook', $member['facebook']); ?>" class="fld" />
                                        <?php echo $objValid->validate('facebook'); ?>
                                    </td>
                                <tr>
                                <tr>
                                    <td><label for="district">Residence District <?php echo $objValid->displayPrefilledRequired('district'); ?>
                                    </label></td>
                                    <td>
                                        <select name="district" id="district" style="width:150px;border:1px solid #aaa;">
                                            <option value="" <?php echo $objForm->stickySelect('district', ''); ?>></option>
                                            <?php foreach($districts as $district) {     
                                                echo '<option value="'.$district['id'].'"'.$objForm->stickySelect('district', $district['id'], $member['district']).'>'.$district['name'].'</option>';
                                            } ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="high_school">High School  <?php echo $objValid->displayPrefilledRequired('high_school'); ?>
                                    </label></td>
                                    <td>
                                        <input type="text" name="high_school" id="high_school" class="fld"
                                            value="<?php echo $objForm->stickyText('high_school', $member['high_school']); ?>" data-url="/sugarkms/mod/getSchoolList.php" data-high_school="1"  />
                                        <?php echo $objValid->validate('high_school'); ?>
                                    </td>
                                <tr>
                                <tr>
                                    <td><label for="grad_year_h">Year of Graduation <?php echo $objValid->displayPrefilledRequired('grad_year_h'); ?>
                                    </label></td>
                                    <td>
                                        <select name="grad_year_h" id="grad_year_h" style="width:60px;border:1px solid #aaa;">
                                            <?php  
                                                if($member['grad_year_h'] == '') {
                                                    echo '<option value=""'.$objForm->stickySelect('grad_year_h', '', $member['grad_year_h']).'></option>';
                                                }
                                                for($i=2000;$i<=2025;$i++) {
                                                    echo '<option value="'.$i.'"'.$objForm->stickySelect('grad_year_h', $i, $member['grad_year_h']).'>'.$i.'</option>';
                                                } 
                                            ?>
                                        </select>
                                        <?php echo $objValid->validate('grad_year_h'); ?>
                                    </td>
                                </tr>
                                <tr>
                                <tr>
                                    <td><label for="uni">University <?php echo $objValid->displayPrefilledRequired('uni'); ?>
                                    </label></td>
                                    <td>
                                        <input type="text" name="uni" id="uni" class="fld"
                                            value="<?php echo $objForm->stickyText('uni', $member['uni']); ?>" data-url="/sugarkms/mod/getSchoolList.php" data-high_school="0" />
                                        <?php echo $objValid->validate('uni'); ?>
                                    </td>
                                <tr>
                                <tr>
                                    <td><label for="grad_year_u">Year of Graduation <?php echo $objValid->displayPrefilledRequired('grad_year_u'); ?>
                                    </label></td>
                                    <td>
                                        <select name="grad_year_u" id="grad_year_u" style="width:60px;border:1px solid #aaa;" >
                                            
                                            <?php 
                                                if($member['grad_year_u'] == '') {
                                                    echo '<option value="" '.$objForm->stickySelect('grad_year_u', '', $member['grad_year_u']).'></option>';
                                                }
                                                for($i=2005;$i<=2030;$i++) {
                                                    echo '<option value="'.$i.'" '.$objForm->stickySelect('grad_year_u', $i, $member['grad_year_u']).'>'.$i.'</option>';
                                                } 
                                            ?>
                                        </select>
                                        <?php echo $objValid->validate('grad_year_u'); ?>
                                    </td>
                                </tr>
                            </table>
                            <label for="btn" class="sbm sbm_blue fl_l" style="length:40px;"><input type="submit" class="btn" value="Save changes" /></label>
                            <br />
                            <br />
                        </form>
                        <br />
                    </td>
                    
                    <td style="width:50%;">
                        <form action="" method="post">
                            <h2>Change Password</h2>
                            <table cellpadding="0" cellspacing="0" border="0" class="tbl_insert">
                                <tr>
                                    <td><label for="current">Current Password: *</label></td>
                                    <td>
                                        <input type="password" name="current" id="name" value="" class="fld" />
                                        <?php echo $objValid->validate('current'); ?>
                                        <?php echo $objValid->validate('current_mismatch'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="new">New Password: *</label></td>
                                    <td>
                                        <input type="password" name="new" id="name" value="" class="fld" />
                                        <?php echo $objValid->validate('new'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="retype">Retype New Password: *</label></td>
                                    <td>
                                        <input type="password" name="retype" id="name" value="" class="fld" />
                                        <?php echo $objValid->validate('retype'); ?>
                                        <?php echo $objValid->validate('new_mismatch'); ?>
                                    </td>
                                </tr>
                            </table>
                            <label for="btn" class="sbm sbm_blue fl_l" style="length:40px;margin-right:10px;"><input type="submit" class="btn" value="Save changes" /></label>
                            <?php if($objMember->isAdmin($current_user['id'])) { ?>
                                <input type="hidden" name="reset" value="1"/>
                                <label for="btn" class="sbm sbm_blue fl_l" style="length:40px;"><input type="submit" class="btn" value="Reset to default" /></label>
                            <?php } ?>
                            <br />
                            <br />
                        </form>                        
                    </td>
                </tr>
            </table>
            
            
        <?php } else {
        if($success) { 
            ?>
            <p>The profile for <?php echo $member['name']; ?> has been <strong>edited successfully</strong>.<br />
            <a href="<?php echo $this->objPage->generateURL('member', array('id'=> $id)); ?>">View this profile.</a><br />
            <a href="<?php echo $this->getCurrentURL(); ?>">Go back and make another edit.</a><br />
            <a href="<?php echo $this->objPage->generateURL('member'); ?>">Go to the list of members.</a><br />
        <?php } else { ?>
            <p><strong>There was a problem</strong> saving the changes for <?php echo $member['name']; ?>'s profile.<br />Please contact administrator.<br />
            <a href="<?php echo $this->getCurrentURL(); ?>">Go back and make another edit.</a><br />
            <a href="<?php echo $this->objPage->generateURL('member'); ?>">Go to the list of members.</a><br />
        <?php }
    } ?>
    
<?php 
        require_once('_footer.php');    
?>