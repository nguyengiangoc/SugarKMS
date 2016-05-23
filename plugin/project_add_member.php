        <?php
            
            $objPage = new Page();
            $objProject = new Project();
                
            if(!isset($data['params'])) {
                $project = $data['project'];                
                $id = $project['id'];
            } else {
                $id = $data['params']['id'];
                $project = $objProject->getProjectById($id);
            }

            $objPosition = new Position();
            $allPositions = $objPosition->getAllPositionsInProject($project['project_type_id']);
        
        ?>
        
        <div class="sectionParams" data-params="id=<?php echo $id; ?>">
        </div>
        
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
                <tr >
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
                    <!--
<td >
                        From &nbsp;
                        <select id="selectStart" disabled="" style="width:75px;">
                            <option value="" id="startOptionBlank"></option>                        
                            <?php
                                //if ($project['same_start_end'] == '0') {
//                                    for($i=$project['month_start'];$i<13;$i++) {
//                                        echo '<option value="'.$i.'_'.$project['year_start'].'">'.$i.'/'.$project['year_start'].'</option>';
//                                    }
//                                    for($i=1;$i<$project['month_end']+1;$i++) {
//                                        echo '<option value="'.$i.'_'.$project['year_end'].'">'.$i.'/'.$project['year_end'].'</option>';
//                                    }
//                                } else {
//                                    for($i=$project['month_start'];$i<$project['month_end']+1;$i++) {
//                                        echo '<option value="'.$i.'_'.$project['year_start'].'">'.$i.'/'.$project['year_start'].'</option>';
//                                    }
//                                }
                            ?>
                        </select>
                        &nbsp;
                        To &nbsp;
                        <select id="selectEnd" disabled="" style="width:75px;">
                            <option value="" id="endOptionBlank"></option>                        
                            <?php
                                //if ($project['same_start_end'] == '0') {
//                                    for($i=$project['month_end'];$i>0;$i--) {
//                                        echo '<option value="'.$i.'_'.$project['year_end'].'">'.$i.'/'.$project['year_end'].'</option>';
//                                    }
//                                    for($i=12;$i>$project['month_start']-1;$i--) {
//                                        echo '<option value="'.$i.'_'.$project['year_start'].'">'.$i.'/'.$project['year_start'].'</option>';
//                                    }
//                                    
//                                    
//                                    
//                                    
//                                    
//                                    //for($i=$project['month_start'];$i<13;$i++) {
////                                        echo '<option value="'.$i.'_'.$project['year_start'].'">'.$i.'/'.$project['year_start'].'</option>';
////                                    }
////                                    for($i=1;$i<$project['month_end']+1;$i++) {
////                                        echo '<option value="'.$i.'_'.$project['year_end'].'">'.$i.'/'.$project['year_end'].'</option>';
////                                    }
//                                } else {
//                                    for($i=$project['month_end'];$i>$project['month_start']-1;$i--) {
//                                        echo '<option value="'.$i.'_'.$project['year_start'].'">'.$i.'/'.$project['year_start'].'</option>';
//                                    }
//                                }
                            ?>
                        </select>                        
                        
                    </td>
-->
                    <td>
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