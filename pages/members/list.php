<?php
    $objMember = new Member();
    $rows = array();
    $objForm = new Form();
    
    $objProject = new Project();
    $projects = $objProject->getProjectsForSearch();
    
    $objTeam = new Team();
    $teams = $objTeam->getTeamsForSearch();
    
    $objPosition = new Position();
    $positions = $objPosition->getAllPositions(true);
    
    $objValid = new Validation($objForm);
    
    $criteria = array('personal' => array(), 'involvements' => array());
    
    if($this->objURL->get('search')) {
        $name = $objForm->getPost('name');
        if(!empty($name)) { $criteria['personal']['name'] = $name; }
        
        $gender = $objForm->getPost('gender');
        if(!empty($gender)) { $criteria['personal']['gender'] = $gender; }
        
        $day = $objForm->getPost('day');
        if(!empty($day)) { $criteria['personal']['day'] = $day; }
        
        $month = $objForm->getPost('month');
        if(!empty($month)) { $criteria['personal']['month'] = $month; }
        
        $year = $objForm->getPost('year');
        if(!empty($year)) { $criteria['personal']['year'] = $year; }
        
        $high_school = $objForm->getPost('high_school');
        if(!empty($high_school)) { $criteria['personal']['high_school'] = $high_school; }
        
        $grad_year_h = $objForm->getPost('grad_year_h');
        if(!empty($grad_year_h)) { $criteria['personal']['grad_year_h'] = $grad_year_h; }
        
        $uni = $objForm->getPost('uni');
        if(!empty($uni)) { $criteria['personal']['uni'] = $uni; }
        
        $grad_year_u = $objForm->getPost('grad_year_u');
        if(!empty($grad_year_u)) { $criteria['personal']['grad_year_u'] = $grad_year_u; }
        
        $project = $objForm->getPost('project');
        if(!empty($project)) { $criteria['involvements']['project_type_id'] = $project; }
        
        $project_year = $objForm->getPost('project_year');
        if(!empty($project_year)) { $criteria['involvements']['project_year'] = $project_year; }
        
        $team = $objForm->getPost('team');
        if(!empty($team)) { $criteria['involvements']['team_id'] = $team; }
        
        $position = $objForm->getPost('position');
        if(!empty($position)) { $criteria['involvements']['position_id'] = $position; }
        
        if(!empty($criteria['personal']) || !empty($criteria['involvements'])) {
            
            $rows = $objMember->getMemberByCrit($criteria);
        
            echo Plugin::get('front'.DS.'member_search', array(
                'profile' => $profile,
                'rows' => $rows,
                'objMember' => $objMember
            ));
            exit();
        } else {
            echo '';
            exit();
        }
        
        
    }
    
    
    
    require_once('_header.php');
    
?>
<h1>Member :: Search
    <span style="float:right;font-size:12px;cursor:pointer;">
        <!--
<a id="clearFields"><u>Clear all fields</u></a> |
-->
        <a id="clearSearch"><u>Clear search</u></a>
    </span>
</h1>
<?php //print_r($criteria); ?>
<form action="" method="post" id="search" data-url="/sugarkms/members/search/true">
<div class="fl_l">
    <span style="font-size:18px;">Personal Information</span>
    <table cellpadding="0" cellspacing="0" border="0" class="tbl_insert" style="margin-top:14px;">
    
        <tr>
            <th><label for="name">Name:</label></th>
            <td><input type="text" name="name" id="srch" value="<?php echo $objForm->stickyText('name'); ?>" class="fld200" /></td>
        </tr>
        <tr>
            <th><label for="Gender">Gender: </label></th>
            <td><select name="gender" id="gender" class="gender">
                <option value="" <?php echo $objForm->stickySelect('gender', ''); ?>></option>
                <option value="Male" <?php echo $objForm->stickySelect('gender', 'Male'); ?>>Male</option>
                <option value="Female" <?php echo $objForm->stickySelect('gender', 'Female'); ?>>Female</option>
            </select></td>
        </tr>
        <tr>
            <th><label for="month">Date of birth:</label></th>
            <td>
                <select name="day" id="day" style="width:50px;border:1px solid #aaa;">
                    <option value="" <?php echo $objForm->stickySelect('day', ''); ?>></option>
                    <?php for($i=1;$i<=31;$i++) {     
                        echo '<option value="'.$i.'"'.$objForm->stickySelect('day', $i).'>'.$i.'</option>';
                    } ?>
                </select>
                <select name="month" id="month" style="width:50px;border:1px solid #aaa;">
                    <option value="" <?php echo $objForm->stickySelect('month', ''); ?>></option>
                    <?php for($i=1;$i<=12;$i++) {     
                        echo '<option value="'.$i.'"'.$objForm->stickySelect('month', $i).'">'.$i.'</option>';
                    } ?>
                </select>
                <select name="year" id="year" style="width:60px;border:1px solid #aaa;">
                    <option value="" <?php echo $objForm->stickySelect('year', ''); ?>></option>
                    <?php for($i=1985;$i<=date("Y") - 15;$i++) {
                        echo '<option value="'.$i.'"'.$objForm->stickySelect('year', $i).'>'.$i.'</option>';
                    } ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="high_school">High School: </label></th>
            <td><input type="text" name="high_school" id="high_school" class="fld200"
                value="<?php echo $objForm->stickyText('high_school'); ?>" data-url="/sugarkms/mod/getSchoolList.php"  /></td>
        <tr>
        <tr>
            <th>Year of Graduation: </th>
            <td>
                <select name="grad_year_h" id="grad_year_h" style="width:60px;border:1px solid #aaa;">
                    <option value=""></option>
                    <?php for($i=2000;$i<=2025;$i++) {
                        echo '<option value="'.$i.'"'.$objForm->stickySelect('grad_year_h', $i).'>'.$i.'</option>';
                    } ?>
                </select>
            </td>
        </tr>
        <tr>
        <tr>
            <th><label for="uni">University: </label></th>
            <td><input type="text" name="uni" id="uni" class="fld200"
                value="<?php echo $objForm->stickyText('uni'); ?>"  data-url="/sugarkms/mod/getSchoolList.php" /></td>
        <tr>
        <tr>
            <th>Year of Graduation: </th>
            <td>
                <select name="grad_year_u" id="grad_year_u" style="width:60px;border:1px solid #aaa;" >
                    <option value=""></option>
                    <?php for($i=2005;$i<=2030;$i++) {
                        echo '<option value="'.$i.'"'.$objForm->stickySelect('grad_year_u', $i).'>'.$i.'</option>';
                    } ?>
                </select>
            </td>
        </tr>
    </table>
</div>
    
<div class="fl_r" style="margin-right:15px;">
    <span style="font-size:18px;">Involvement with Sugar</span>
    <table cellpadding="0" cellspacing="0" border="0" class="tbl_insert" style="margin-top:14px;">
        <tr>
            <th>Position:</th>
            <td>
                <select name="position" style="width:180px;border:1px solid #aaa;">
                    <option value=""></option>
                    <?php foreach($positions as $position) {
                        echo '<option value="'.$position['id'].'"'.$objForm->stickySelect('position', $position['id']).'>'.$position['name'].'</option>';
                    } ?>
                </select>
            </td>
        </tr>
        <tr>
            <th>Team:</th>
            <td>
                <select name="team" style="width:180px;border:1px solid #aaa;">
                    <option value=""></option>
                    <?php foreach($teams as $team) {
                        echo '<option value="'.$team['id'].'"'.$objForm->stickySelect('team', $team['id']).'>'.$team['name'].'</option>';
                    } ?>
                </select>
            </td>
        </tr>
        <tr>
            <th>Project / Group:</th>
            <td>
                <select name="project" id="projectName" style="width:180px;border:1px solid #aaa;">
                    <option value=""></option>
                    <?php foreach($projects as $project) {
                        echo '<option value="'.$project['id'].'"'.$objForm->stickySelect('project', $project['id']).'>'.$project['name'].'</option>';
                    } ?>
                </select>
            </td>
        </tr>
        <tr>
            <th>Project Year:</th>
            <td>
                <select name="project_year" id="projectYear" style="width:60px;border:1px solid #aaa;">
                    <option value=""></option>
                    <?php for($i=date('Y')+1;$i>=2009;$i--) {
                        echo '<option value="'.$i.'"'.$objForm->stickySelect('project_year', $i).'>'.$i.'</option>';
                    } ?>
                </select>
            </td>
            
        </tr>
    </table>
</div>
    <label for="btn" class="sbm sbm_blue fl_l" style="length:40px;clear:left;margin-bottom:15px;">
        <input type="submit" class="btn" value="Search member" id="loadResult" />
    </label>
    <br />
    <br />
</form>

<div id="result">

</div>               
<?php
 
    require_once('_footer.php');  
?>