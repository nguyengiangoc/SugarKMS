<?php 
    if($objMember->canAddMember($profile['id'])) {
        $objForm = new Form();       
        $objValid = new Validation($objForm);
    
        
        $result = null;
        
        if($objForm->isPost('name')) {
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
                'gender'
            );
            $high_school = $objForm->getPost('high_school');
            $grad_year_h = $objForm->getPost('grad_year_h');
            $uni = $objForm->getPost('uni');
            $grad_year_u = $objForm->getPost('grad_year_u');
            $email = $objForm->getPost('personal_email');
            
            if(empty($high_school) && !empty($grad_year_h)) { $objValid->add2Errors('high_school'); }
            if(!empty($high_school) && empty($grad_year_h)) { $objValid->add2Errors('grad_year_h'); }
            
            if(empty($uni) && !empty($grad_year_u)) { $objValid->add2Errors('uni'); }
            if(!empty($uni) && empty($grad_year_u)) { $objValid->add2Errors('grad_year_u'); }
            
            if($objMember->isDuplicateEmail($email, $id)) { $objValid->add2Errors('duplicate_email'); }
            
            if($objValid->isValid()) {  
                $return = $objMember->addMember($objValid->_post);
                if($return['result']) {
                    $id = $return['id'];
                    Helper::redirect('/sugarkms/'.$this->objURL->getCurrent(array(action, id), false, array('action', 'added', 'id', $id)));
                } else {
                    Helper::redirect('/sugarkms/'.$this->objURL->getCurrent(array(action, id), false, array('action', 'added-failed')));
                }
                
            }
        }
        require_once('_header.php'); 
?>
<h1>Member :: Add</h1>
<form action="" method="post" enctype="multipart/form-data">
    <?php //if(isset($_POST)) { echo '<pre>'.print_r($_POST).'</pre>'; }  ?>
    <h2>Personal Information:</h2>
    <table cellpadding="0" cellspacing="0" border="0" class="tbl_insert">
        <tr>
            <th><label for="name">Name: *</label></th>
            <td>
                <input type="text" name="name" id="checkNameExists" value="<?php echo $objForm->stickyText('name'); ?>" class="fld" data-url="/sugarkms/mod/checkNameExists.php" />
                <span id="nameMessage" class="red" ></span>
                <?php echo $objValid->validate('name'); ?>
            </td>
        <tr>
            <th><label for="Gender">Gender: *</label></th>
            <td><select name="gender" id="gender" class="gender">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
            </select></td>
        </tr>
        <tr>
            <th>Date of Birth: </th>
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
            <th><label for="personal_email">Email: </label></th>
            <td>
                <input type="text" name="personal_email" id="personal_email" value="<?php echo $objForm->stickyText('personal_email'); ?>" class="fld" />
                <?php echo $objValid->validate('personal_email'); echo $objValid->validate('duplicate_email');?>
            </td>
        </tr>
        <tr>
            <th><label for="phone">Phone: </label></th>
            <td>
                <input type="text" name="phone" id="phone" value="<?php echo $objForm->stickyText('phone'); ?>" class="fld" />
                <?php echo $objValid->validate('phone'); ?>
            </td>
        </tr>
        <tr>
            <th><label for="skype">Skype: </label></th>
            <td>
                <input type="text" name="skype" id="skype" value="<?php echo $objForm->stickyText('skype'); ?>" class="fld" />
                <?php echo $objValid->validate('skype'); ?>
            </td>
        </tr>
        <tr>
            <th><label for="facebook">Facebook: </label></th>
            <td>
                <input type="text" name="facebook" id="facebook" value="<?php echo $objForm->stickyText('facebook'); ?>" class="fld" />
                <?php echo $objValid->validate('facebook'); ?>
            </td>
        </tr>
        <tr>
            <th><label for="high_school">High School: </label></th>
            <td>
                <input type="text" name="high_school" id="high_school" class="fld"
                    value="<?php echo $objForm->stickyText('high_school'); ?>" data-url="/sugarkms/mod/getSchoolList.php"/>
                <?php echo $objValid->validate('high_school'); ?>
            </td>
        </tr>
        <tr>
            <th><label for="grad_year_h">Year of Graduation: </label></th>
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
            <th><label for="uni">University: </label></th>
            <td>
                <input type="text" name="uni" id="uni" class="fld"
                    value="<?php echo $objForm->stickyText('uni'); ?>" data-url="/sugarkms/mod/getSchoolList.php"/>
                <?php echo $objValid->validate('uni'); ?>
            </td>
        </tr>
        <tr>
            <th><label for="grad_year_u"></label> Year of Graduation: </th>
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


<?php 
        require_once('_footer.php'); 
    } else {
        require_once('_header.php');
?>
    <h1>Access Denied</h1>
    You are not allowed to add new member profile.
<?php      
        require_once('_footer.php');
    }
?>