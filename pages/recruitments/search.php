<?php
    
    $rows = array();
    
    $objRecruitment = new Recruitment();
    
    $objProject = new Project();
    $projects = $objProject->getAllProjectTypes();
    $waves = $objProject->getWaves();
    
    $objTeam = new Team();
    $teams = $objTeam->getTeamsForSearch();
    
    $objPosition = new Position();
    $positions = $objPosition->getAllPositions(true);
    
    $objForm = new Form();
    
    $criteria = array();
    
    if($objForm->isPost('position')) { 
        
                
        $project = $objForm->getPost('project');
        if(!empty($project)) { $criteria['project_type_id'] = $project; }
        
        $project_year = $objForm->getPost('project_year');
        if(!empty($project_year)) { $criteria['project_year'] = $project_year; }
        
        $project_wave = $objForm->getPost('project_wave');
        if(!empty($project_wave)) { $criteria['project_wave'] = $project_wave; }
        
        $team = $objForm->getPost('team');
        if(!empty($team)) { $criteria['team_id'] = $team; }
        
        $position = $objForm->getPost('position');
        if(!empty($position)) { $criteria['position_id'] = $position; }
        
        if(!empty($criteria)) {
            
            $rows = $objRecruitment->getRecruitmentByCriteria($criteria);           
            
            //var_dump($rows);
            
            echo Plugin::get('recruitment_search', array('rows' => $rows));
            exit();
        } else {
            echo '';
            exit();
        }

    }
    
    $header = 'Recruitment :: Search';
    require_once('_header.php');
    
?>
    <h1><?php echo $header; ?>
        <a class="h2rightlink" id="clearSearch"><u>Clear search</u></a>   
    </h1>
    <?php //print_r($criteria); ?>
    <form action="" method="" id="search" data-url="<?php echo $this->getCurrentURL(); ?>">
            
            <table cellpadding="0" cellspacing="0" border="0" style="margin-top:5px;">
                <tr>
                    <td>Position</td>
                    <td>
                        <select name="position" style="width:180px;border:1px solid #aaa;">
                            <option value=""></option>
                            <?php foreach($positions as $position) {
                                echo '<option value="'.$position['id'].'">'.$position['name'].'</option>';
                            } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Team</td>
                    <td>
                        <select name="team" style="width:180px;border:1px solid #aaa;">
                            <option value=""></option>
                            <?php foreach($teams as $team) {
                                echo '<option value="'.$team['id'].'">'.$team['name'].'</option>';
                            } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Project / EXCO</td>
                    <td>
                        <select name="project" id="projectName" style="width:180px;border:1px solid #aaa;">
                            <option value=""></option>
                            <?php foreach($projects as $project) {
                                echo '<option value="'.$project['id'].'">'.$project['name'].'</option>';
                            } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Project Year</td>
                    <td>
                        <select name="project_year" id="projectYear" style="width:100px;border:1px solid #aaa;">
                            <option value=""></option>
                            <?php for($i=date('Y')+1;$i>=2009;$i--) {
                                echo '<option value="'.$i.'">'.$i.'</option>';
                            } ?>
                        </select>
                    </td>
                    
                </tr>
                <tr>
                    <td>Project Wave</td>
                    <td>
                        <select name="project_wave" style="width:100px;border:1px solid #aaa;">
                            <option value=""></option>
                            <?php foreach($waves as $wave) {
                                echo '<option value="'.$wave['id'].'">'.$wave['wave_name'].'</option>';
                            } ?>
                        </select>
                    </td>
                    
                </tr>
            </table>
    
        
        <label for="btn" class="sbm sbm_blue fl_l">
            <input type="submit" class="btn" value="Search recruitment" id="loadResult" />
        </label>
        <br />
        <br />
    
    </form>
    
    <div id="result">
    
    </div>               
<?php
 
    require_once('_footer.php');  
?>