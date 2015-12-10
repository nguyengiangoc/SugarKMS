<?php 
    if($objMember->canAddMember($profile['id'])) {
        $objForm = new Form();
        
        $objProject = new Project();
        $projects = $objProject->getAllProjects();
        
        $objTeam = new Team();
        $teams = $objTeam->getAllTeams();
        
        $objPosition = new Position();
        $positions = $objPosition->getAllPositions();
        
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
                'grad_year_u',
                'project',
                'year_start',
                'team',
                'position',
                'active'
            );
            $objValid->_required = array(
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
                'project',
                'year_start',
                'team',
                'position',
                'active'
            );
            $uni = $objForm->getPost('uni');
            $grad_year_u = $objForm->getPost('grad_year_u');
            $email = $objForm->getPost('personal_email');
            
            if(!empty($uni) && empty($grad_year_u)) { $objValid->add2Errors('grad_year_u'); }
            if($objMember->isDuplicateEmail($email, $id)) { $objValid->add2Errors('duplicate_email'); }
            
            if($objValid->isValid()) {  
                $return = $objMember->addMember($objValid->_post);
                if($return['result']) {
                    $id = $return['id'];
                    Helper::redirect('/sugarkms/'.$this->objURL->getCurrent(array(action, id), false, array('action', 'added', 'id', $id)));
                } else {
                    $id = "failed";
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
            <td><?php echo $objValid->validate('name'); ?><input type="text" name="name" id="name" value="<?php echo $objForm->stickyText('name'); ?>" class="fld" /></td>
        <tr>
            <th><label for="Gender">Gender: *</label></th>
            <td><select name="gender" id="gender" class="gender">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
            </select></td>
        </tr>
        <tr>
            <th>Date of Birth: *</th>
            <td>
                <select name="day" id="day" style="width:50px;border:1px solid #aaa;">
                    <?php for($i=1;$i<=31;$i++) {     
                        echo '<option value="'.$i.'">'.$i.'</option>';
                    } ?>
                </select>
                <select name="month" id="month" style="width:80px;border:1px solid #aaa;">
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
                <select name="year" id="year" style="width:60px;border:1px solid #aaa;">
                    <?php for($i=1985;$i<=date("Y") - 15;$i++) {
                        echo '<option value="'.$i.'">'.$i.'</option>';
                    } ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="personal_email">Personal Email: *</label></th>
            <td><?php echo $objValid->validate('personal_email'); echo $objValid->validate('duplicate_email');?>
            <input type="text" name="personal_email" id="personal_email" value="<?php echo $objForm->stickyText('personal_email'); ?>" class="fld" /></td>
        <tr>
        <tr>
            <th><label for="phone">Phone: *</label></th>
            <td><?php echo $objValid->validate('phone'); ?><input type="text" name="phone" id="phone" value="<?php echo $objForm->stickyText('phone'); ?>" class="fld" /></td>
        <tr>
        <tr>
            <th><label for="skype">Skype: *</label></th>
            <td><?php echo $objValid->validate('skype'); ?><input type="text" name="skype" id="skype" value="<?php echo $objForm->stickyText('skype'); ?>" class="fld" /></td>
        <tr>
        <tr>
            <th><label for="facebook">Facebook: *</label></th>
            <td><?php echo $objValid->validate('facebook'); ?><input type="text" name="facebook" id="facebook" value="<?php echo $objForm->stickyText('facebook'); ?>" class="fld" /></td>
        <tr>
        <tr>
            <th><label for="high_school">High School: *</label></th>
            <td><?php echo $objValid->validate('high_school'); ?><input type="text" name="high_school" id="high_school" value="<?php echo $objForm->stickyText('high_school'); ?>" class="fld" /></td>
        <tr>
        <tr>
            <th>Year of Graduation: *</th>
            <td><?php echo $objValid->validate('grad_year_h'); ?>
                <select name="grad_year_h" id="grad_year_h" style="width:60px;border:1px solid #aaa;">
                    <?php for($i=2000;$i<=2025;$i++) {
                        echo '<option value="'.$i.'">'.$i.'</option>';
                    } ?>
                </select>
            </td>
        </tr>
        <tr>
        <tr>
            <th><label for="uni">University: </label></th>
            <td><?php echo $objValid->validate('uni'); ?><input type="text" name="uni" id="uni" value="<?php echo $objForm->stickyText('uni'); ?>" class="fld" /></td>
        <tr>
        <tr>
            <th>Year of Graduation: </th>
            <td><?php echo $objValid->validate('grad_year_u'); ?>
                <select name="grad_year_u" id="grad_year_u" style="width:60px;border:1px solid #aaa;" >
                    <option value="">----</option>
                    <?php for($i=2005;$i<=2030;$i++) {
                        echo '<option value="'.$i.'">'.$i.'</option>';
                    } ?>
                </select>
            </td>
        </tr>
    </table>
    <p><span style="font-size:18px;">Involvements with Sugar:</span>   
    <span style="float:right;"><a href="#" class="addInvInAdd">Add new row</a></span></p>
    <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat involvements">
        <tr>
            <th>Project / Group</th>
            <th>Year End</th>
            <th style="width:100px;">Team</th>
            <th>Position</th>
            <th>Active</th>
            <th>Remove</th>
        </tr>
        <tr id="row1" data-id="1">
            <td>
                <select name="project[]" style="width:180px;border:1px solid #aaa;">
                    <option value=""></option>
                    <?php foreach($projects as $project) {
                        echo '<option value="'.$project['id'].'">'.$project['name'].'</option>';
                    } ?>
                </select>
            </td>
            <td>
                <select class="activeToggleAdd" name="year_start[]" style="width:60px;border:1px solid #aaa;">
                    <option value=""></option>
                    <?php for($i=2009;$i<=date('Y')+2;$i++) {
                        echo '<option value="'.$i.'">'.$i.'</option>';
                    } ?>
                </select>
            </td>
            <td>
                <select name="team[]" style="width:100px;border:1px solid #aaa;">
                    <option value=""></option>
                    <?php foreach($teams as $team) {
                        echo '<option value="'.$team['id'].'">'.$team['name'].'</option>';
                    } ?>
                </select>
            </td>
            <td>
                <select name="position[]" style="width:180px;border:1px solid #aaa;">
                    <option value=""></option>
                    <?php foreach($positions as $position) {
                        echo '<option value="'.$position['id'].'">'.$position['name'].'</option>';
                    } ?>
                </select>
            </td>
            <td class="active">
                <select style="width:50px;border:1px solid #aaa;" disabled="disabled">
                    <option>No</option>
                </select>
                <input type="hidden" name="active[]" value="no" />
                    
            </td>
            <td><a href="#" class="removeInvInAdd" data-id="1">Remove</a></td>
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