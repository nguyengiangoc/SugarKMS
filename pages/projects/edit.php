<?php 
    
    $teams = $objProject->getMemberListWithTeam($id, $project['project_type_id'], true);
    $objTeam = new Team();
    $objPosition = new Position();
    $objForm = new Form();
    $allPositions = $objPosition->getAllPositionsInProject($project['project_type_id']);
    $allTeams = $objTeam->getAllTeamsInProject($project['project_type_id']);
    
    if($this->objURL->get('reload') == 'yes') {
        echo Plugin::get('front'.DS.'project_edit_member', array('teams' => $teams, 'project' => $project, 'total' => $objProject->_total));
        exit();
    }
    
    require_once('_header.php'); 
    
?>
        <h1>
            <?php //if($project['project_type_id'] !=  5) { echo 'Project'; }?>
            <?php echo $project['name'].' '.$project['project_time']; ?> :: Edit
                <span class="h2rightlink"><a href="/sugarkms/projects/id/<?php echo $id; ?>"><u>View this project</u></a></span>
        </h1>

        <h2><?php if($project['project_type_id'] !=  5) { echo 'Organizing Team'; } else { echo 'EXCO Members'; }?>
        </h2>
        <div class="addMemberForm" >
            <h4><strong>Add Members</strong></h4>
            <p>Only members whose profiles have been recorded in the system can be added.<br />
             This requirement reduces the need to input a member's profile again every time that member participates in a new project.</p>
            <form id="addMemberForm" data-url="/sugarkms/mod/addMemberToProject.php">
             <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" id="member_list" style="margin-bottom:25px;">
                <tr>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Team</th>
                    <th>Time</th>
                    <th>Action</th>
                </tr>
                <tr >
                    <td class="br_td br_btm">
                        <input type="text" id="autocomplete" data-url="/sugarkms/mod/getNameList.php" data-add="/sugarkms/members/action/add" 
                        data-reload="/sugarkms/projects/id/<?php echo $project['id']; ?>/action/edit/reload/yes" style="width:140px;">
                        <input type="hidden" name="project_type_id" id="project_type_id" value="<?php echo $project['project_type_id']; ?>"/>
                        <input type="hidden" name="project_id" id="projectId" value="<?php echo $project['id']; ?>" />
                        <input type="hidden" name="person_id" id="memberId" />
                        <input type="hidden" name="verified" id="verified" />
                        <span style="vertical-align:middle;visibility:hidden;" id="checkIcon"><img src="/sugarkms/images/icon_check.png" style="width:15px;height:15px;" /></span>
                    </td>
                    <td class="br_td br_btm">
                        <select id="selectPosition" style="width:120px;" name="position_id" data-url="/sugarkms/mod/getTeamsForPosition.php" disabled="">
                            <option value="" id="positionOptionBlank"></option>
                            <?php foreach($allPositions as $position) {
                                echo '<option value="'.$position['id'].'"'.$objForm->stickySelect('position[]', $position['id']).'>'.$position['name'].'</option>';
                            } ?>
                        </select>
                    </td>
                    <td class="br_td br_btm">
                        <?php foreach($allTeams as $team) {
                                echo '<option value="'.$team['id'].'"'.$objForm->stickySelect('team[]', $team['id']).' class="teamOption teamOptionHide">'.$team['name'].'</option>';
                        } ?>
                        <select id="selectTeam" disabled="" style="width:100px;" name="team_id">
                            <option value="" id="teamOptionBlank"></option>
                            
                        </select>
                    </td>
                    <td class="br_td br_btm">
                        From &nbsp;
                        <select id="selectStart" disabled="" style="width:75px;">
                            <option value="" id="startOptionBlank"></option>                        
                            <?php
                                if ($project['same_start_end'] == 'no') {
                                    for($i=$project['month_start'];$i<13;$i++) {
                                        echo '<option value="'.$i.'_'.$project['year_start'].'">'.$i.'/'.$project['year_start'].'</option>';
                                    }
                                    for($i=1;$i<$project['month_end']+1;$i++) {
                                        echo '<option value="'.$i.'_'.$project['year_end'].'">'.$i.'/'.$project['year_end'].'</option>';
                                    }
                                } else {
                                    for($i=$project['month_start'];$i<$project['month_end']+1;$i++) {
                                        echo '<option value="'.$i.'_'.$project['year_start'].'">'.$i.'/'.$project['year_start'].'</option>';
                                    }
                                }
                            ?>
                        </select>
                        &nbsp;
                        To &nbsp;
                        <select id="selectEnd" disabled="" style="width:75px;">
                            <option value="" id="endOptionBlank"></option>                        
                            <?php
                                if ($project['same_start_end'] == 'no') {
                                    for($i=$project['month_start'];$i<13;$i++) {
                                        echo '<option value="'.$i.'_'.$project['year_start'].'">'.$i.'/'.$project['year_start'].'</option>';
                                    }
                                    for($i=1;$i<$project['month_end']+1;$i++) {
                                        echo '<option value="'.$i.'_'.$project['year_end'].'">'.$i.'/'.$project['year_end'].'</option>';
                                    }
                                } else {
                                    for($i=$project['month_end'];$i>$project['month_start']-1;$i--) {
                                        echo '<option value="'.$i.'_'.$project['year_start'].'">'.$i.'/'.$project['year_start'].'</option>';
                                    }
                                }
                            ?>
                        </select>                        
                        </select>
                    </td>
                    <td class="br_td br_btm">
                        <label for="btn" class="sbm sbm_blue">
                            <input type="submit" class="btn" value="Add" id="addMemberBtn" />
                        </label>
                    </td>
                </tr>
                <tr>
                    <td class="br_btm prompt" id="addMemberMessageRow" colspan="5" style="display:none;">
                    
                    </td>
                </tr>
             </table>
             </form>
        </div>
        <div id="list">
            <?php echo Plugin::get('front'.DS.'project_edit_member', array('teams' => $teams, 'project' => $project, 'total' => $objProject->_total)); ?>
        </div>


<?php 
    require_once('_footer.php'); 
?>