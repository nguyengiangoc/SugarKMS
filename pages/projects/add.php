<?php
    $objMember = new Member();
    if($objMember->canAddProject($profile['id'])) {
       
       $objProject = new Project();
       $projects = $objProject->getAllProjectsForList();
       
       $objForm = new Form();
       $objValid = new Validation($objForm);
       
       $params = array();
       
       if($objForm->isPost('project_type_id')) {
            $objValid->_expected = array(
                'project_type_id',
                'project_year'
            );
            $objValid->_required = array(
                'project_type_id',
                'project_year'
            );
                       
            if($objValid->isValid()) {
                $valid = 'yes';
                $project_year = $objForm->getPost('project_year');
                $project_type_id = $objForm->getPost('project_type_id');
                $project_type = $objProject->getProjectTypeById($project_type_id);
                
                if(!empty($project_type)) {
                    $split = explode('_', $project_year);
                    $year_start = $split[0];
                    if(count($split) == 2) {
                        $wave = $split[1];
                    } else {
                        $wave = 0;
                    }
                    $month_start = $project_type['month_start'];
                    $month_end = $project_type['month_end'];
                    $same_start_end = $project_type['same_start_end'];
                    $write_two_years = $project_type['write_two_years'];
                    $year_end = $same_start_end == 'yes' ? $year_start : $year_start + 1;
                    
                    $params = array(
                        'project_type_id' => $project_type_id,
                        'wave_id' => $wave,
                        'month_start' => $month_start,
                        'year_start' => $year_start,
                        'month_end' => $month_end,
                        'year_end' => $year_end,
                        'same_start_end' => $same_start_end,
                        'write_two_years' => $write_two_years
                    ); 
                    
                    $return = $objProject->addProject($params);
                    if($return['result']) {
                        $id = $return['id'];
                        Helper::redirect('/sugarkms/'.$this->objURL->getCurrent(array(action, id), false, array('action', 'added', 'id', $id)));
                    } else {
                        $id = 'failed';
                        Helper::redirect('/sugarkms/'.$this->objURL->getCurrent(array(action, id), false, array('action', 'added-failed')));
                    }
                }
                
                
            } 
        }
       
       require_once('_header.php'); 
    
     
?>
    <h1>Project &amp; EXCO :: Add</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <?php //if(isset($_POST)) { echo '<pre>'.print_r($_POST).'</pre>'; }  ?>
        <table cellpadding="0" cellspacing="0" border="0" class="tbl_insert">
            <tr>
                <th><label for="project_type_id">Project Type: *</label></th>
                <td>
                    <select name="project_type_id" id="changeProjectType" style="width:170px;border:1px solid #aaa;" data-url="/sugarkms/mod/getUnaddedYears.php">
                        <?php if(!isset($_POST['project_type_id'])) { ?>
                            <option value="" id="projectTypeBlank"></option>
                        <?php } ?>
                        
                        <?php foreach($projects as $project) {
                            echo '<option value="'.$project['id'].'"'.$objForm->stickySelect('project_type_id', $project['id']).'>'.$project['name'].'</option>';
                        } ?>
                    </select>
                    <?php echo $objValid->validate('project_type_id'); ?>
                    <span id="projectTypeMessage" class="red"></span>
                </td>
            </tr>
            <tr>
                <th><label for="project_year"></label>Project Year: *</th>
                <td>
                    <select name="project_year" id="project_year" style="width:120px;border:1px solid #aaa;"
                        <?php if(!isset($_POST['project_year'])) { echo 'disabled=""'; } ?>  >
                        <option value="" id="projectYearBlank"></option>
                        <?php if(isset($_POST['project_year'])) { 
                            $unadded_years = $objProject->getUnaddedYears($_POST['project_type_id']);
                            foreach($unadded_years as $unadded) {
                        ?>
                            <option value="<?php echo $unadded['value']; ?>" 
                                class="projectYearOption" <?php echo $objForm->stickySelect('project_year', $unadded['value']); ?> >
                                <?php echo $unadded['label']; ?>
                            </option>
                        <?php
                            }
                        }?>
                        
                    </select>
                    <?php echo $objValid->validate('project_year'); ?>
                </td>
            </tr>
        </table>
        <label for="btn" class="sbm sbm_blue fl_l" style="margin-bottom:15px;"><input type="submit" id="addProjectBtn" class="btn" value="Add new project" />
        
    </form>

<?php
        require_once('_footer.php'); 
    } else {
        require_once('_header.php');
?>
    <h1>Access Denied</h1>
    You are not allowed to add new project.
<?php        
        require_once('_footer.php'); 
    }
?>  