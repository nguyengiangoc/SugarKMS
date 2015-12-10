<?php       
    if($objMember->canEditMember($profile['id'], $id)) {
        
        $objProject = new Project();
        $projects = $objProject->getAllProjects();
        
        $objTeam = new Team();
        $teams = $objTeam->getAllTeams();
        
        $objPosition = new Position();
        $positions = $objPosition->getAllPositions();
        
        $involvements = $objMember->getAllInvolvements($id);
        
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
            $objValid->_special = array('personal_email' => 'email');
            
            $high_school = $objForm->getPost('high_school');
            $grad_year_h = $objForm->getPost('grad_year_h');
            $uni = $objForm->getPost('uni');
            $grad_year_u = $objForm->getPost('grad_year_u');
            $email = $objForm->getPost('personal_email');
            
            if(empty($high_school) && !empty($grad_year_h)) { $objValid->add2Errors('high_school'); }
            if(!empty($high_school) && empty($grad_year_h)) { $objValid->add2Errors('grad_year_h'); }
            
            $email = $objForm->getPost('personal_email');
            $facebook = $objForm->getPost('facebook');
            
            if($objMember->isDuplicateEmail($email, $id)) { $objValid->add2Errors('duplicate_email'); }
            
            if($objValid->isValid()) {
                if($objMember->updateMember($objValid->_post, $id)) {                       
                    Helper::redirect('/sugarkms/'.$this->objURL->getCurrent(array('action', 'id'), false, array('action', 'edited', 'id', $id)));
                } else {
                    Helper::redirect('/sugarkms/'.$this->objURL->getCurrent(array('action', 'id'), false, array('action', 'edited-failed', 'id', $id)));
                }
            }
        }
        
        if($objForm->isPost('involvement')) {
            $objValid->_expected = array('involvement', 'project', 'project_year', 'team', 'position', 'active');
            $objValid->_required = array('involvement', 'project', 'project_year', 'team', 'position', 'active');
            if($objValid->isValid()) {
                if($objMember->updateInvolvement($objValid->_post)) {                       
                    Helper::redirect('/sugarkms/'.$this->objURL->getCurrent(array('action', 'id'), false, array('action', 'edited', 'id', $id)));
                } else {
                    Helper::redirect('/sugarkms/'.$this->objURL->getCurrent(array('action', 'id'), false, array('action', 'edited-failed', 'id', $id)));
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
            <span style="float:right;font-size:12px;cursor:pointer;"><a href="/sugarkms/members/action/view/id/<?php echo $id; ?>"><u>View this profile</u></a></span>
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
                    <td><?php echo $objValid->validate('name'); ?><input type="text" name="name" id="name" value="<?php echo $objForm->stickyText('name', $member['name']); ?>" class="fld" /></td>
                <tr>
                    <th><label for="Gender">Gender: *</label></th>
                    <td><select name="gender" id="gender" class="gender">
                        <option value="Male" <?php echo $objForm->stickySelect('gender', 'Male', $member['gender']); ?>>Male</option>
                        <option value="Female" <?php echo $objForm->stickySelect('gender', 'Female', $member['gender']); ?>>Female</option>
                    </select></td>
                </tr>
                <tr>
                    <th>Date of Birth: </th>
                    <td>
                        <select name="day" id="day" style="width:50px;border:1px solid #aaa;">
                            <?php for($i=1;$i<=31;$i++) {
                                echo '<option value="'.$i.'"'.$objForm->stickySelect('day', $i, $member['day']).'>'.$i.'</option>';
                            } ?>
                        </select>
                        <select name="month" id="month" style="width:100px;border:1px solid #aaa;">
                            <option value="1" <?php echo $objForm->stickySelect('month', 1, $member['month']); ?>>January</option>
                            <option value="2" <?php echo $objForm->stickySelect('month', 2, $member['month']); ?>>February</option>
                            <option value="3" <?php echo $objForm->stickySelect('month', 3, $member['month']); ?>>March</option>
                            <option value="4" <?php echo $objForm->stickySelect('month', 4, $member['month']); ?>>April</option>
                            <option value="5" <?php echo $objForm->stickySelect('month', 5, $member['month']); ?>>May</option>
                            <option value="6" <?php echo $objForm->stickySelect('month', 6, $member['month']); ?>>June</option>
                            <option value="7" <?php echo $objForm->stickySelect('month', 7, $member['month']); ?>>July</option>
                            <option value="8" <?php echo $objForm->stickySelect('month', 8, $member['month']); ?>>August</option>
                            <option value="9" <?php echo $objForm->stickySelect('month', 9, $member['month']); ?>>September</option>
                            <option value="10" <?php echo $objForm->stickySelect('month', 10, $member['month']); ?>>October</option>
                            <option value="11" <?php echo $objForm->stickySelect('month', 11, $member['month']); ?>>November</option>
                            <option value="12" <?php echo $objForm->stickySelect('month', 12, $member['month']); ?>>December</option>
                        </select>
                        <select name="year" id="year" style="width:60px;border:1px solid #aaa;">
                            <?php       for($i=1985;$i<= date("Y") - 15;$i++) {
                                echo '<option value="'.$i.'"'.$objForm->stickySelect('year', $i, $member['year']).'>'.$i.'</option>';
                            } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="personal_email">Email: </label></th>
                    <td><?php echo $objValid->validate('personal_email'); echo $objValid->validate('duplicate_email');?>
                    <input type="text" name="personal_email" id="personal_email" value="<?php echo $objForm->stickyText('personal_email', $member['personal_email']); ?>" class="fld" /></td>
                <tr>
                <tr>
                    <th><label for="phone">Phone: </label></th>
                    <td><?php echo $objValid->validate('phone'); ?><input type="text" name="phone" id="phone" value="<?php echo $objForm->stickyText('phone', $member['phone']); ?>" class="fld" /></td>
                <tr>
                <tr>
                    <th><label for="skype">Skype: </label></th>
                    <td><?php echo $objValid->validate('skype'); ?><input type="text" name="skype" id="skype" value="<?php echo $objForm->stickyText('skype', $member['skype']); ?>" class="fld" /></td>
                <tr>
                <tr>
                    <th><label for="facebook">Facebook Link: </label></th>
                    <td><?php echo $objValid->validate('facebook'); ?><input type="text" name="facebook" id="facebook" value="<?php echo $objForm->stickyText('facebook', $member['facebook']); ?>" class="fld" /></td>
                <tr>
                <tr>
                    <th><label for="high_school">High School: </label></th>
                    <td><?php echo $objValid->validate('high_school'); ?><input type="text" name="high_school" id="high_school" value="<?php echo $objForm->stickyText('high_school', $member['high_school']); ?>" class="fld" /></td>
                <tr>
                <tr>
                    <th>Year of Graduation: </th>
                    <td>
                        <select name="grad_year_h" id="grad_year_h" style="width:60px;border:1px solid #aaa;">
                            <option value="" <?php if($member['grad_year_h'] == '') echo ' selected="selected"'; ?>></option>
                            <?php for($i=2000;$i<=2025;$i++) {
                                echo '<option value="'.$i.'"'.$objForm->stickySelect('grad_year_h', $i, $member['grad_year_h']).'>'.$i.'</option>';
                            } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                <tr>
                    <th><label for="uni">University: </label></th>
                    <td><?php echo $objValid->validate('uni'); ?><input type="text" name="uni" id="uni" value="<?php echo $objForm->stickyText('uni', $member['uni']); ?>" class="fld" /></td>
                <tr>
                <tr>
                    <th>Year of Graduation:</th>
                    <td><?php echo $objValid->validate('grad_year_u'); ?>
                        <select name="grad_year_u" id="grad_year_u" style="width:60px;border:1px solid #aaa;" >
                            <option value="" <?php if($member['grad_year_u'] == '') echo ' selected="selected"'; ?>></option>
                            <?php for($i=2005;$i<=2030;$i++) {
                                echo '<option value="'.$i.'"'.$objForm->stickySelect('grad_year_u', $i, $member['grad_year_u']).'>'.$i.'</option>';
                            } ?>
                        </select>
                    </td>
                </tr>
            </table>
            <label for="btn" class="sbm sbm_blue fl_l" style="length:40px;"><input type="submit" class="btn" value="Save changes" /></label>
            <br />
            <br />
        </form>
        <br />
        <?php 
            if($objMember->isAdmin($profile['id'])) { 
        ?>
            <form action="" method="post">
                <p><span style="font-size:18px;">Involvements with Sugar:</span>
                <span style="float:right;"><a href="#" class="addInvInEdit" data-url="/sugarkms/members/action/newInv/id/<?php echo $id; ?>">Add new row</a></span></p>            
                <div style="overflow-x:scroll;white-space: nowrap;" >
                <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" id="involvements">
                    <tr>
                        <th>Position</th>
                        <th style="width:100px;">Team</th>
                        <th>Project / Group</th>
                        <th>Project Year</th>
                        <th>Active</th>
                        <th>Remove</th>
                    </tr>
                    <?php 
                        if(!empty($involvements)) {
                            foreach($involvements as $row) {
                    ?>
                    <tr id="row-<?php echo $row['id']; ?>" data-id="<?php echo $row['id']; ?>">
                        <input type="hidden" name="involvement[]" value="<?php echo $row['id']; ?>" />
                        <td>
                            <select name="position[]" style="width:150px;border:1px solid #aaa;">
                                <?php foreach($positions as $position) {
                                    echo '<option value="'.$position['id'].'"'.$objForm->stickySelect('position[]', $position['id'] ,$row['position_id']).'>'.$position['name'].'</option>';
                                } ?>
                            </select>
                        </td>
                        <td>
                            <select name="team[]" style="width:100px;border:1px solid #aaa;">
                                <?php foreach($teams as $team) {
                                    echo '<option value="'.$team['id'].'"'.$objForm->stickySelect('team[]', $team['id'] ,$row['team_id']).'>'.$team['name'].'</option>';
                                } ?>
                            </select>
                        </td>
                        <td>
                            <select name="project[]" style="width:170px;border:1px solid #aaa;">
                                <?php foreach($projects as $project) {
                                    echo '<option value="'.$project['id'].'"'.$objForm->stickySelect('project[]', $project['id'] ,$row['project_id']).'>'.$project['name'].'</option>';
                                } ?>
                            </select>
                        </td>
                        <td>
                            <select name="project_year[]" style="width:60px;border:1px solid #aaa;" id="project_year_<?php echo $row['id']; ?>" class="activeToggleEdit">
                                    <?php for($i=2009;$i<=date('Y')+2;$i++) {
                                        echo '<option value="'.$i.'"'.$objForm->stickySelect('project_year[]', $i ,$row['year_start']).'>'.$i.'</option>';
                                    } ?>
                            </select>
                        </td>                        
                        <td id="active_<?php echo $row['id']; ?>">
                            <?php if($row['year_start'] < date("Y")) { ?>
                                <select style="width:50px;border:1px solid #aaa;" disabled="disabled">
                                    <option>No</option>
                                </select>
                                <input type="hidden" name="active[]" value="no" />
                            <?php } else { ?>
                                <select name="active[]" style="width:50px;border:1px solid #aaa;">
                                    <option value="yes" <?php echo $objForm->stickySelect('active[]', 'yes', $row['active']); ?>>Yes</option>
                                    <option value="no" <?php echo $objForm->stickySelect('active[]', 'no', $row['active']); ?>>No</option>
                                </select>
                            <?php }?>
                        </td>
                        <td><a href="#" class="confirmRemoveInv" data-url="/sugarkms/members/action/removeInv/id/">Remove</a></td>
                    </tr>
                    <?php
                            }
                        } else { 
                    ?>
                    <tr>
                        <td colspan="5" id="noInvolvement"><center>No involvements have been recorded.</center></td>
                    </tr>
                    <?php
                        }                
                    ?>
                </table>
                </div>
                <label for="btn" class="sbm sbm_blue fl_l" style="length:40px;"><input type="submit" class="btn" value="Save changes" /></label>
                <br />
                <br />
            </form>
        <?php
            } else { 
        ?>
            <p><span style="font-size:18px;">Involvements with Sugar:</span>   <span style="float:right;"><a href="#" class="addNewRow">Request new involvments</a></span></p>
            <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat">
                <tr>
                    <th>Position</th>
                    <th style="width:100px;">Team</th>
                    <th>Project Name</th>
                    <th>Project Year</th>
                    <th>Change</th>
                </tr>
                <?php 
                    if(!empty($involvements)) {
                        foreach($involvements as $row) { ?>
                <tr>
                    <td><?php echo $row['position_name']; ?></td>
                    <td><?php echo $row['team_name']; ?></td>                
                    <td><?php echo $row['project_name'].' '.$row['project_time']; ?></td>
                    <td><?php echo $row['participation_time']; ?></td>
                    <td>Request</td>
                </tr>
                <?php 
                        } 
                    } else {
                        ?>
                <tr>
                    <td colspan="5"><center>No involvements have been recorded.</center></td>
                </tr>
                <?php        
                    }
                ?>
            </table>
        <?php } ?>
        
    
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