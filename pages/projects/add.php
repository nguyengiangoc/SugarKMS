<?php
    $exco = $this->cPage == 'exco' ? 1 : 0;
    $project_type = $this->cPage == 'exco' ? 'exco' : 'project';
    $objMember = new Member();

       
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
                //var_dump($project_year);
                
                if(!empty($project_type)) {
                    $split = explode('_', $project_year);
                    $year_start = $split[0];
                    if(count($split) == 2) {
                        $wave_id = $split[1];
                        $wave_info = $objProject->getWaves(array('id' => 3))[0];
                        $month_start = $wave_info['month_start'];
                        $month_end = $wave_info['month_end'];
                        $year_end = $wave_info['same_start_end'] == '1' ? $year_start : $year_start + 1;
                        
                        
                    } else {
                        $wave_id = 0;
                        $month_start = $project_type['month_start'];
                        $month_end = $project_type['month_end'];
                        $year_end = $project_type['same_start_end'] == '1' ? $year_start : $year_start + 1;
                    }
                    
                    
                    $params = array(
                        'project_type_id' => $project_type_id,
                        'wave_id' => $wave_id,
                        'month_start' => $month_start,
                        'year_start' => $year_start,
                        'month_end' => $month_end,
                        'year_end' => $year_end
                    ); 
                    
                    //var_dump($params);
                    
                    $return = $objProject->addProject($params);
                    if($return['result']) {
                        $id = $return['id'];
                        if($project_type_id == 5) {
                            $project_type = 'exco';
                        } else {
                            $project_type = 'project';
                        }
                        Helper::redirect($this->objPage->generateURL($project_type, array('id' => $id)));
                    } else {
                        $success = false;
                    }
                }
                
                
            } 
        }
       
       $header = $exco ? 'EXCO' : 'Project';  
       $header .= ' :: Add';
       require_once('_header.php'); 
    
     
?>
    <h1><?php echo $header; ?></h1>
    <?php if(!isset($success)) { ?>
        <form action="" method="post" enctype="multipart/form-data">
            <?php //if(isset($_POST)) { echo '<pre>'.print_r($_POST).'</pre>'; }  ?>
            <table cellpadding="0" cellspacing="0" border="0" class="tbl_insert">
                <tr>
                    <td><label for="project_type_id">Project Type: *</label></td>
                    <td>
                        <select name="project_type_id" id="changeProjectType" style="width:170px;border:1px solid #aaa;" data-url="/sugarkms/mod/getUnaddedYears.php">
                            <?php if(!isset($_POST['project_type_id'])) { ?>
                                <option value="" id="projectTypeBlank"></option>
                            <?php } 
                                if($exco) {
                                    echo '<option value="5">EXCO</option>';
                            ?>
                                
                            <?php
                                } else {
                                    foreach($projects as $project) {
                                        echo '<option value="'.$project['id'].'"'.$objForm->stickySelect('project_type_id', $project['id']).'>'.$project['name'].'</option>';
                                    }
                                }
                            ?>
                        </select>
                        <?php echo $objValid->validate('project_type_id'); ?>
                        <span id="projectTypeMessage" class="red"></span>
                    </td>
                </tr>
                <tr>
                    <td><label for="project_year"></label>Project Year: *</td>
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
    <?php } else {  
            if(!$success) {
                ?>
                    <p><strong>There was a problem</strong> adding this project / this EXCO.<br />Please contact administrator.<br />
                    <a href="<?php echo $this->getCurrentURL(); ?>">Try adding again.</a><br />
                <?php
            } else {
                
            }
         
    } ?>
<?php
        require_once('_footer.php'); 
?>  