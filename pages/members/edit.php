<?php       
    if($objMember->canEditMember($profile['id'], $id)) {
               
        $objForm = new Form();
        $objValid = new Validation($objForm);

        if($objForm->isPost('name')) {
            
            $objValid->_expected = array(
                'name', 'gender', 'day', 'month', 'year', 'personal_email', 'phone',
                'skype','facebook', 'high_school', 'grad_year_h', 'uni', 'grad_year_u'        
            );
            $objValid->_required = array(
                'name', 'gender'
            );
            
            $email = $objForm->getPost('personal_email');
            $phone = $objForm->getPost('phone');
            $facebook = $objForm->getPost('facebook');
            $high_school = $objForm->getPost('high_school');
            $grad_year_h = $objForm->getPost('grad_year_h');
            $uni = $objForm->getPost('uni');
            $grad_year_u = $objForm->getPost('grad_year_u');
            $email = $objForm->getPost('personal_email');
            
            if(!empty($member['personal_email']) && empty($email)) {  $objValid->_special = array('personal_email' => 'email'); }
            if(!empty($member['phone']) && empty($phone)) { $objValid->_required[] = 'phone'; }
            if(!empty($member['facebook']) && empty($facebook)) { $objValid->_required[] = 'facebook'; }
            if(!empty($member['high_school']) && empty($high_school)) { $objValid->_required[] = 'high_school'; }
            if(!empty($member['uni']) && empty($uni)) { $objValid->_required[] = 'uni'; }
            
            if(empty($high_school) && !empty($grad_year_h)) { $objValid->add2Errors('high_school'); }
            if(!empty($high_school) && empty($grad_year_h)) { $objValid->add2Errors('grad_year_h'); }
            
            $email = $objForm->getPost('personal_email');
            $facebook = $objForm->getPost('facebook');
            
            if($objMember->isDuplicateEmail($email, $id)) { $objValid->add2Errors('duplicate_email'); }
            
            if($objValid->isValid()) {
                if($objMember->updateMember($objValid->_post, $id)) {                       
                    Helper::redirect('/sugarkms/'.$this->objURL->getCurrent(array('action', 'id'), false, array('action', 'edited', 'id', $id)));
                } else {
                    Helper::redirect('/sugarkms/'.$this->objURL->getCurrent(array('action', 'id'), false, array('action', 'edit-failed', 'id', $id)));
                }
            }
        }
                
        if($objForm->isPost('action')) {
            $objUpload = new Upload();
            if($objUpload->upload(IMAGES_PATH)) {
                if(is_file(IMAGES_PATH.DS.$member['avatar'])) {
                    unlink(IMAGES_PATH.DS.$member['avatar']);
                }   
                $objMember->updateMember(array('avatar' => $objUpload->_names[0]), $id);
                //neu upload duoc anh thanh cong thi cho duong dan cua anh vao trong database
                Helper::redirect('/sugarkms/'.$this->objURL->getCurrent(array('action', 'id'), false, array('action', 'edited-upload', 'id', $id)));
                //tuc la lay phan param page=products, bo cai action=add, id=bao nhieu day
            } else {
                Helper::redirect('/sugarkms/'.$this->objURL->getCurrent(array('action', 'id'), false, array('action', 'edited-no-upload', 'id', $id)));
            }
        }
            


            
        require_once('_header.php'); 
?>
        <h1><?php echo $member['name']; ?> :: Edit
            <span class="h2rightlink"><a href="/sugarkms/members/id/<?php echo $id; ?>"><u>View this profile</u></a></span>
        </h1>
        <pre><?php //print_r($objValid->_post); ?></pre>
        <pre><?php //print_r($objValid->_errors); ?></pre>
        <pre><?php //print_r($objValid->_errorsMessages); ?></pre>
        <?php 
            //echo 'is email: ';
//            if(filter_var($objForm->getPost('personal_email'), FILTER_VALIDATE_EMAIL)) { echo 'true<br />'; } else { echo 'false<br />'; } 
//            echo 'key exist: ';
//            if(array_key_exists('personal_email', $objValid->_special)) { echo 'true<br />'; } else { echo 'false<br />'; }
//            echo 'check special: ';
//            if($objValid->checkSpecial('personal_email', $objForm->getPost('personal_email'), $objValid->_special['personal_email'])) { echo 'true<br />'; } else { echo 'false<br />'; } 
//            echo 'key: '.array_search('personal_email', $objValid->_errors).'<br />';
//            if(is_string(array_search('personal_email', $objValid->_errors))) { echo 'true<br />'; } else { echo 'false<br />'; }
//            echo 'validate message: ';
//            echo $objValid->wrapWarn($objValid->_message['personal_email']);
             
        ?>
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
            
            <br />
        </form>
        <br /><br /><br />
        <form action="" method="post">
            <h2>Personal Information:</h2>
            <table cellpadding="0" cellspacing="0" border="0" class="tbl_insert">
                <tr>
                    <th><label for="name">Name: *</label></th>
                    <td>
                        <input type="text" name="name" id="name" value="<?php echo $objForm->stickyText('name', $member['name']); ?>" class="fld" />
                        <?php echo $objValid->validate('name'); ?>
                    </td>
                <tr>
                    <th><label for="Gender">Gender: *</label></th>
                    <td><select name="gender" id="gender" class="gender">
                        <option value="Male" <?php echo $objForm->stickySelect('gender', 'Male', $member['gender']); ?>>Male</option>
                        <option value="Female" <?php echo $objForm->stickySelect('gender', 'Female', $member['gender']); ?>>Female</option>
                    </select></td>
                </tr>
                <tr>
                    <th>Date of Birth: 
                        <?php if(!empty($member['day']) && !empty($member['month']) && !empty($member['year'])) echo '*'; ?>
                    </th>
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
                    <th><label for="personal_email">Email: 
                        <?php if(!empty($member['personal_email'])) echo '*'; ?>
                    </label></th>
                    <td>
                        <input type="text" name="personal_email" id="personal_email" value="<?php echo $objForm->stickyText('personal_email', $member['personal_email']); ?>" class="fld" />
                        <?php echo $objValid->validate('personal_email'); echo $objValid->validate('duplicate_email');?>
                    </td>
                <tr>
                <tr>
                    <th><label for="phone">Phone: 
                        <?php if(!empty($member['phone'])) echo '*'; ?>
                    </label></th>
                    <td>
                        <input type="text" name="phone" id="phone" value="<?php echo $objForm->stickyText('phone', $member['phone']); ?>" class="fld" />
                        <?php echo $objValid->validate('phone'); ?>
                    </td>
                <tr>
                <tr>
                    <th><label for="skype">Skype: 
                        <?php if(!empty($member['skype'])) echo '*'; ?>
                    </label></th>
                    <td>
                        <input type="text" name="skype" id="skype" value="<?php echo $objForm->stickyText('skype', $member['skype']); ?>" class="fld" />
                        <?php echo $objValid->validate('skype'); ?>
                    </td>
                <tr>
                <tr>
                    <th><label for="facebook">Facebook Link: 
                        <?php if(!empty($member['facebook'])) echo '*'; ?>
                    </label></th>
                    <td>
                        <input type="text" name="facebook" id="facebook" value="<?php echo $objForm->stickyText('facebook', $member['facebook']); ?>" class="fld" />
                        <?php echo $objValid->validate('facebook'); ?>
                    </td>
                <tr>
                <tr>
                    <th><label for="high_school">High School: 
                        <?php if(!empty($member['high_school'])) echo '*'; ?>
                    </label></th>
                    <td>
                        <input type="text" name="high_school" id="high_school" class="fld"
                            value="<?php echo $objForm->stickyText('high_school', $member['high_school']); ?>" data-url="/sugarkms/mod/getSchoolList.php"  />
                        <?php echo $objValid->validate('high_school'); ?>
                    </td>
                <tr>
                <tr>
                    <th><label for="grad_year_h">Year of Graduation:
                        <?php if(!empty($member['grad_year_h'])) echo '*'; ?>
                    </label></th>
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
                    <th><label for="uni">University: 
                        <?php if(!empty($member['uni'])) echo '*'; ?>
                    </label></th>
                    <td>
                        <input type="text" name="uni" id="uni" class="fld"
                            value="<?php echo $objForm->stickyText('uni', $member['uni']); ?>" data-url="/sugarkms/mod/getSchoolList.php" />
                        <?php echo $objValid->validate('uni'); ?>
                    </td>
                <tr>
                <tr>
                    <th><label for="grad_year_u">Year of Graduation:
                        <?php if(!empty($member['grad_year_u'])) echo '*'; ?>
                    </label></th>
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
        
    
<?php 
        require_once('_footer.php');    
    } else {
        require_once('_header.php');
?>
    <h1>Access Denied</h1>
    You are not allowed to edit <?php $member['name'] ?>'s profile.
<?php
        require_once('_footer.php');
    }            

?>