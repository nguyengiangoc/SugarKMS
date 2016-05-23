<?php
    
    if(isset($data['params']) && isset($data['params']['id'])) {
        $params = $data['params'];
        $id = $params['id'];
        
        
        $objProject = new Project();        
        $objMember = new Member();
        $objPosition = new Position();
        $objTeam = new Team();
        
        $involvement = $objMember->getInvolvements(array('id' => $id));
        
        if(!empty($involvement)) {
            $involvement = $involvement[0];
            $project = $objProject->getProjectById($involvement['project_id']);
            $member = $objMember->getMemberById($involvement['member_id']);
        
        
        
    ?>
        <h2 class="borderBottom">
            Manage Involvement :: <?php echo $member['name']; ?>
            <a class="closeInvolvementDetails h2rightlink" href="#">Close</a>
        </h2>
        
        <div class="sectionParams" data-params="id=<?php echo $involvement['id']; ?>"></div>
        <br />
        
        <table cellpadding="0" cellspacing="0" border="0" style="width:100%;vertical-align:middle;" class="panelTable horizontalTable" data-object="page">
            <tr>
                <td >
                    <h4>Current Details</h4>
                    <table cellpadding="0" cellspacing="0" border="0" class="horizontalTable" style="width:90%">
                        <tr>
                            <th class="borderTop" style="width:30%"><strong>Position</strong></th>
                            <td class="borderRight borderTop"><?php echo $involvement['position_name']; ?></td>
                        </tr>
                        <tr>
                            <th><strong>Team</strong></th>
                            <td class="borderRight"><?php echo $involvement['team_name']; ?></td>
                        </tr>
                        <tr>
                            <th><strong>In Charge</strong></th>
                            <td class="borderRight"><?php echo $involvement['in_charge']; ?></td>
                        </tr>

                        <tr>
                            <th><strong>Time</strong></th>
                            <td class="borderRight"> <?php echo $involvement['month_start']; ?>/<?php echo $involvement['year_start']; ?> - <?php echo $involvement['month_end']; ?>/<?php echo $involvement['year_end']; ?></td>
                        </tr>  

                        <tr>
                            <th><strong>Status</strong></th>
                            <td class="borderRight">
                                <?php 
                                    if(($involvement['year_end'] < $project['year_end']) || ($involvement['year_end'] == $project['year_end'] && $involvement['month_end'] < $project['month_end']) ) {
                                        echo 'Withdrawn';
                                    } else {
                                        if(($project['year_end'] < date("Y")) || ($project['year_end'] == date("Y") && $project['month_end'] < date("m"))) {
                                            echo 'Completed';
                                        } else {
                                            echo 'Active';
                                        }
                                    }
                                ?>
                            </td>
                        </tr>
                    </table>
                    <br />
                </td>
                <td style="width:50%;">
                    <h4>Change Details</h4> 
                    <form class="changeInvolvementDetails" style="display:inline-block;">
                        <table cellpadding="0" cellspacing="0" border="0" class="horizontalTable">
                        <tr>
                            <td ><strong>Position</strong></td>
                            <td>
                                <select class="selectPosition" style="width:140px;" name="position_id">
                                    <?php 
                                        $positions = $objPosition->getAllPositionsInProject($project['project_type_id']);
                                        foreach($positions as $position) {
                                            $option = '<option value="'.$position['id'].'"';
                                            if($position['id'] == $involvement['position_id']) {
                                                $option .= ' selected="selected" ';
                                            }
                                            $option .= '>'.$position['name'].'</option>';
                                            echo $option;
                                        } 
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Team</strong></td>
                            <td>
                                <select class="selectTeam" style="width:140px;" name="team_id">
                                    <?php 
                                        $teams = $objTeam->getTeamsForPosition($involvement['position_id'], $project['project_type_id']);
                                        foreach($teams as $team) {
                                            echo '<option value="'.$team['id'].'">'.$team['name'].'</option>';
                                        } 
                                    ?>
                                    
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>In Charge</strong></td>
                            <td><input type="text" name="in_charge" value="<?php echo $involvement['in_charge']; ?>" /></td>
                        </tr>
                        <tr>
                            <td><strong>From</strong></td>
                            <td>
                                <select class="selectStart" style="width:75px;">                    
                                    <?php
                                        if ($project['same_start_end'] == '0') {
                                            for($i=$project['month_start'];$i<13;$i++) {
                                                $option = '<option value="'.$i.'_'.$project['year_start'].'" ';
                                                
                                                if($i==$involvement['month_start']) {
                                                    $option .= ' selected="selected" ';
                                                }
                                                
                                                $option .= ' >'.$i.'/'.$project['year_start'].'</option>';
                                                
                                                echo $option;
                                            }
                                            for($i=1;$i<$project['month_end']+1;$i++) {
                                                
                                                $option = '<option value="'.$i.'_'.$project['year_end'].'" ';
                                                
                                                if($i==$involvement['month_start']) {
                                                    $option .= ' selected="selected" ';
                                                }
                                                
                                                $option .= ' >'.$i.'/'.$project['year_end'].'</option>';
                                                
                                                echo $option;
                                            }
                                        } else {
                                            for($i=$project['month_start'];$i<$project['month_end']+1;$i++) {
                                                
                                                $option = '<option value="'.$i.'_'.$project['year_start'].'" ';
                                                
                                                if($i==$involvement['month_start']) {
                                                    $option .= ' selected="selected" ';
                                                }
                                                
                                                $option .= ' >'.$i.'/'.$project['year_start'].'</option>';
                                                
                                                echo $option;
                                            }
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>To</strong></td>
                            <td>
                                <select class="selectEnd" style="width:75px;">                       
                                    <?php
                                        
                                        if ($project['same_start_end'] == '0') {
                                            for($i=$project['month_start'];$i<13;$i++) {
                                                $option = '<option value="'.$i.'_'.$project['year_start'].'" ';
                                                
                                                if($i==$involvement['month_end']) {
                                                    $option .= ' selected="selected" ';
                                                }
                                                
                                                $option .= ' >'.$i.'/'.$project['year_start'].'</option>';
                                                
                                                echo $option;
                                            }
                                            for($i=1;$i<$project['month_end']+1;$i++) {
                                                
                                                $option = '<option value="'.$i.'_'.$project['year_end'].'" ';
                                                
                                                if($i==$involvement['month_end']) {
                                                    $option .= ' selected="selected" ';
                                                }
                                                
                                                $option .= ' >'.$i.'/'.$project['year_end'].'</option>';
                                                
                                                echo $option;
                                            }
                                        } else {
                                            for($i=$project['month_start'];$i<$project['month_end']+1;$i++) {
                                                
                                                $option = '<option value="'.$i.'_'.$project['year_start'].'" ';
                                                
                                                if($i==$involvement['month_end']) {
                                                    $option .= ' selected="selected" ';
                                                }
                                                
                                                $option .= ' >'.$i.'/'.$project['year_start'].'</option>';
                                                
                                                echo $option;
                                            }
                                        }
                                                                          
                                        //if ($project['same_start_end'] == '0') {
//                                            for($i=$project['month_end'];$i>0;$i--) {
//                                                echo '<option value="'.$i.'_'.$project['year_end'].'">'.$i.'/'.$project['year_end'].'</option>';
//                                            }
//                                            for($i=12;$i>$project['month_start']-1;$i--) {
//                                                echo '<option value="'.$i.'_'.$project['year_start'].'">'.$i.'/'.$project['year_start'].'</option>';
//                                            }
//                                        } else {
//                                            for($i=$project['month_end'];$i>$project['month_start']-1;$i--) {
//                                                echo '<option value="'.$i.'_'.$project['year_start'].'">'.$i.'/'.$project['year_start'].'</option>';
//                                            }
//                                        }
                                    ?>
                                </select>                        
                            
                        </td>
                        </tr>
                    </table>
                    <br />
                    <label for="btn" class="sbm sbm_blue" >
                        <input type="submit" class="btn changeInvolvementDetailsBtn" value="Save" />
                    </label>
                        
                    </form>
                    <br /><br />
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br />
                    <h4>Remove Member</h4>
                    <strong>WARNING:</strong> 
                    <br />
                    - Only remove when this member <strong>HAS NEVER</strong> participated in this project (incorrect record).<br />
                    - If this member withdraws from this project, change the <strong>involvement time</strong>.<br />
                    - <strong>CONTACT ADMIN</strong> before removing.<br />
                    <br />
                    <label for="btn" class="sbm sbm_blue" >
                        <input type="submit" class="btn removeMemberBtn" value="Remove" />
                    </label>
                    <br /><br />
                </td>
            </tr>

        </table>

    
    <?php    
        } else {
            
            ?>
        
                    <h2 class=" borderBottom">Manage Involvement</h2>
                    <br />
                    Click the member's row to manage their involvement.
                
                <?php 
            
        }
        
    } else {
        $objPage = new Page();
    ?>
        <!--
<h2>Add Organizer</h2>
        <div class="addMemberForm" >
            <p><strong>NOTICE:</strong> Only members whose <strong>profiles have been recorded</strong> in the system can be added.<br />
            <input type="hidden" name="verified" id="verified" />
            <form id="addMemberForm">
             <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" id="member_list" style="margin-bottom:25px;">
                <tr>                    
                    <th>Name</th>
                    <th>Position</th>
                    <th>Team</th>
                    <th>Action</th>
                </tr>
                <tr>
                    <td>
                        <input type="text" id="autocomplete" data-url="/sugarkms/mod/getNameList.php" data-add="<?php echo $objPage->generateURL('member', array('action' => 'add')); ?>" 
                        style="width:140px;" />
                        <input type="hidden" name="project_id" id="project_id" value="<?php echo $project['id']; ?>" />
                        <input type="hidden" name="project_type_id" id="project_type_id" value="<?php echo $project['project_type_id']; ?>" />
                        <input type="hidden" name="wave_id" id="wave_id" value="<?php echo $project['wave_id']; ?>" />
                        <input type="hidden" name="member_id" id="member_id" />
                        
                        <span style="vertical-align:middle;visibility:hidden;" id="checkIcon"><img src="/sugarkms/images/icon_check.png" style="width:15px;height:15px;" /></span>
                    </td>
                    <td>
                        <select class="selectPosition" style="width:140px;" name="position_id" data-url="/sugarkms/mod/getTeamsForPosition.php" disabled="">
                            <option value="" class="positionOptionBlank"></option>
                            <?php foreach($allPositions as $position) {
                                echo '<option value="'.$position['id'].'" >'.$position['name'].'</option>';
                            } ?>
                        </select>
                    </td>
                    <td>
                        <select class="selectTeam" disabled="" style="width:120px;" name="team_id">
                            <option value="" class="teamOptionBlank"></option>
                            
                        </select>
                    </td>
                    <td class=" ">
                        <label for="btn" class="sbm sbm_blue">
                            <input type="submit" class="btn" value="Add" id="addMemberBtn" />
                        </label>
                    </td>
                </tr>
                <tr>
                    <td class=" prompt" id="addMemberMessageRow" colspan="5" style="display:none;">
                    
                    </td>
                </tr>
             </table>
             </form>
        </div>
-->
        
        
          
        <h2 class=" borderBottom">Manage Involvement</h2>
        <br />
        Click the member's row to manage their involvement.
    
    <?php    
        
    }
    

?>
    
    
    
    